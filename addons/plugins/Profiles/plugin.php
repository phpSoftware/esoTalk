<?php
// Copyright 2014 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["Profiles"] = array(
	"name" => "Profiles",
	"description" => "Allows custom fields to be added to user profiles.",
	"version" => ESOTALK_VERSION,
    "author" => "esoTalk Team",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL" => "https://github.com/phpSoftware/esoTalk-v2/",
	"license" => "GPLv2",
	"dependencies" => array(
		"esoTalk" => "1.0.0g4"
	)
);

class ETPlugin_Profiles extends ETPlugin {

	/*
	|--------------------------------------------------------------------------
	| Plugin Setup
	|--------------------------------------------------------------------------
	*/

	public function setup($oldVersion = "")
	{
		// For the Profiles plugin, we need two tables.
		// The first, profile_field, stores information about the custom profile fields that
		// the administrator has defined.
		$structure = ET::$database->structure();
		$structure->table("profile_field")
			->column("fieldId", "int(11) unsigned", false)
			->column("name", "varchar(31)", false)
			->column("description", "varchar(255)")
			->column("type", "enum('text','textarea','select','radios','checkboxes','member')", "text")
			->column("options", "text")
			->column("showOnPosts", "tinyint(1)", 0)
			->column("hideFromGuests", "tinyint(1)", 0)
			->column("searchable", "tinyint(1)", 0)
			->column("position", "int(11)", 0)
			->key("fieldId", "primary")
			->exec(false);

		// The second, profile_data, stores the actual values of these fields that users
		// have entered in their profiles.
		$structure
			->table("profile_data")
			->column("memberId", "int(11) unsigned", false)
			->column("fieldId", "int(11) unsigned", false)
			->column("data", "text")
			->key(array("memberId", "fieldId"), "primary")
			->exec(false);

		// If this is the first installation of the Profiles plugin (e.g. on a fresh
		// esoTalk installation,) set up some default fields for the administrator.
		if (!$oldVersion) $this->createDefaultFields();

		// Version 1.0.0g4 of this plugin changed the way that profile data is stored
		// (previously it was just stored in the user preferences blob.) If we're
		// upgrading from an old version, we'll need to convert to the new format.
		elseif (version_compare($oldVersion, "1.0.0g4", "<")) {

			$this->createDefaultFields();
			$model = ET::getInstance("profileFieldModel");

			$result = ET::SQL()->select("memberId, preferences")->from("member")->exec();
			while ($row = $result->nextRow()) {
				ET::memberModel()->expand($row);
				if (!empty($row["preferences"]["about"])) $model->setData($row["memberId"], 1, $row["preferences"]["about"]);
				if (!empty($row["preferences"]["location"])) $model->setData($row["memberId"], 2, $row["preferences"]["location"]);
			}

		}

		return true;
	}

	protected function createDefaultFields()
	{
		$model = ET::getInstance("profileFieldModel");
		if ( ! $model->getById(1)) {
			$model->create(array(
				"fieldId"     => 1,
				"name"        => "About",
				"description" => "Write something about yourself.",
				"type"        => "textarea"
			));
		}
		if ( ! $model->getById(2)) {
			$model->create(array(
				"fieldId"     => 2,
				"name"        => "Location",
				"type"        => "text",
				"showOnPosts" => true,
				"searchable"  => true
			));
		}
	}

	public function __construct($rootDirectory)
	{
		parent::__construct($rootDirectory);

		// Register the profile_field model which provides convenient methods to
		// manage profile data.
		ETFactory::register("profileFieldModel", "ProfileFieldModel", dirname(__FILE__)."/ProfileFieldModel.class.php");

		// Register the profiles admin controller which provides an interface for
		// administrators to manage custom profile fields.
		ETFactory::registerAdminController("profiles", "ProfilesAdminController", dirname(__FILE__)."/ProfilesAdminController.class.php");
	}

	/*
	|--------------------------------------------------------------------------
	| Admin Page
	|--------------------------------------------------------------------------
	*/

	// When initializing any admin controller, add a link to the Profiles admin page
	// in the admin menu.
	public function handler_initAdmin($sender, $menu)
	{
		$menu->add("profiles", "<a href='".URL("admin/profiles")."'><i class='icon-smile'></i> ".T("Profiles")."</a>");
	}

