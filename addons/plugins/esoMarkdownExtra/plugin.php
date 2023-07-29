<?php

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["esoMarkdownExtra"] = array(
    "name"        => "esoMarkdownExtra",
    "description" => "This plugin uses the Markdown Extra library from Michel Fortin to render text.",
    "version"     => "1.0",
    "author"      => "Kassius Iakxos",
    "authorEmail" => "kassius@users.noreply.github.com",
    "authorURL"   => "http://github.com/kassius",
    "license"     => "GPLv2"
);

spl_autoload_register(function($class){
		require preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
});
use \Michelf\MarkdownExtra;

require_once(PATH_CORE."/lib/ETFormat.class.php");

class MDETFormat extends ETFormat
{
	public function format($sticky = false)
	{
		if (C("esoTalk.format.mentions")) $this->mentions();
		if (!$this->inline) $this->quotes();
		$this->closeTags();
		
		return $this;
	}
}

class ETPlugin_esoMarkdownExtra extends ETPlugin
{
	public $content;
	public $MDETFormat;
	public $MDEParser;

	public function init()
	{
		$this->MDETFormat = new MDETFormat;
		$this->MDEParser = new MarkdownExtra;
		$this->MDEParser->fn_id_prefix = mt_rand(0,99999)."-";
	}

	public function handler_format_beforeFormat($sender)
	{
		$this->MDETFormat->content = $sender->get();
	}

	public function handler_format_afterFormat($sender)
	{
		$this->MDETFormat->links(); 
		
		$search = array("\r&gt; ","\n&gt; ");
		$this->MDETFormat->content = str_replace($search, "\n> ", $this->MDETFormat->content);
		$this->MDETFormat->content = $this->MDEParser->transform($this->MDETFormat->content);

		$this->MDETFormat->content = str_replace("\r", "\n", $this->MDETFormat->content);
		while(strstr($this->MDETFormat->content,"\n\n") !== FALSE) { $this->MDETFormat->content = str_replace("\n\n", "", $this->MDETFormat->content); }

		$this->MDETFormat->format();
		$this->MDETFormat->content = str_replace("\\\"", "\"", $this->MDETFormat->content);
		
		
		$this->MDETFormat->content = preg_replace_callback(
		      '#\<code (.*?)\>(.+?)\<\/code\>#s',function($m)
		      {
		        //remove link-member link in code view
		        $code = preg_replace("#\<a href='(.*?)' class='link-member'\>(.*?)</a\>#s","$2",$m[2]);
		        //fixed html format
		        $code = str_replace("&amp;","&",$code);
		        return  "<code ".$m[1].">".$code."</code>";
		    },$this->MDETFormat->content);
    
    
		$sender->content = $this->MDETFormat->content;
	}

	public function handler_conversationController_renderBefore($sender)
	{
		$sender->addCSSFile($this->resource("markdown.css"));
	}

}

?>
