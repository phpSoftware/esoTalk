<?php

if (!defined("IN_ESOTALK")) exit;

 // An implementation of the string filter interface for plain text strings
ET::$pluginInfo["AutoLink"] = array(
    "name"        => "AutoLink",
    "description" => "Post !URL (with the !) & AutoLink embeds MP4 Video, MP3 Audio, Images, Vimeo, Dailymotion ItemFix ex LiveLeak, Twitch.TV, RuTube and SoundCloud",
    "version"     => "1.2.2",
    "author"      => "esoTalk Team",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL"   => "https://github.com/phpSoftware/esoTalk-v2/",
    "license"     => "GPLv2"
);

class ETPlugin_AutoLink extends ETPlugin
{

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
	  // $link[3] = Only URL params, including initial ?

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
	  
		$width = 640;
		$height = 380;


		// MP4 Video
		if ( strtolower( substr( $link[2], -4 ) ) == '.mp4')
			return '<video width="'.$width.'" height="'.$height.'" type="video/webm" controls="controls"><source src="'.$link[0].'" ></source></video>';

		// MP3 Audio
		else if( strtolower( substr( $link[2], -4 ) ) == '.mp3' )
			return '<audio controls="controls"><source src="'.$link[0].'"></audio>';

		// Images (as in accepted_image_formats defined)
		else if( preg_match( '/\.([a-z]{1,5})$/i', $link[2], $matches ) && in_array( strtolower( $matches[1] ), $this->accepted_image_formats ) )
			return '<img class="auto-embedded" src="'.$link[1].$link[2].$link[3].'" alt="Image" title="'.$link[1].$link[2].$link[3].'" />';

		// YouTube.com
		else if( preg_match( '/(?:www\.)?youtube\.com\/watch\?v=([a-z0-9].*)/i', $link[2].$link[3], $matches ) )
			return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowfullscreen></iframe>';
		
		// Youtube Shorts
	        else if( preg_match( '/youtube\.com\/shorts\/(\w+\s*\/?)*([0-9]+)*$/i', $link[2], $matches ) )
		      return '<iframe width="'.$width.'" height="'.$height.'"  src="'.$link[1].'www.youtube.com/embed/'.$matches[1].'?rel=0&amp;playsinline=1&amp;controls=1&amp;showinfo=0&amp;modestbranding=0" frameborder="0" allowfullscreen></iframe>';
		
		else if( preg_match( '/^(?:www\.)?youtu\.be\/([^\/]+)/i', $link[2], $matches ))
		  return '<iframe width="'.$width.'" height="'.$height.'"  src="https://www.youtube-nocookie.com/embed/'.$matches[1].'?rel=0" frameborder="0" allowfullscreen></iframe>';
		
		// Vimeo.com
		else if( preg_match( '/vimeo\.com\/(\w+\s*\/?)*([0-9]+)*$/i', $link[2], $matches ) )
			return '<iframe src="//player.vimeo.com/video/'.$matches[1].'?color=ffffff" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
		
		// Dailymotion.com
		else if( preg_match( '/(?:www\.)?dai\.ly\/([a-z0-9].*)/i', $link[2], $matches ) )
		  return '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" src="https://www.dailymotion.com/embed/video/'.$matches[1].'"></iframe>';
		
		// LiveLeak.com is now ItemFix - https://www.itemfix.com/ll
		else if( preg_match( '/(?:www\.)?itemfix\.com\/v\?t=([a-z0-9].*)/i', $link[2].$link[3], $matches ) )
		  return '<iframe frameborder="0" src="http://www.itemfix.com/e/'.$matches[1].'" width="'.$width.'" height="'.$height.'"></iframe>';
		
		// Twitch TV
		else if ( preg_match('/twitch\.tv\/([^\/]+)/i',$link[2], $matches))
			return '<iframe src="https://player.twitch.tv/?channel='.$matches[1].'&parent=localhost" frameborder="0" scrolling="no" height="'.$height.'" width="'.$width.'"></iframe>';
				
		// RuTube
		else if( preg_match( '/rutube\.ru\/video\/([^\/]+)/i', $link[2], $matches ) )
			return '<iframe frameborder="0" src="//rutube.ru/play/embed/'.$matches[1].'" width="'.$width.'" height="'.$height.'" allowFullScreen></iframe>';

		// SoundCloud
    if ( preg_match('/soundcloud\.com\/(.+)/i',  $link[2], $matches ) )
        return '<iframe frameborder="0" src="https://w.soundcloud.com/player/?color=%237f7f7f&url='.$link[0].'" width="'.$width.'" style="height:100px"></iframe>';

	  // default to linkifying
		return '<a href="'.$link[0].'" rel="nofollow external">'.$link[0].'</a>';

	}

	/**
	 * Reads query parameters
	 * @param $params : result array as key => value
	 * @param $string : query string
	 * @param $required : array of required parameters key
	 * @return true if required parameters are present.
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
