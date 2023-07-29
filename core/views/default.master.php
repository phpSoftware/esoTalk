<?php
// Copyright 2011 Toby Zerner, Simon Zerner
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

/**
 * Default master view. Displays a HTML template with a header and footer.
 *
 * @package esoTalk
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>

<meta charset='<?php echo T("charset", "utf-8"); ?>'>
<title><?php echo sanitizeHTML($data["pageTitle"]); ?></title>
<?php echo $data["head"]; ?>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta property="og:title" content="<?php echo sanitizeHTML($data["pageTitle"]); ?>" />
    <meta property="og:description" content="If you are normal, you have got to be MAD!" />
	<meta property="og:url" content="<?php echo URL('',true); ?>" />
	<?php if(file_exists('uploads/logo.png')) { ?>
    <meta property="og:image" content="<?php echo URL('',true); ?>uploads/logo.png" />
  <?php } ?>
  <?php if(file_exists('favicon.png')) { ?>
  
    <link rel="shortcut icon" type="image/png" href="<?php echo URL('',true); ?>favicon.png" />
  <?php } ?> 
  <link rel="manifest" href="/manifest.json">
  <link rel="apple-touch-icon-precomposed" sizes="180x180" href="../fav/apple-touch-icon.png" />
  <link rel="apple-touch-icon-precomposed" sizes="57x57" href="../fav/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../fav/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../fav/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../fav/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="../fav/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="../fav/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="../fav/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="../fav/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="../../fav/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="../fav/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="../fav/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="../fav/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="../fav/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="../fav/mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="../fav/mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="../fav/mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="../fav/mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="../fav/mstile-310x310.png" />
<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
<link rel="shortcut icon" href="/favicon.png" />
<link rel="stylesheet" type="text/css" href="/fancybox/fancybox.css">
<link type="text/css" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500&display=swap" rel="stylesheet">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1828851643911658"
     crossorigin="anonymous"></script>
</head>
<body class='<?php echo $data["bodyClass"]; ?>'>

<script>
//Infinite Scroll
$(function(){ //on document ready
    $(document).scroll(function (e) { //bind scroll event

        var intBottomMargin = 60; //Pixels from bottom when script should trigger

        //if less than intBottomMargin px from bottom
        if ($(window).scrollTop() >= $(document).height() - $(window).height() - intBottomMargin) {
          //  $(".viewMore").click(); //trigger click
        }

    });
});
</script>
<a href='#' class='scrollToTop'><i class='fas fa-chevron-up'></i></a>
 <script>
$(document).ready(function(){

    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollToTop').fadeIn();
        } else {
            $('.scrollToTop').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.scrollToTop').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

});
</script> 

<script src="/fancybox/fancybox.js"></script>
<?php $this->trigger("pageStart"); ?>

<div id='messages'>
<?php foreach ($data["messages"] as $message): ?>
<div class='messageWrapper'>
<div class='message <?php echo $message["className"]; ?>' data-id='<?php echo @$message["id"]; ?>'><?php echo $message["message"]; ?></div>
</div>
<?php endforeach; ?>
</div>

<div id='wrapper'>

<!-- HEADER -->



<div id='hdr'>
<div id='hdr-content'>

<div id='hdr-inner'>

<?php if ($data["backButton"]): ?>

<a href='<?php echo $data["backButton"]["url"]; ?>' id='backButton' title='<?php echo T("Back to {$data["backButton"]["type"]}"); ?>'><i class="icon-chevron-left"></i></a>

<?php endif; ?>

<h1 id='forumTitle'><a href='<?php echo URL(""); ?>'><?php echo $data["forumTitle"]; ?></a></h1>

<ul id='mainMenu' class='menu'>
<?php if (!empty($data["mainMenuItems"])) echo $data["mainMenuItems"]; ?>
</ul>

<ul id='userMenu' class='menu'>
<?php echo $data["userMenuItems"]; ?>
<li><a href='<?php echo URL("conversation/start"); ?>' class='link-newConversation button'><?php echo T("New Conversation"); ?></a></li>

</ul>

</div>
</div>
</div>
<!-- BODY -->
<div id='body'>
<div id='body-content'>
<?php echo $data["content"]; ?>
</div>
</div>

<!-- FOOTER -->
<div id='ftr'>
<div id='ftr-content'>
<ul class='menu'>
<li id='goToTop'><a href='#'><?php echo T("Go to top"); ?></a></li>
<?php echo $data["metaMenuItems"]; ?>
<?php if (!empty($data["statisticsMenuItems"])) echo $data["statisticsMenuItems"]; ?>
</ul>
</div>
</div>
<?php $this->trigger("pageEnd"); ?>

</div>
</body>
</html>
