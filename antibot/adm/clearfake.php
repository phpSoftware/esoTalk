<?php
// очистка таблицы фейк ботов
// Last update date: 2020.11.23
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Empty the records');

if (isset($_POST['submit'])) {
$del = $antibot_db->exec("DELETE FROM fake;");
$vacuum = $antibot_db->exec("VACUUM;");
}

echo '<script>document.location.href="?'.$abw.$abp.'=fake";</script>';
