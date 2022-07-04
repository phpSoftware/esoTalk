<?php
// Made on August 1, 2015 by yathish

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays the settings form for the Reputation plugin.
 *
 * @package esoTalk
 */

$form = $data["reputationSettingsForm"];
?>
<?php echo $form->open(); ?>

<div class='section'>

<!-- A warning to enter only numeric values
-->
<h4><strong>Enter only numeric values!</strong></h4>

<ul class='form'>

<li>
<!-- Show checkbox for admin to enable/disable showing reputation points to members
-->
<label>Enable RP</label>
<?php echo $form->checkbox("showReputationPublic"); ?>
<small><?php echo T("Show Reputation Points to members."); ?></small>
</li>
<li>
<!-- Show textarea for admin to set reputation points for starting a conversation
-->
<label>Start Conversation</label>
<?php echo $form->input("conversationStartRP", "text"); ?>
<small><?php echo T("RP to member for starting a conversation."); ?></small>
</li>
<li>
<!-- Show textarea for admin to set reputation points for posting a reply on a conversation
-->
<label>Post Reply</label>
<?php echo $form->input("replyRP", "text"); ?>
<small><?php echo T("RP to be given to member for posting a reply on a conversation."); ?></small>
</li>
<li>
<!-- Show textarea for admin to set reputation points for getting a reply on a started conversation
-->
<label>Get reply on Conversation</label>
<?php echo $form->input("getReplyRP", "text"); ?>
<small><?php echo T("RP to be given to conversation start member for getting a post."); ?></small>
</li>
<li>
<!-- Show textarea for admin to set reputation points for getting a new view on conversation
-->
<label>Views RP</label>
<?php echo $form->input("viewsRP", "text"); ?>
<small><?php echo T("RP to be given to member for getting a view on a conversation."); ?></small>
</li>
<li>
<!-- Show textarea for admin to set reputation points for getting likes on a post (dependancy on likes plugin)
-->
<label>Likes RP</label>
<?php echo $form->input("likesRP", "text"); ?>
<small><?php echo T("RP to be given to member for getting a like on a post."); ?></small>
</li>
<li>
<!-- Show checkbox for admin to update RP based on new formula. Show checkbox only if both Likes and Views are installed.
-->
<label>Update RP</label>
<?php if(in_array("Likes",C("esoTalk.enabledPlugins")) && in_array("Views", C("esoTalk.enabledPlugins"))) {
echo $form->checkbox("newReputationUpdate");
echo "<small>".T("Update Reputation Points of all members based on the new values. Use with caution.")."</small>";}
else echo "<small>".T("<strong> You must have Likes and Views Plugins installed and enabled to use this option!</strong>")."</small>"; ?>
</li>
</ul>

</div>

<div class='buttons'>
<?php echo $form->saveButton("reputationSave"); ?>
</div>

<?php echo $form->close(); ?>
