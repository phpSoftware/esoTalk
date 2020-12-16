<?php
// Last update date: 2020.11.25
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Log out');

//setcookie('auth_admin_token', 'null', $ab_config['time']-100);

echo '<script>
var d = new Date();
d.setTime(d.getTime() + 30);
var expires = "expires="+ d.toUTCString();
document.cookie = "auth_admin_token=0; " + expires + "; path=/;";
document.location.href="?'.$abw.$abp.'=index";
</script>';
die();
