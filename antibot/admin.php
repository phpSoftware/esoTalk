<?php
// Last update date: 2020.11.25
// admin panel
header('X-Powered-CMS: AntiBot.Cloud (See: https://antibot.cloud/)');
header('X-Robots-Tag: noindex');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
define('ANTIBOT', true);
define('ANTIBOT_ADMIN', true);

$start_time = microtime(true);
$ab_config['cms'] = 'antibot';
$abp = 'abp';
$abp_get = array(); // добавочные гет переменные
$abw = '';
foreach ($abp_get as $k => $v) {
$abw .= $k.'='.$v.'&'; // подставлять в урл
}

require_once(__DIR__.'/code/include.php');

$ab_webdir = dirname($ab_config['uri']); // веб путь до папки антибота (без закрывающего слэша)

// установка урла проверки при инсталяции антибота:
if ($ab_config['check_url'] == '{CHECK_URL}') {
$fileconf = file_get_contents(__DIR__.'/data/conf.php');
$fileconf = str_ireplace('{CHECK_URL}', $ab_webdir.'/ab.php', $fileconf);
file_put_contents(__DIR__.'/data/conf.php', $fileconf, LOCK_EX);
}

// если выключен нормально настроенный мемкешед, включаем обратно:
if ($ab_config['memcached_counter'] == 0) {
if (extension_loaded('memcached')) {
$m = new Memcached();
$m->addServer($ab_config['memcached_host'], $ab_config['memcached_port']);
$m->set('memcached_test', 1);
if ($m->get('memcached_test') == 1) {
$fileconf = file_get_contents(__DIR__.'/data/conf.php');
$fileconf = str_ireplace('$ab_config[\'memcached_counter\'] = 0;', '$ab_config[\'memcached_counter\'] = 1;', $fileconf);
file_put_contents(__DIR__.'/data/conf.php', $fileconf, LOCK_EX);
}
}
}

$host = isset($_SERVER['HTTP_HOST']) ? preg_replace("/[^0-9a-z-.:]/","", $_SERVER['HTTP_HOST']) : '';
$lang_code = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr(mb_strtolower(trim(preg_replace("/[^a-zA-Z]/","",$_SERVER['HTTP_ACCEPT_LANGUAGE'])), 'UTF-8'), 0, 2, 'utf-8') : 'en'; // 2 первых символа
$lang_code = isset($_COOKIE['lang_code']) ? mb_substr(mb_strtolower(trim(preg_replace("/[^a-zA-Z]/","",$_COOKIE['lang_code'])), 'UTF-8'), 0, 2, 'utf-8') : $lang_code;

// перевод на язык посетителя:
if (file_exists(__DIR__.'/lang/adm/'.$lang_code.'.php')) {
require_once(__DIR__.'/lang/adm/'.$lang_code.'.php');
}

if ($ab_config['email'] == '' OR $ab_config['pass'] == '') die('EMAIL or PASS not set in '.__DIR__.'/data/conf.php');

// пост запрос авторизации (установки cookie):
if (isset($_POST['auth_post'])) {
$auth_user = isset($_POST['auth_user']) ? trim($_POST['auth_user']) : ''; // email
$auth_pass = isset($_POST['auth_pass']) ? trim($_POST['auth_pass']) : ''; // pass
$token = md5($auth_user.$ab_config['accept_lang'].$ab_config['useragent'].$ab_config['ip'].$auth_pass.$ab_config['host'].$ab_config['salt']); // токен, основанный на post данных
setcookie('auth_admin_token', $token, $ab_config['time']+86400, '/');
} else {
$token = isset($_COOKIE['auth_admin_token']) ? trim($_COOKIE['auth_admin_token']) : ''; // token из cookie
}

// проверка авторизации:
if ($token != md5($ab_config['email'].$ab_config['accept_lang'].$ab_config['useragent'].$ab_config['ip'].$ab_config['pass'].$ab_config['host'].$ab_config['salt'])) {
require_once(__DIR__.'/code/loginform.php');
die();
}

$content = '';
// страница админки
$page = isset($_GET[$abp]) ? preg_replace("/[^0-9a-z]/","",trim($_GET[$abp])) : 'index';
if (!file_exists(__DIR__.'/adm/'.$page.'.php')) {$page = 'index';}
require_once(__DIR__.'/adm/'.$page.'.php');

