<?php
// Last update date: 2020.12.04
// most of the settings that are listed here are recommended settings.
// описание настроек на русском языке: https://antibot.cloud/static/conf_ru.txt

// email for access to the admin panel (if cloud version, write email from antibot.cloud).
$ab_config['email'] = '';

// password for access to the admin panel (if cloud version, write password from antibot.cloud).
$ab_config['pass'] = '';

// salt, change to reset cookie to visitors.
$ab_config['salt'] = 'oxo_VANGATO_oxo';

// for connecting cloud checking - this value must be empty: $ab_config['check_url'] = '';
$ab_config['check_url'] = '/antibot/ab.php';

// timeout when checking before the button appears (in seconds, 3 is optimal).
// if the cloud version is running, then this is the time until the button appears for users who did not go through automatically.
// if the local (free) version is running, then this is the delay time before the redirect and the appearance of the button.
$ab_config['timer'] = 3;

// deny access to visitors with an empty referrer on the checking page.
// 0 - do not deny access, 1 - deny access.
$ab_config['stop_noreferer'] = 0;

// deny access to visitors with an empty HTTP_ACCEPT_LANGUAGE on the checking page.
// 0 - do not deny access, 1 - deny access.
$ab_config['stop_nolang'] = 0;

// disable the ability to access the website at the click of a button (if not passed automatic checking).
// 0 - do not disable the button, 1 - disable the button.
$ab_config['input_button'] = 0;

// enable reCAPTCHA v3 checking (for cloud checking). 0 - disabled, 1 - enabled.
// visitors from China will not pass, google.com they have blocked.
$ab_config['re_check'] = 0;

// enable Hosting checking (for cloud checking). 0 - disabled, 1 - enabled.
// blocking automatic access of users with ip addresses belonging to hosting companies.
$ab_config['ho_check'] = 0;

// if the website runs on https with http/2.0 support.
// 1 - only allow users who support http2.
// 0 - allow all verified cookies (recommended).
$ab_config['http2only'] = 0;

// save the good bots to the white list ip by mask /24 for ipv4 and by mask /64 for ipv6.
// 1 - shortened record (recommended), 0 - full ip.
$ab_config['short_mask'] = 1;

// if the visitor is defined as a fake bot (with a user agent like a good bot):
// 1 - stop script execution (recommended)
// 0 - allow checking as a person.
$ab_config['stop_fake'] = 1; 

// ---------------------------------------------------------------------

// LOGS (1 - enable, 0 - disable).

// log of visitors to the checking page.
$ab_config['antibot_log_tests'] = 1;

// log of visitors who passed the checking page.
$ab_config['antibot_log_users'] = 0;

// fake bot log (with a user agent like a good bot, but with incorrect PTR).
$ab_config['antibot_log_fakes'] = 1;

// ---------------------------------------------------------------------

// statistics counters in memcached. 1 - enable, 0 - disable.
$ab_config['memcached_counter'] = 0;

$ab_config['memcached_host'] = '127.0.0.1';
$ab_config['memcached_port'] = 11211;

// prefix for the data in the memcached (must be unique for each AntiBot software on the server.).
$ab_config['memcached_prefix'] = 'antibot_';

// extended statistics on search bots: yandex, google, mailru, bing. 1 - enable, 0 - disable.
$ab_config['extended_bot_stat'] = 0;

// ---------------------------------------------------------------------

// server response code for users blocked in the rules. available options:
// Only: 200, 400, 403, 404, 410, 451, 500, 502, 503, 504.
// See: https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
$ab_config['header_error_code'] = 200;

// content shown to blocked users:
// 0 - system message depending on the error code.
// 1 - your content from antibot/data/error.txt
$ab_config['custom_error_page'] = 1;

// allow access only to visitors from the specified referrers. checked only on the checkout page.
// 1 - start up only on the white list of referrers.
// 0 - do not check the referrer and let all to the antibot check page.
// with a non-whitelisted referrer, the visitor will see an error page.
$ab_config['check_ref_traf'] = 0;

// search for these words in the referrer host to allow access to the antibot check page.
$ab_config['allow_ref_only'] = array('yandex', 'google', 'bing', 'mail.ru');

