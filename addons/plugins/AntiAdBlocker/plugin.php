<?php

// Copyright 2020 vanGato
// This file is part of esoTalk. Please see the included license file for usage information.

if (!defined("IN_ESOTALK")) exit;

ET::$pluginInfo["AntiAdBlocker"] = array(
	"name"          => "AntiAdBlocker",
	"description"   => "Allows to hide Content if AdBlocker is active.",
	"version"       => ESOTALK_VERSION,
    "author"      => "vanGato",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL"   => "https://github.com/phpSoftware/esoTalk-2020/",
	"license"       => "GPLv2",
	"dependencies"  => array(
		"esoTalk"     => "1.0.0g4"
	)
);

class ETPlugin_AntiAdBlocker extends ETPlugin {

public function handler_init()
{
	#if (ET::$session->isAdmin()) return;
	#ET::$controller->addCSSFile($this->resource("aab.css"), true);
}

public function handler_pageStart($sender)
{
  echo '<link rel="stylesheet" href="' . C("esoTalk.baseURL") . '/addons/plugins/AntiAdBlocker/resources/aab.css">';
  echo '<link rel="stylesheet" href="' . C("esoTalk.baseURL") . '/addons/plugins/AntiAdBlocker/resources/StickyAd.css">';
  echo '<div class="disabledcss">' .
  	T("AntiAdBlocker", 
  		"
  		Please&nbsp;disable&nbsp;your&nbsp;ad&nbsp;blocker!&nbsp;&mdash;                                 <!-- ENGLISH  -->
  		Bitte&nbsp;deaktiviere&nbsp;Deinen&nbsp;Werbeblocker!&nbsp;&mdash;                               <!-- DEUTSCH  -->
  		Si&nbsp;prega&nbsp;di&nbsp;disattivare&nbsp;il&nbsp;blocco&nbsp;degli&nbsp;annunci!&nbsp;&mdash; <!-- ITALIANO -->
  		Veuillez&nbsp;désactiver&nbsp;votre&nbsp;bloqueur&nbsp;de&nbsp;publicité!&nbsp;&mdash;           <!-- FRANÇAIS -->
  		Por&nbsp;favor,&nbsp;desactive&nbsp;el&nbsp;bloqueador&nbsp;de&nbsp;anuncios!&nbsp;&mdash;       <!-- ESPAÑOL  -->
  		Пожалуйста,&nbsp;деактивируйте&nbsp;Ваш&nbsp;рекламный&nbsp;блокиратор!                          <!-- RUSSKIY  -->
  		"
  	) . '</div><div class="disabledcssBG">';
}

public function handler_pageEnd($sender)
{
  echo "</div>";
}

}