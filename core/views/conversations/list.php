<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Displays the conversation list - a table with each conversation as a row.
 *
 * @package esoTalk
 */
?>
<ul class='list conversationList'>

<?php



// vanGato - TODO: SORT STARTPAGE BY channelId - ADD if isset()
array_multisort(array_map(function($element) {if (isset($element[2]['channelId'])) {return $element[2]['channelId'];}else{return '';}}, $data["results"]), SORT_ASC, $data["results"]);



// Loop through the conversations and output a table row for each one.
foreach ($data["results"] as $conversation):
$this->renderView("conversations/conversation", $data + array("conversation" => $conversation));
endforeach;

?>

<?php if ($data["showViewMoreLink"]): ?>
<li class='viewMore'>
<a href='<?php
$searchWithoutLimit = ET::searchModel()->removeGambit($data["searchString"], 'return strpos($term, strtolower(T("gambit.limit:"))) === 0;');
echo URL("conversations/".sanitizeHTML($data["channelSlug"])."?search=".urlencode($searchWithoutLimit.($searchWithoutLimit ? " + " : "")."#".T("gambit.limit:").($data["limit"] + C("esoTalk.search.limitIncrement")))); ?>'><?php echo T("View more"); ?></a>
</li>
<?php endif; ?>

</ul>
