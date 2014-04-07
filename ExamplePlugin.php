<?php

// description

/*
__PocketMine Plugin__
class=AnOldApiPlugin
name=OldApi
author=PEMapModder
version=1.0
apiversion=12
*/

// information

// no namespaces needed

// main plugin class

class AnOldApiPlugin implements Plugin{
	// start
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	// initialize
	public function init(){
		$this->api->addHandler("player.block.touch", array($this, "eventHandler"), 10);
		$this->api->console->register("echo", "echos a command to console", array($this, "commandHandler"));
		$this->api->console->alias("ec", "echo");
		$this->api->console->alias("eh", "echo");
		$this->api->schedule(1200, array($this, "delayedFunction"), array("item 0", "item 1"), false);
		$this->api->schedule(700, array($this, "repeatedFunction"), array("item 2", "item 3"), true);
	}
	// end
	public function __destruct(){
	}
	// handle commands
	public function commandHandler($cmd, $params, $issuer, $alias){
		switch($cmd){
			case "echo":
				console(FORMAT_AQUA."$issuer has used /$cmd.");
				return "You have used /$cmd.";
		}
	}
	// handle events
	public function eventHandler($data, $event){
		switch($event){
			case "player.block.touch":
				if($data["player"]->username !== "PEMapModder")
					return false;
				break;
		}
	}
	// delayed schedule
	public function delayedFunction($data){
		var_dump($data);
	}
	// repeated schedule
	public function repeatedFunction($data){
		var_dump($data);
	}
}

class AnotherRandomClass{
	public function __construct(){
		$this->server = ServerAPI::request();
	}
}

// EOF
