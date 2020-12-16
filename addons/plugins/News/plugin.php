<?php
// Copyright 2014 Aleksandr Tsiolko

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["News"] = array(
	"name" => "News",
	"description" => "Allows add forum news to esotalk.",
	"version" => "1.0.0",
  "author" => "esoTalk Team",
  "authorEmail" => "5557720max@gmail.com",
  "authorURL" => "https://github.com/phpSoftware/esoTalk-2020/",
	"license" => "MIT"
);

class ETPlugin_News extends ETPlugin {

	public function setup($oldVersion = "")
	{
		$structure = ET::$database->structure();
		$structure->table("new")
			->column("newId", "int(11) unsigned", false)
			->column("title", "varchar(31)", false)
			->column("content", "text")
			->column("hideFromGuests", "tinyint(1)", 0)
			->column("position", "int(11)", 0)
			->column("startTime", "int(11)", false)
			->key("newId", "primary")
			->exec(false);

		$this->createDefaultNews();
		return true;
	}
	
	protected function createDefaultNews()
	{
		$time = time();
		$model = ET::getInstance("newsModel");
		$model->setData(array(
			//"newId"        => 1,
			"title"          => "First new",
			"content"        => "We are open.",
			"hideFromGuests" => 0,
			"position"       => 1,
			"startTime"      =>$time
		));
		
		return true;
	}

	public function __construct($rootDirectory)
	{
		parent::__construct($rootDirectory);		
		ETFactory::register("newsModel", "NewsModel", dirname(__FILE__)."/NewsModel.class.php");		
		ETFactory::registerAdminController("news", "NewsAdminController", dirname(__FILE__)."/NewsAdminController.class.php");
	}


	public function handler_initAdmin($sender, $menu)
	{
		$menu->add("news", "<a href='".URL("admin/news")."'><i class='icon-pencil'></i> ".T("News")."</a>");
	}
	
	public function handler_init($sender) 
	{
		$model = ET::getInstance("newsModel");
		$news = $model->get();
		//date_default_timezone_set('Asia/Bangkok');
		//echo gmdate("l, F jS, Y \- h:i:s A", mktime(7));
		if($news){			
			$string = '';
			foreach($news as $new){
				if (ET::$session->userId) {
					//$string.= mktime(gmdate("H")+7,gmdate("i")+0,gmdate("s"),gmdate("m"),gmdate("d"),gmdate("Y")); 
					$string.='<p><strong>'.addslashes($new['title']).'</strong> '.addslashes($new['content']).' <small>'.date("d.m.Y", $new['startTime']).'</small></p>';
					//$string.= '<p>'.gmdate("d-m-Y "."H:i", $new['startTime']).'</p>';
				} 
				elseif($new['hideFromGuests']==0){
					$string.='<p><strong>'.addslashes($new['title']).'</strong> '.addslashes($new['content']).' <small>'.date("d.m.Y", $new['startTime']).'</small></p>';
				}

			}
			
			if(!empty($string)){
				ET::$controller->addCSSFile($this->resource("news.css"), true);
				$js = '<script>$(document).ready(function(){ if ($("div.triangle-border.top").length == 0) { $("<div class=\"triangle-border top\"'.$string.'</div>").insertBefore("form#search");} });</script>';
				$sender->addToHead($js);
			}			
		}
		return true;		
	}
	
	public function disable()
	{
		return true;
	}
	
	public function uninstall()
	{
		$structure = ET::$database->structure();
		$structure->table("new")
			->drop();
		return true;
	}
}