echo '<!DOCTYPE html>
<html lang="'.abTranslate('en').'">
<head>
<title>'.$title.' - '.$host.'</title>
<meta charset="utf-8">
<meta name="referrer" content="unsafe-url" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="static/bootstrap.min.css">
<style>
body {overflow-y: scroll;}
.pngflag {height: 16px; border: 1px solid #C0C0C0;}
</style>
</head>
<body class="bg-light">
<main role="main" class="container">
<nav class="my-3 navbar navbar-dark bg-dark rounded shadow-sm">
  <a class="navbar-brand" href="/">'.$host.' <sup><small>'.$ab_version.'</small></sup></a>
<span class="navbar-text"> 
<a href="?'.$abw.$abp.'=lang&lang=ru&rand='.$start_time.'" title="на Русском"><img src="'.$ab_webdir.'/flags/RU.png" class="pngflag" /></a> 
<a href="?'.$abw.$abp.'=lang&lang=en&rand='.$start_time.'" title="in English"><img src="'.$ab_webdir.'/flags/US.png" class="pngflag" /></a> 
<a href="?'.$abw.$abp.'=lang&lang=pl&rand='.$start_time.'" title="po Polsku"><img src="'.$ab_webdir.'/flags/PL.png" class="pngflag" /></a>
</span>
</nav>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=index" '.(($page == 'index') ? 'class="text-secondary"' : '').'>'.abTranslate('Home').'</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=counters" '.(($page == 'counters') ? 'class="text-secondary"' : '').'>'.abTranslate('Statistics').'</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=rules" '.(($page == 'rules') ? 'class="text-secondary"' : '').'>'.abTranslate('Rules').'</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=hits" '.(($page == 'hits') ? 'class="text-secondary"' : '').'>'.abTranslate('Query Log').'</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=fake" '.(($page == 'fake') ? 'class="text-secondary"' : '').'>'.abTranslate('Fake bots').'</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=conf" '.(($page == 'conf') ? 'class="text-secondary"' : '').'>conf.php</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=counter" '.(($page == 'counter') ? 'class="text-secondary"' : '').'>counter.txt</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=tpl" '.(($page == 'tpl') ? 'class="text-secondary"' : '').'>tpl.txt</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=error" '.(($page == 'error') ? 'class="text-secondary"' : '').'>error.txt</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=update" '.(($page == 'update') ? 'class="text-secondary"' : '').'>'.abTranslate('Update').'</a></li>
<li class="breadcrumb-item"><a href="?'.$abw.$abp.'=exit&rand='.$start_time.'">'.abTranslate('Log out').'</a></li>
</ol>
</nav>';
if ($ab_config['check_url'] != 'https://cloud.antibot.cloud/antibot7.php') {
echo '
<span id="tablePrint"></span>
<div class="alert alert-danger" role="alert" id="hiddentext" style="display:none;">'.abTranslate('Don\'t want to see ads? - Connect the cloud version of the antibot.').'</div>
<script>
function toggle_show(id) {
	document.getElementById(id).style.display = document.getElementById(id).style.display == "none" ? "block" : "none";
}
</script>';
}
echo '<div class="my-3 p-3 bg-white rounded shadow-sm">
';
echo $content;
$exec_time = microtime(true) - $start_time;
$exec_time = round($exec_time, 5);
echo '</div></main>
<br />
<footer class="container border-top text-center text-muted">
        <div class="row">
          <div class="col-12">
<small>
<a href="https://antibot.cloud/" target="_blank">Powered by AntiBot Cloud</a> | Time: '.$exec_time.' Sec.</small>
</div>
</div>
      </footer>
<br />';
if ($ab_config['check_url'] != 'https://cloud.antibot.cloud/antibot7.php') {
echo '<script>
function xrender(data) {
var  myTable = \'<div class="alert alert-secondary" role="alert"><span class="close" style="cursor:pointer;" onClick="toggle_show(\\\'hiddentext\\\')" title="Close">&times;</span><ul>\';
for (var i = 0; i < data.length; i++) {
myTable+=\'<li><a href="\' + data[i].url + \'" title="\' + data[i].descr + \'" target="_blank">\' + data[i].title + \'</a></li>\';
}
myTable+=\'</ul></div>\';
document.getElementById(\'tablePrint\').innerHTML = myTable;
}
</script>
<script src="https://w2w.click/content.php?id=1571829962&offset=0&limit=3&render=xrender" async></script>';
}
echo '</body>
</html>';
