<?php
// Last update date: 2020.11.25
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Change the interface language');

$lang = isset($_GET['lang']) ? trim(preg_replace("/[^a-z]/","", $_GET['lang'])) : 'en';
$referer = isset($_SERVER['HTTP_REFERER']) ? trim(strip_tags($_SERVER['HTTP_REFERER'])) : '?'.$abw.$abp.'=index';

echo '<script>
var d = new Date();
d.setTime(d.getTime() + 31536000);
var expires = "expires="+ d.toUTCString();
document.cookie = "lang_code='.$lang.'; " + expires + "; path=/;";
document.location.href="'.$referer.'";
</script>';
die();
