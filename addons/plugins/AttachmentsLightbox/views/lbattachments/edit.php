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

?>
<div class='attachments-edit'>
	<ul>
		<?php foreach ($data["attachments"] as $attachment): ?>
		<li id='attachment-<?php echo $attachment["attachmentId"]; ?>'>
			<a href='<?php echo URL("attachment/remove/".$attachment["attachmentId"]."?token=".ET::$session->token); ?>' class='control-delete' data-id='<?php echo $attachment["attachmentId"]; ?>'><i class='icon-remove'></i></a>
			<strong><?php echo $attachment["filename"]; ?></strong>
		</li>
		<?php endforeach; ?>
	</ul>

	<a class='attachments-button'><?php echo T("Attach a file"); ?></a>
</div>

<div class='dropZone'><?php echo T("Drop files to upload"); ?></div>