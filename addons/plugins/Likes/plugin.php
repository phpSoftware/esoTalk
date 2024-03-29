<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["Likes"] = array(
	"name" => "Likes",
	"description" => "Allows members to like posts.",
	"version" => ESOTALK_VERSION,
    "author" => "esoTalk Team",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL" => "https://github.com/phpSoftware/esoTalk-v2/",
	"license" => "GPLv2",
	"dependencies" => array(
		"esoTalk" => "1.0.0g4"
	)
);

class ETPlugin_Likes extends ETPlugin {

// Like a post.
public function action_conversationController_like($sender, $postId = false)
{
	$sender->responseType = RESPONSE_TYPE_JSON;
	if (!$sender->validateToken() or !ET::$session->userId or ET::$session->isSuspended()) return;

	// Get the conversation.
	if (!($conversation = ET::conversationModel()->getByPostId($postId))) return false;

	// Get the post.
	$post = ET::postModel()->getById($postId);

	ET::SQL()->insert("like")
		->set("postId", $post["postId"])
		->set("memberId", ET::$session->userId)
		->setOnDuplicateKey("memberId", ET::$session->userId)
		->exec();

	$post["likes"][ET::$session->userId] = array("avatarFormat" => ET::$session->user["avatarFormat"], "username" => ET::$session->user["username"]);

	$sender->json("names", $this->getNames($post["likes"]));
	$sender->render();
}

// Unlike a post.
public function action_conversationController_unlike($sender, $postId = false)
{
	$sender->responseType = RESPONSE_TYPE_JSON;
	if (!$sender->validateToken() or !ET::$session->userId) return;

	// Get the conversation.
	if (!($conversation = ET::conversationModel()->getByPostId($postId))) return false;

	// Get the post.
	$post = ET::postModel()->getById($postId);

	ET::SQL()->delete()
		->from("like")
		->where("postId=:postId")->bind(":postId", $post["postId"])
		->where("memberId=:memberId")->bind(":memberId", ET::$session->userId)
		->exec();

	unset($post["likes"][ET::$session->userId]);

	$sender->json("names", $this->getNames($post["likes"]));
	$sender->render();
}

// Show a list of members who liked a post.
public function action_conversationController_liked($sender, $postId = false)
{
	if (!($postId = (int)$postId)) return;
	$post = ET::postModel()->getById($postId);
	if (!$post) return;

	$sender->data("members", $post["likes"]);

	$sender->render($this->view("liked"));
}

// Get the likes from the database and attach them to the posts.
public function handler_postModel_getPostsAfter($sender, &$posts)
{
	$postsById = array();
	foreach ($posts as &$post) {
		$postsById[$post["postId"]] = &$post;
		$post["likes"] = array();
	}

	if (!count($postsById)) return;

	$result = ET::SQL()
		->select("postId, m.memberId, m.email, username, avatarFormat")
		->from("like l")
		->from("member m", "m.memberId=l.memberId", "left")
		->where("postId IN (:ids)")
		->bind(":ids", array_keys($postsById))
		->exec();

	while ($row = $result->nextRow()) {
		$postsById[$row["postId"]]["likes"][$row["memberId"]] = array("memberId" => $row["memberId"], "username" => $row["username"], "email" => $row["email"], "avatarFormat" => $row["avatarFormat"]);
	}
}

public function handler_conversationController_renderBefore($sender)
{
	$sender->addJSLanguage("Like", "Unlike");
	$sender->addJSFile($this->resource("likes.js"));
	$sender->addCSSFile($this->resource("likes.css"));
}

public function handler_conversationController_formatPostForTemplate($sender, &$formatted, $post, $conversation)
{
	if ($post["deleteMemberId"]) return;

	$liked = array_key_exists(ET::$session->userId, $post["likes"]);

	$members = $this->getNames($post["likes"]);
	if ( ! ET::$session->userId and ! $members) return;

	$likeText = isset(ET::$session->userId) ? T($liked ? "Unlike" : "Like") : "";

	$likes = "<p class='likes".($liked ? " liked" : "")."'>
		<a href='#' class='like-button'>$likeText</a>
		<span class='like-separator'".( (! ET::$session->userId or ! $members) ? " style='display:none'" : "").">&nbsp;&middot;&nbsp;</span>
		<span class='like-members'>$members</span>
	</p>";

	$formatted["footer"][] = $likes;
}

public function getNames($likes)
{
	$names = array();
	foreach ($likes as $id => $member) $names[] = memberLink($id, $member["username"]);

	// If there's more than one name, construct the list so that it has the word "and" in it.
	if (count($names) > 1) {

		// If there're more than 3 names, chop off everything after the first 3 and replace them with a
		// "x others" link.
		if (count($names) > 3) {
			$otherNames = array_splice($names, 3);
			$lastName = "<a href='#' class='showMore name'>".sprintf(T("%s others"), count($otherNames))."</a>";
		} else {
			$lastName = array_pop($names);
		}

		$members = sprintf(T("%s like this."), sprintf(T("%s and %s"), implode(", ", $names), $lastName));
	}

	// If there's only one name, we don't need to do anything gramatically fancy.
	elseif (count($names)) {
		$members = sprintf(T("%s likes this."), $names[0]);
	}
	else {
		$members = "";
	}

	return $members;
}

public function setup($oldVersion = "")
{
	$structure = ET::$database->structure();
	$structure->table("like")
		->column("postId", "int unsigned", false)
		->column("memberId", "int unsigned", false)
		->key(array("postId", "memberId"), "primary")
		->exec(false);

	return true;
}

}
