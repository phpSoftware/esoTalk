<?php

if (!defined("IN_ESOTALK")) exit;

 // An implementation of the string filter interface for plain text strings
ET::$pluginInfo["AutoLink"] = array(
    "name" => "AutoLink",
    "description" => "When you post an URL, AutoLinksLight automatically embeds videos from Youtube, Dailymotion, TwitchTV, RuTube, SoundCloud etc..",
    "version" => "1.2.1",
    "author" => "esoTalk Team",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL" => "https://github.com/phpSoftware/esoTalk-v2/",
    "license" => "GPLv2"
);

class ETPlugin_AutoLink extends ETPlugin {

	// ACCEPTED PROTOTYPES
	//
	var $accepted_protocols = array(
	  'http://', 'https://', 'ftp://', 'ftps://', 'mailto:', 'telnet://',
	  'news://', 'nntp://', 'nntps://', 'feed://', 'gopher://', 'sftp://' );

	//
	// AUTO-EMBED IMAGE FORMATS
	//
	var $accepted_image_formats = array(
	  'gif', 'jpg', 'jpeg', 'tif', 'tiff', 'bmp', 'png', 'svg', 'ico' );


public function handler_format_format($sender)
{
	// quick check to rule out complete wastes of time
	if( strpos( $sender->content, '://' ) !== false || strpos($sender->content, 'mailto:' ) !== false )
	{
	  $sender->content = preg_replace_callback( '/(?<=^|\r\n|\n| |\t|<br>|<br\/>|<br \/>)!?([a-z]+:(?:\/\/)?)([^ <>"\r\n\?]+)(\?[^ <>"\r\n]+)?/i', array( &$this, 'autoLink' ), $sender->content );
	 }
}

public function autoLink( $link = array())
{
  // $link[0] = the complete URL
  // $link[1] = link prefix, lowercase (e.g., 'http://')
  // $link[2] = URL up to, but not including, the ?
  // $link[3] = URL params, including initial ?

  // sanitise input
  $link[1] = strtolower( $link[1] );
  if( !isset( $link[3] ) ) $link[3] = '';

  // check protocol is allowed
  if( !in_array( $link[1], $this->accepted_protocols ) ) return $link[0];

  // check for forced-linking and strip prefix
  $forcelink = substr( $link[0], 0, 1 ) == '!';
  if( $forcelink ) $link[0] = substr( $link[0], 1 );

  $params = array();
  $matches = array();

  
  if( !$forcelink && ( $link[1] == 'http://' || $link[1] == 'https://' ) )
  {
	$width = 640;
	$height = 380;
	// Webm
	if( strtolower( substr( $link[2], -5 ) ) == '.webm')
	return '<video width="'.$width.'" height="'.$height.'" type="video/webm" controls="controls"><source src="'.$link[0].'" ></source></video>';
	// Mp4
	if( strtolower( substr( $link[2], -4 ) ) == '.mp4')
	return '<video width="'.$width.'" height="'.$height.'" type="video/webm" controls="controls"><source src="'.$link[0].'" ></source></video>';
	// Mp3
	else if( strtolower( substr( $link[2], -4 ) ) == '.mp3' )
	return '<audio controls="controls"><source src="'.$link[0].'"></audio>';
	// images
	elseif( preg_match( '/\.([a-z]{1,5})$/i', $link[2], $matches ) && in_array( strtolower( $matches[1] ), $this->accepted_image_formats ) )
	return '<img class="auto-embedded" src="'.$link[1].$link[2].$link[3].'" alt="-image-" title="'.$link[1].$link[2].$link[3].'" />';
	// youtube
	if( strcasecmp( 'www.youtube.com/watch', $link[2] ) == 0 && $this->params( $params, $link[3], 'v' ) )
	  return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$params['v'].'" frameborder="0" allowfullscreen></iframe>'; 
	 else if( preg_match( '/^(?:www\.)?youtu\.be\/([^\/]+)/i', $link[2], $matches ))
	  return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowfullscreen></iframe>';
	// Youtube Shorts
	else if( preg_match( '/youtube\.com\/shorts\/(\w+\s*\/?)*([0-9]+)*$/i', $link[2], $matches ) )
		return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$matches[1].'?rel=0&amp;playsinline=1&amp;controls=1&amp;showinfo=0&amp;modestbranding=0" frameborder="0" allowfullscreen></iframe>';
	// Vimeo
	else if( preg_match( '/vimeo\.com\/(\w+\s*\/?)*([0-9]+)*$/i', $link[2], $matches ) )
	return '<iframe src="//player.vimeo.com/video/'.$matches[1].'?color=ffffff" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
	// Dailymotion
	else if( preg_match( '/^www\.dailymotion\.com\/(?:[a-z]+\/)?video\/([^\/]+)/i', $link[2], $matches ) )
	  return '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" src="http://www.dailymotion.com/embed/video/'.$matches[1].'"></iframe>';
	// ItemFix.com
	else if( preg_match( '/itemfix\.com\/(\w+\s*\/?)*([0-9]+)*$/i', $link[2], $matches ) )
	  return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.itemfix.com/e/'.$link[0].'" frameborder="0" allowfullscreen></iframe>';
	// Twitch TV
	elseif ( preg_match('/twitch\.tv\/(\w+\s*\/?)*([0-9]+)*$/i',$link[2], $matches))
		return '<iframe src="http://www.twitch.tv/'.$matches[1].'/embed" frameborder="0" scrolling="no" height="'.$height.'" width="'.$width.'"></iframe>';
	// Vine (format like this : https://vine.co/v/ME70KX9A2X7/)
	elseif ( preg_match('/vine\.co\/(\w+\s*\/?)*([0-9]+)*$/i',$link[2], $matches))
		return '<iframe class="vine-embed" src="https://vine.co/v/'.$matches[1].'/embed/simple" width="480" height="480" frameborder="0"></iframe><script async src="//platform.vine.co/static/scripts/embed.js" charset="utf-8"></script>';
	// Metacafe
		else if( preg_match( '/^www\.metacafe\.com\/watch\/([0-9]+)\/([^\/]+)\/?$/', $link[2], $matches ) )
		return '<iframe src="http://www.metacafe.com/embed/'.$matches[1].'" width="'.$width.'" height="'.$height.'" allowFullScreen frameborder=0></iframe>';
	// RuTube
	else if( preg_match( '/rutube\.ru\/video\/(\w+\s*\/?)*([0-9]+)*$/i', $link[2], $matches ) )
		return '<iframe src="//rutube.ru/play/embed/'.$matches[1].'" width="'.$width.'" height="'.$height.'" allowFullScreen frameborder=0></iframe>';
  }


  // default to linkifying
	return '<a href="'.$link[0].'" rel="nofollow external">'.$link[0].'</a>';

}

/*Reads query parameters
params : result array as key => value
string : query string
required : array of required parameters key
@return true if required parameters are present.
*/
function params( &$params, $string, $required )
{
  $string = html_entity_decode($string);
  if( !is_array( $required ) ) $required = array( $required );
  if( substr( $string, 0, 1 ) == '?' ) $string = substr( $string, 1 );
  $params = array();
  $bits = split( '&', $string );
  foreach( $bits as $bit ) {
	$pair = split( '=', $bit, 2 );
	if( in_array( $pair[0], $required ) ) $params[ $pair[0] ] = $pair[1];
  }
  return count( $required ) == count( $params );
}

}
?>
