<?php
/**
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *   @author Marcel Lange <contact@marcel-lange.info>
 *   @package AttachmentsLightbox
 *
 *   This file is part of esoTalk. Please see the included license file for usage information.
 * */

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["AttachmentsLightbox"] = array(
	"name" => "AttachmentsLightbox",
	"description" => "Adds Fancybox to Attachements",
	"version" => ESOTALK_VERSION,
    "author" => "esoTalk Team",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL" => "https://github.com/phpSoftware/esoTalk-v2/",
	"license" => "GPLv2"
);

class ETPlugin_AttachmentsLightbox extends ETPlugin {

	// Add the attachments/fineuploader JS/CSS to the conversation view.
	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addJSFile($this->resource("http://code.jquery.com/jquery-latest.min.js"));
		$sender->addJSFile($this->resource("lib/jquery.mousewheel-3.0.6.pack.js"));
        $sender->addCSSFile($this->resource("source/jquery.fancybox.css"));

		$sender->addJSFile($this->resource("source/jquery.fancybox.pack.js"));

        $sender->addCSSFile($this->resource("source/helpers/jquery.fancybox-buttons.css"));
        $sender->addJSFile($this->resource("source/helpers/jquery.fancybox-buttons.js"));
        $sender->addJSFile($this->resource("source/helpers/jquery.fancybox-media.js"));

        $sender->addCSSFile($this->resource("source/helpers/jquery.fancybox-thumbs.css"));
        $sender->addJSFile($this->resource("source/helpers/jquery.fancybox-thumbs.js"));

        $sender->addCSSFile($this->resource("style.css"));

        $sender->addToHead('
            <script type="text/javascript">
                $(document).ready(function() {
                    $(".fancybox").fancybox();
                });
            </script>
        ');

    }

    // Hook onto ConversationController::formatPostForTemplate and add the attachment/list view to the bottom of each post.
    public function handler_conversationController_formatPostForTemplate($sender, &$formatted, $post, $conversation)
    {
        // If the post has been deleted or has no attachments, stop!
        if ($post["deleteMemberId"] or empty($post["attachments"])) return;

        $view = $sender->getViewContents("lbattachments/list", array("attachments" => $post["attachments"]));

        // Rewrites or ordering would be nice! That here is really bad
        $pos1 = strpos($formatted["body"], "<div class='attachments'");
        if (!$pos1) $pos1 = strlen($formatted["body"]);

        $pos2 = strpos($formatted["body"], "<p class='likes");
        $formatted["body"] = substr_replace($formatted["body"], $view, $pos1, $pos2 - $pos1);
    }
}
