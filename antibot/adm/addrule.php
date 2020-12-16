<?php
// Last update date: 2020.11.23
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Add rule');

if (isset($_POST['submit'])) {
$_POST['search'] = isset($_POST['search']) ? trim(preg_replace("/[^0-9a-zA-Z\.\:\-]/","", $_POST['search'])) : die('search');
$_POST['comment'] = isset($_POST['comment']) ? trim(strip_tags($_POST['comment'])) : '';
$_POST['comment'] = $antibot_db->escapeString($_POST['comment']);
$_POST['rule'] = isset($_POST['rule']) ? trim(strip_tags($_POST['rule'])) : '';
if ($_POST['rule'] != 'white' AND $_POST['rule'] != 'black') die('rule');
if ($_POST['rule'] != '') {
$add = @$antibot_db->exec("INSERT INTO rules (search, rule, comment) VALUES ('".$_POST['search']."', '".$_POST['rule']."', '".$_POST['comment']."');");
}
}

echo '<script>document.location.href="?'.$abw.$abp.'=rules";</script>';