// if the visitor fell under any of the blocking rules and received the blocking page,
// then also a cookie named stop is set for 10 days.
// 1 - block these visitors in the future, even if they no longer fall under the blocking rules.
// 0 - do not block.
$ab_config['block_stop_cookie'] = 0;

// ---------------------------------------------------------------------

// List of good bots in the format: signature from User-Agent => array of PTR records:
// if the PTR record is empty or uninformative, then specify array('.');
// then all bots with this user agent will be skipped as good bots,
// but ip will not be added to the base of good bots.
// if the bot comes from a small number of subnets, then you can specify a part of the ip address.

$ab_se['Googlebot'] = array('.googlebot.com'); // GoogleBot (main indexer)
$ab_se['yandex.com'] = array('yandex.ru', 'yandex.net', 'yandex.com'); // All Yandex bots
$ab_se['Mail.RU_Bot'] = array('mail.ru', 'smailru.net'); // All Bots Mail.RU Indexers
$ab_se['bingbot'] = array('search.msn.com'); // Bing.com indexer
//$ab_se['msnbot'] = array('search.msn.com'); // Additional Indexer Bing.com
//$ab_se['Google-Site-Verification'] = array('googlebot.com', 'google.com'); // Check for Google Search Console
//$ab_se['vkShare'] = array('.vk.com', '.vkontakte.ru', '.go.mail.ru', '.userapi.ru'); // vkontakte
//$ab_se['facebookexternalhit'] = array('.fbsv.net', '66.220.149.', '31.13.', '2a03:2880:'); // Facebook
//$ab_se['OdklBot'] = array('.odnoklassniki.ru'); // Однокласники
//$ab_se['MailRuConnect'] = array('.smailru.net'); // Мой мир (mail.ru)
$ab_se['TelegramBot'] = array('149.154.161'); // Telegram
$ab_se['Twitterbot'] = array('.twttr.com', '199.16.15'); // Twitter
//$ab_se['googleweblight'] = array('google.com'); // 
//$ab_se['BingPreview'] = array('search.msn.com'); // Check Bing Mobile Page Adaptation
//$ab_se['uptimerobot'] = array('uptimerobot.com');
//$ab_se['pingdom'] = array('pingdom.com');
//$ab_se['HostTracker'] = array('.'); //
//$ab_se['Yahoo! Slurp'] = array('.yahoo.net'); // Yahoo Bots
//$ab_se['SeznamBot'] = array('.seznam.cz'); // seznam.cz
//$ab_se['Pinterestbot'] = array('.pinterest.com'); // 
//$ab_se['Mediapartners'] = array('googlebot.com', 'google.com'); // AdSense bot
//$ab_se['AdsBot-Google'] = array('google.com'); // Adwords bot
//$ab_se['Google-Adwords'] = array('google.com'); // Adwords bot (Google-Adwords-Instant и Google-AdWords-Express
//$ab_se['Google-Ads'] = array('google.com'); // Adwords bot (Google-Ads-Creatives-Assistant)
//$ab_se['Google Favicon'] = array('google.com');
//$ab_se['FeedFetcher-Google'] = array('google.com'); // google news
//$ab_se['Applebot'] = array('applebot.apple.com'); // see http://www.apple.com/go/applebot

// ---------------------------------------------------------------------

// If the website (php) is behind the proxy (apache for nginx or cloudflare, etc.), 
// specify the subnet ip of the proxy servers and the value of the $_SERVER 
// variable from which to take the real visitor ip. Only ipv4 is supported.

// CloudFlare:
$ab_proxy['173.245.48.0/20'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['103.21.244.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['103.22.200.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['103.31.4.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['141.101.64.0/18'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['108.162.192.0/18'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['190.93.240.0/20'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['188.114.96.0/20'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['197.234.240.0/22'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['198.41.128.0/17'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['162.158.0.0/15'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['104.16.0.0/12'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['172.64.0.0/13'] = 'HTTP_CF_CONNECTING_IP';
$ab_proxy['131.0.72.0/22'] = 'HTTP_CF_CONNECTING_IP';

// ---------------------------------------------------------------------

// Security setting!
// for files: conf.php, counter.txt, tpl.txt, error.txt
// disable file editing in admin panel. 1 - disable editing, 0 - allow editing.
$ab_config['disable_editing'] = 0;
