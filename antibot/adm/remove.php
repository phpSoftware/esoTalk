<?php
// Last update date: 2020.11.23
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Delete rule');

if (isset($_POST['submit']) AND isset($_POST['id'])) {
$_POST['id'] = isset($_POST['id']) ? trim(preg_replace("/[^0-9]/","", $_POST['id'])) : die('id');
$del = $antibot_db->exec("DELETE FROM rules WHERE rowid=".$_POST['id'].";");
}

echo '<script>document.location.href="?'.$abw.$abp.'=rules";</script>';