	/*
	|--------------------------------------------------------------------------
	| Member About Pane
	|--------------------------------------------------------------------------
	*/

	// When initializing a member profile page, add the 'about' pane.
	public function handler_memberController_initProfile($sender, $member, $panes, $controls, $actions)
	{
		$panes->add("about", "<a href='".URL(memberURL($member["memberId"], $member["username"], "about"))."'>".T("About")."</a>", 0);
	}

	// Set the default member pane as the 'about' pane.
	public function action_memberController_index($sender, $member = "")
	{
		$this->action_memberController_about($sender, $member);
	}

	// Render the 'about' member profile pane.
	public function action_memberController_about($sender, $member = "")
	{
		if (!($member = $sender->profile($member, "about"))) return;

		$model = ET::getInstance("profileFieldModel");
		$fields = $model->getData($member["memberId"]);

		foreach ($fields as $k => &$field) {

			// If this field is hidden from guests and we're a guest, don't display it.
			if ($field["hideFromGuests"] and !ET::$session->user) unset($fields[$k]);

			switch ($field["type"]) {
				case "textarea":
					$field["data"] = ET::formatter()->init($field["data"])->format()->get();
					break;

				case "checkboxes":
					$items = explode("\n", $field["data"]);
					$items = array_map("sanitizeHTML", $items);
					$field["data"] = implode("<br>", $items);
					break;

				case "member":
					$field["data"] = "<a href='".URL("member/name/".urlencode($field["data"]))."'>".sanitizeHTML($field["data"])."</a>";
					break;

				default:
					$field["data"] = ET::formatter()->init($field["data"])->inline(true)->format()->get();
			}
		}

		$sender->data("fields", $fields);
		$sender->renderProfile($this->view("about"));
	}

	/*
	|--------------------------------------------------------------------------
	| Display Profile Fields On Posts
	|--------------------------------------------------------------------------
	*/

	// Get fields from the database and attach them to the posts.
	public function handler_postModel_getPostsAfter($sender, &$posts)
	{
		$postsById = array();
		foreach ($posts as &$post) {
			$postsById[$post["postId"]] = &$post;
			$post["fields"] = array();
		}

		if (!count($postsById)) return;

		$result = ET::SQL()
			->select("p.postId, f.fieldId, f.name, f.hideFromGuests, d.data")
			->from("post p")
			->from("profile_data d", "d.memberId=p.memberId", "left")
			->from("profile_field f", "d.fieldId=f.fieldId", "left")
			->where("p.postId IN (:ids)")
			->where("f.showOnPosts")
			->orderBy("f.position ASC")
			->bind(":ids", array_keys($postsById))
			->exec();

		while ($row = $result->nextRow()) {
			$postsById[$row["postId"]]["fields"][$row["fieldId"]] = array(
				"name" => $row["name"],
				"data" => $row["data"],
				"hideFromGuests" => $row["hideFromGuests"],
			);
		}
	}

	// Insert a post's fields into its template array.
	public function handler_conversationController_formatPostForTemplate($sender, &$formatted, $post, $conversation)
	{
		if ($post["deleteMemberId"]) return;

		foreach ($post["fields"] as $fieldId => $field) {

			// If this field is hidden from guests and we're a guest, don't display it.
			if ($field["hideFromGuests"] and !ET::$session->user) continue;

			// Truncate the field's contents to 30 characters and add it to the post's info array.
			//if (strlen($field["data"]) > 30) $field["data"] = substr($field["data"], 0, 30)."...";
			if (mb_strlen($field["data"],'UTF-8') > 30) $field["data"] = mb_substr($field["data"], 0, 30, 'UTF-8')."...";
			
			// Truncate the field's contents to 30 characters and add it to the post's info array.
			$formatted["info"][] = "<span class='profile-".$fieldId."'>".sanitizeHTML($field["data"])."</span>";

		}
	}

	/*
	|--------------------------------------------------------------------------
	| User Profile Settings
	|--------------------------------------------------------------------------
	*/

