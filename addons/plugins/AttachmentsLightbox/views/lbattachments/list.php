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
<div class='attachments'>
    <h4><span>Attachments</span></h4>
    <?php $images = array()?>
    <?php $others = array()?>
    <?php
    foreach ($data["attachments"] as $attachment){
        #add to images if it is one
        if (preg_match('/(.*)\.(jpg|gif|png)+/Ui', $attachment["filename"])){
            $images[$attachment["attachmentId"] ] = $attachment["filename"];
        }else{
            $others[$attachment["attachmentId"] ] = $attachment["filename"];
        }
    }?>
    <?php if(count($images)):?>
        <div class="clearfix">
            <?php foreach($images as $id => $filename):?>
                <a class="fancybox" rel="grouping_attachemants"  href='<?php echo URL("attachment/" . $id . "_" . $filename); ?>'>
                    <img width="50" src="<?php echo URL("attachment/" . $id . "_" . $filename); ?>" title="<?php echo $filename ?>"
                         alt="<?php echo $filename ?>" />
                </a>
            <?php endforeach;?>
        </div>
    <?php endif?>
    <?php if (count($others)):?>
        <ul>
            <?php foreach ($others as $id => $filename): ?>
                <li>
                    <a href='<?php echo URL("attachment/" . $id . "_" . $filename); ?>'>
                        <?php echo $filename; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif;?>
</div>