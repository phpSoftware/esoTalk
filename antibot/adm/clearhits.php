<?php
// очистка таблицы хитов
// Last update date: 2020.11.23
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Empty the records');

if (isset($_POST['submit'])) {
$del = $antibot_db->exec("DELETE FROM hits;");
$vacuum = $antibot_db->exec("VACUUM;");
}

echo '<script>document.location.href="?'.$abw.$abp.'=hits";</script>';
