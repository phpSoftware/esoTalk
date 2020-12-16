<?php
// Last update date: 2020.10.17
if(!defined('ANTIBOT')) die('access denied');

$title = abTranslate('Home');

$size = round(filesize(__DIR__.'/../data/sqlite.db') / 1024 / 1024, 2);

$content .= '
<div id="new_version_msg" class="alert alert-success" role="alert" style="display:none">'.abTranslate('A new version of the antibot is now available. In order to upgrade, visit page:').' <a href="?'.$abw.$abp.'=update">'.abTranslate('Update').'</a></div>
<p>Loade average: '.implode(' ', sys_getloadavg()).'</p>
<p>'.abTranslate('Database file size').' /antibot/data/sqlite.db: '.$size.' MB.</p>';
if ($lang_code == 'ru') {
$content .= '<p><a href="https://t.me/AntiBotCloud" target="_blank">@AntiBotCloud</a> - '.abTranslate('telegram chat support in Russian.').'</p>
<p><a href="https://foxi.biz/viewforum.php?id=1" target="_blank">Фокси Форум</a> - русскоязычный форум поддержки.</p>';
} else {
$content .= '<p><a href="https://t.me/AntiBotCloudSupport" target="_blank">@AntiBotCloudSupport</a> - '.abTranslate('telegram chat support in English.').'</p>
';
}

if ($ab_config['check_url'] == 'https://cloud.antibot.cloud/antibot7.php') {
$content .= '<div class="alert alert-success" role="alert">Telegram <a href="https://t.me/MikFoxi" target="_blank">@MikFoxi</a> & email <a href="mailto:admin@mikfoxi.com?subject=AntiBot: '.$host.'" target="_blank">admin@mikfoxi.com</a> - '.abTranslate('support service for the cloud (paid) version.').'</div>';
}

if ($ab_config['check_url'] != 'https://cloud.antibot.cloud/antibot7.php') {
$content .= '
<table class="table table-bordered table-hover table-sm">
  <thead>
    <tr class="table-info">
      <th colspan="3">'.abTranslate('Do you want the antibot to protect your website even better?').' - '.abTranslate('The difference between cloud check and your local').':</th>
    </tr>
    <tr>
      <th scope="col">'.abTranslate('Description').'</th>
      <th scope="col">LOCAL ('.abTranslate('Your').')</th>
      <th scope="col">CLOUD</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>'.abTranslate('Bots Filtering').'</td>
      <td>'.abTranslate('Local (software)').'</td>
      <td>'.abTranslate('Cloud (service)').'</td>
    </tr>
    <tr>
      <td>'.abTranslate('Efficiency of protection').'</td>
      <td>'.abTranslate('about 70%').'</td>
      <td>'.abTranslate('up to 99%').'</td>
    </tr>
    <tr>
      <td>'.abTranslate('Protection against browser bots that support JS, http2 and other technologies').'</td>
      <td>'.abTranslate('Minimum').'</td>
      <td>'.abTranslate('Maximum').'</td>
    </tr>
    <tr>
      <td>'.abTranslate('Blacklist check IP, PTR, Fingerprint, Whois').'</td>
      <td>'.abTranslate('NO').'</td>
      <td>'.abTranslate('YES').'</td>
    </tr>
    <tr>
      <td>'.abTranslate('Check via reCAPTCHA v.3').'</td>
      <td>'.abTranslate('NO').'</td>
      <td>'.abTranslate('YES').'</td>
    </tr>
    <tr>
      <td>'.abTranslate('Blocking hosting (server) IP').'</td>
      <td>'.abTranslate('NO').'</td>
      <td>'.abTranslate('YES').'</td>
    </tr>
    <tr>
      <td>'.abTranslate('Support').'</td>
      <td>'.abTranslate('Only in the Telegram group').'</td>
      <td>'.abTranslate('By Email, private messages in Telegram, in the Telegram group').'</td>
    </tr>
  </tbody>
</table>
<p>'.abTranslate('Cloud service prices:').'<br />
<strong>ONE:</strong> $25 '.abTranslate('per year - 1 domain and any subdomains of it.').'<br />
<strong>UNLIMITED:</strong> $250 '.abTranslate('per year - Without restrictions of domains, subdomains and without bindings to domains.').'<br />
'.abTranslate('+15 days free trial period to test the cloud version after registering on the site:').' <a href="https://antibot.cloud/#login" target="_blank">antibot.cloud</a>.</p>
';
}

// новости:
$content .= '<span id="other_ab_msg"></span>';

$content .= '<script>var current_version = '.$ab_version.';</script>
<script src="https://antibot.cloud/version.php?data='.md5('Antibot:'.$ab_config['email']).'|'.md5($ab_config['check_url']).'|'.$ab_version.'|'.$ab_config['cms'].'" async></script>
';
