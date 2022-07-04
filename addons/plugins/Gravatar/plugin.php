<?php
// Copyright 2013 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["Gravatar"] = array(
	"name" => "Gravatar",
	"description" => "Allows users to choose to use their Gravatar.",
	"version" => ESOTALK_VERSION,
	"author" => "Toby Zerner",
	"authorEmail" => "support@esotalk.org",
	"authorURL" => "https://esotalk.org",
	"license" => "GPLv2",
	"dependencies" => array(
		"esoTalk" => "1.0.0g4"
	)
);

class ETPlugin_Gravatar extends ETPlugin {

	function init()
	{
		// Override the avatar function.

		/**
		 * Return an image tag containing a member's avatar.
		 *
		 * @param array $member An array of the member's details. (email is required in this implementation.)
		 * @param string $avatarFormat The format of the member's avatar (as stored in the database - jpg|gif|png.)
		 * @param string $className CSS class names to apply to the avatar.
		 */
		function avatar($member = array(), $className = "")
		{
			$default = C("plugin.Gravatar.default") ? C("plugin.Gravatar.default") : "mm";

			$protocol = C("esoTalk.https") ? "https" : "http";
			$url = "$protocol://www.gravatar.com/avatar/".md5(strtolower(trim($member["email"])))."?d=".urlencode($default)."&s=64";

			return "<img src='$url' alt='' class='avatar $className'/>";
		}
	}

	// Change the avatar field on the settings page.
	function handler_settingsController_initGeneral($sender, $form)
	{
		$form->removeField("avatar", "avatar");
		$form->addField("avatar", "avatar", array($this, "fieldAvatar"));
	}

	function fieldAvatar($form)
	{
		return sprintf(T("Change your avatar on %s."), "<a href='https://gravatar.com' target='_blank'>Gravatar.com</a>");
	}

	/**
	 * Construct and process the settings form for this skin, and return the path to the view that should be
	 * rendered.
	 *
	 * @param ETController $sender The page controller.
	 * @return string The path to the settings view to render.
	 */
	public function settings($sender)
	{
		// Set up the settings form.
		$form = ETFactory::make("form");
		$form->action = URL("admin/plugins/settings/Gravatar");
		$form->setValue("default", C("plugin.Gravatar.default", "mm"));

		// If the form was submitted...
		if ($form->validPostBack("save")) {

			// Construct an array of config options to write.
			$config = array();
			$config["plugin.Gravatar.default"] = $form->getValue("default");

			if (!$form->errorCount()) {

				// Write the config file.
				ET::writeConfig($config);

				$sender->message(T("message.changesSaved"), "success autoDismiss");
				$sender->redirect(URL("admin/plugins"));

			}
		}

		$sender->data("gravatarSettingsForm", $form);
		return $this->view("settings");
	}

}
