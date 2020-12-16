<?php
// Last update date: 2020.11.22
if(!defined('ANTIBOT')) die('access denied');

clearstatcache(); // Clears file status cache

if (isset($_POST['submit']) AND isset($_POST['upd'])) {
$actual = @json_decode(file_get_contents('https://github.com/MikFoxi/AntiBot/raw/master/tpl.json'), true);
$archive = @file_get_contents('https://github.com/MikFoxi/AntiBot/raw/master/tpl.txt');
if ($actual['md5'] == md5($archive)) {
file_put_contents(__DIR__.'/../data/tpl.txt', $archive, LOCK_EX);
$content .= '<div class="alert alert-success" role="alert">
'.abTranslate('The update was successful.').'
</div>';
} else {
$content .= '<div class="alert alert-danger" role="alert">
'.abTranslate('Update failed.').'
</div>';
}
} else {
echo '<script>document.location.href="?'.$abw.$abp.'=update";</script>';
die();
} 

$title = abTranslate('Update');