	// On the settings/general page, add a field to the form for each of the custom profile fields.
	public function handler_settingsController_initGeneral($sender, $form)
	{
		$model = ET::getInstance("profileFieldModel");
		$fields = $model->getData(ET::$session->userId);
		$plugin = $this; // for use in closures

		$sender->addJSFile("core/js/autocomplete.js");

		foreach ($fields as $field) {
			$key = "profile_".$field["fieldId"];
			$form->addSection($key, $field["name"]);
			if ($field["type"] == "checkboxes") {
				$data = explode("\n", $field["data"]);
				foreach ($data as $checkbox) {
					$form->setValue($key."[".$checkbox."]", true);
				}
			} else {
				$form->setValue($key, $field["data"]);
			}
			$form->addField($key, $key, function($form) use ($plugin, $field)
			{
				return $plugin->field($form, $field);
			}, array($this, "saveField"));
		}
	}

	// When a profile field is saved, save it to the database.
	public function saveField($form, $key, &$preferences)
	{
		$value = $form->getValue($key);

		if (is_array($value)) $value = implode("\n", array_keys($value));

		// Limit the value to 1000 characters.
		$value = mb_substr($value, 0, 1000, "UTF-8");

		$model = ET::getInstance("profileFieldModel");
		$model->setData(ET::$session->userId, substr($key, 8), $value);
	}

	protected function parseOptions($options)
	{
		$lines = explode("\n", $options);
		$options = array();
		foreach ($lines as $line) {
			$line = trim($line);
			$options[$line] = $line;
		}
		return $options;
	}

	// Render a custom profile field in the settings form.
	public function field($form, $field)
	{
		$key = "profile_".$field["fieldId"];
		switch ($field["type"]) {

			case "textarea":
				$input = $form->input($key, "textarea", array("rows" => 3, "style" => "width:500px"));
				break;

			case "select":
				$options = $this->parseOptions($field["options"]);
				$input = $form->select($key, $this->parseOptions($field["options"]));
				break;

			case "radios":
				$options = $this->parseOptions($field["options"]);
				$input = "<div class='checkboxGroup'>";
				foreach ($options as $option) {
					$input .= "<label class='radio'>".$form->radio($key, $option)." ".$option."</label>";
				}
				$input .= "</div>";
				break;

			case "checkboxes":
				$options = $this->parseOptions($field["options"]);
				$input = "<div class='checkboxGroup'>";
				foreach ($options as $option) {
					$input .= "<label class='checkbox'>".$form->checkbox($key."[".$option."]")." ".$option."</label>";
				}
				$input .= "</div>";
				break;

			case "member":
				$input = $form->input($key, "text");
				$input .= '<script>new ETAutoCompletePopup($("input[name='.$key.']"), false, function(member) {
					$("input[name='.$key.']").val(member.name);
				});</script>';
				break;

			default:
				$input = $form->input($key, "text");
		}
		return $input.($field["description"] ? "<br><small>".$field["description"]."</small>" : "");
	}

	/*
	|--------------------------------------------------------------------------
	| Allow searching the member list by Profile fields
	|--------------------------------------------------------------------------
	*/

	// Add gambits for each of the searchable profile fields.
	public function handler_membersController_constructGambitsMenu($controller, &$gambits)
	{
		$model = ET::getInstance("profileFieldModel");
		$fields = $model->get(array("searchable" => 1));

		$gambits["profile"] = array();

		foreach ($fields as $field) {
			addToArrayString($gambits["profile"], mb_strtolower($field["name"], "UTF-8").": ?", array("gambit-profile-".$field["fieldId"]), 1);
		}
	}

	// On the settings/general page, add a field to the form for each of the custom profile fields.
	public function handler_membersController_parseTerms($sender, &$terms, $sql, &$conditions)
	{
		$model = ET::getInstance("profileFieldModel");
		$fields = $model->get(array("searchable" => 1));

		foreach ($terms as $k => $term) {

			$term = strtolower(trim($term));

			foreach ($fields as $field) {
				$prefix = strtolower($field["name"]).":";

				if (strpos($term, $prefix) === 0) {

					// Find all member IDs that have field data matching this term.
					$rows = ET::SQL()
						->select("DISTINCT memberId")
						->from("profile_data")
						->where("fieldId", $field["fieldId"])
						->where("data LIKE :value")
						->bind(":value", "%".trim(substr($term, strlen($prefix)))."%")
						->exec()
						->allRows("memberId");
					$ids = array_keys($rows);

					// Add these members to the results.
					$conditions[] = "m.memberId IN (:field$k)";
					$sql->bind(":field$k", count($ids) ? $ids : 0);

					unset($terms[$k]);
					break;
				}
			}
		}
	}

}
