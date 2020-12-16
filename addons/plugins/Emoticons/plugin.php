<?php
if (!defined("IN_ESOTALK")) exit;
ET::$pluginInfo["Emoticons"] = array(
	"name" => "Emoticons",
	"description" => "Converts text emoticons to their graphical equivalent.",
	"version" => ESOTALK_VERSION,
    "author" => "esoTalk Team",
    "authorEmail" => "5557720max@gmail.com",
    "authorURL" => "https://github.com/phpSoftware/esoTalk-2020/",
	"license" => "GPLv2"
);
class ETPlugin_Emoticons extends ETPlugin {
	public function handler_renderBefore($sender) {
		$sender->addCSSFile($this->resource("emoticons.css"));
	}
	public function handler_format_format($sender) {
		if ($sender->inline) return;
		$styles = array();
		/*
		:joy: 😂
		:sob: 😭
		<3 :heart: ❤️
		:rofl: 🤣
		:heart_eyes: 😍
		:relieved: 😌
		:fire: 🔥
		:thinking: 🤔
		:tired_face: 😫
		:roll_eyes: 🙄
		*/
		$styles[":)"]         = "background-position:0 0";
		$styles[":-)"]        = "background-position:0 0";
		$styles[":D"]         = "background-position:0 -27px";
		$styles[":-D"]        = "background-position:0 -27px";
		$styles[":P"]         = "background-position:0 -54px";
		$styles[":-P"]        = "background-position:0 -54px";
		$styles["0|"]         = "background-position:0 -81px";
		$styles["O|"]         = "background-position:0 -81px";
		$styles["o|"]         = "background-position:0 -81px";
		$styles["<3"]         = "background-position:0 -108px";
		$styles[":buh"]       = "background-position:0 -135px";
		$styles[":("]         = "background-position:0 -162px";
		$styles[">("]         = "background-position:0 -189px";
		$styles[":finger"]    = "background-position:0 -216px";
		$styles["XX"]         = "background-position:0 -243px";
		$styles["xx"]         = "background-position:0 -243px";
		$styles[":/"]         = "background-position:0 -270px";
		$styles["<0"]         = "background-position:0 -297px";
		$styles["<O"]         = "background-position:0 -297px";
		$styles["<o"]         = "background-position:0 -297px";
		$styles["0|"]         = "background-position:0 -324px";
		$styles["O|"]         = "background-position:0 -324px";
		$styles["o|"]         = "background-position:0 -324px";
		$styles["8)"]         = "background-position:0 -351px";
		$styles[":adolf"]     = "background-position:0 -378px";
		$styles[";("]         = "background-position:0 -405px";
		$styles[";-("]        = "background-position:0 -405px";
		$styles[":'("]        = "background-position:0 -405px";
		$styles[":'-("]       = "background-position:0 -405px";
		$styles[":katze"]     = "background-position:0 -432px";
		$styles["X|"]         = "background-position:0 -459px";
		$styles["x|"]         = "background-position:0 -459px";
		$styles[":bart"]      = "background-position:0 -486px";
		/* YELLOW and BLUE
		$styles[":)"]      = "background-position:0 0";
		$styles["=)"]      = "background-position:0 0";
		$styles[":D"]      = "background-position:0 -20px";
		$styles["=D"]      = "background-position:0 -20px";
		$styles["^_^"]     = "background-position:0 -40px";
		$styles["^^"]      = "background-position:0 -40px";
		$styles[":("]      = "background-position:0 -60px";
		$styles["=("]      = "background-position:0 -60px";
		$styles["-_-"]     = "background-position:0 -80px";
		$styles[";)"]      = "background-position:0 -100px";
		$styles["^_-"]     = "background-position:0 -100px";
		$styles["~_-"]     = "background-position:0 -100px";
		$styles["-_^"]     = "background-position:0 -100px";
		$styles["-_~"]     = "background-position:0 -100px";
		$styles["^_^;"]    = "background-position:0 -120px; width:18px";
		$styles["^^;"]     = "background-position:0 -120px; width:18px";
		$styles[">_<"]     = "background-position:0 -140px";
		$styles[":/"]      = "background-position:0 -160px";
		$styles["=/"]      = "background-position:0 -160px";
		$styles[":\\"]     = "background-position:0 -160px";
		$styles["=\\"]     = "background-position:0 -160px";
		$styles[":x"]      = "background-position:0 -180px";
		$styles["=x"]      = "background-position:0 -180px";
		$styles[":|"]      = "background-position:0 -180px";
		$styles["=|"]      = "background-position:0 -180px";
		$styles["'_'"]     = "background-position:0 -180px";
		$styles["<_<"]     = "background-position:0 -200px";
		$styles[">_>"]     = "background-position:0 -220px";
		$styles["x_x"]     = "background-position:0 -240px";
		$styles["o_O"]     = "background-position:0 -260px";
		$styles["O_o"]     = "background-position:0 -260px";
		$styles["o_0"]     = "background-position:0 -260px";
		$styles["0_o"]     = "background-position:0 -260px";
		$styles[";_;"]     = "background-position:0 -280px";
		$styles[":'("]     = "background-position:0 -280px";
		$styles[":O"]      = "background-position:0 -300px";
		$styles["=O"]      = "background-position:0 -300px";
		$styles[":o"]      = "background-position:0 -300px";
		$styles["=o"]      = "background-position:0 -300px";
		$styles[":P"]      = "background-position:0 -320px";
		$styles["=P"]      = "background-position:0 -320px";
		$styles[";P"]      = "background-position:0 -320px";
		$styles[":["]      = "background-position:0 -340px";
		$styles["=["]      = "background-position:0 -340px";
		$styles[":3"]      = "background-position:0 -360px";
		$styles["=3"]      = "background-position:0 -360px";
		$styles["._.;"]    = "background-position:0 -380px; width:18px";
		$styles["<(^.^)>"] = "background-position:0 -400px; width:19px";
		$styles["(>'.')>"] = "background-position:0 -400px; width:19px";
		$styles["(>^.^)>"] = "background-position:0 -400px; width:19px";
		$styles["-_-;"]    = "background-position:0 -420px; width:18px";
		$styles["(o^_^o)"] = "background-position:0 -440px";
		$styles["(^_^)/"]  = "background-position:0 -460px; width:19px";
		$styles[">:("]     = "background-position:0 -480px";
		$styles[">:["]     = "background-position:0 -480px";
		$styles["._."]     = "background-position:0 -500px";
		$styles["T_T"]     = "background-position:0 -520px";
		// $styles["XD"]   = "background-position:0 -540px";
		$styles["('<"]     = "background-position:0 -560px";
		// $styles["B)"]   = "background-position:0 -580px";
		// $styles["XP"]   = "background-position:0 -600px";
		$styles[":S"]      = "background-position:0 -620px";
		$styles["=S"]      = "background-position:0 -620px";
		$styles[">:)"]     = "background-position:0 -640px";
		$styles[">:D"]     = "background-position:0 -640px";
		*/
		$from = $to = array();
		foreach ($styles as $k => $v) {
			$quoted = preg_quote(sanitizeHTML($k), "/");
			$from[] = "/(?<=^|[\s.,!<>]){$quoted}(?=[\s.,!<>)]|$)/i";
			$to[] = "<span class='emoticon' style='$v'>$k</span>";
		}
		$sender->content = preg_replace($from, $to, $sender->content);
	}
}
