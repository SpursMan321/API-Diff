<?php

// description

namespace pemapmodder\exampleplugin; // A warning will be generated if no namespace is used
// No description required. Do it in plugin.yml

// no namespaces needed
// namespaces are used
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\event\Event;
use pocketmine\event\EventExecutor;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\PluginTask;

// main plugin class

class AnNewApiPlugin extends PluginBase implements Listener, CommandExecutor{
	// not necessary that you extend PluginBase, but it is more convenient. however it is necessary that you implement the Plugin interface. (PluginBase is subclass of it)
	// Listener interface is empty. You can leet anything implement it.
	// interface CommandExecutor is onCommand(CommandSender, Command, mixed, array);
	
	// start
	public function onLoad(){
		// $this->server is already done by PluginBase on initialization
	}
	// initialize
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvent("BlockBreakEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "eventHandler")), $this, false);
		$this->getServer()->getPluginManager()->registerEvent("BlockPlaceEvent", $this, EventPriority::HIGH, new MyEventExecutor(array($this, "eventHandler")), $this, false);
		$command = new PluginCommand("echo", $this);
		$command->setDescription("echos a command to console");
		$command->setAliases(array("ec", "eh")); // just some examples...
		$command->register($this->getServer()->getCommandMap());
		// You could have done this with plugin.yml
		$this->getServer()->getScheduler()->scheduleDelayedTask(
				new MyCallbackPluginTask(array($this, "delayedFunction"), array("item 0", "item 1"), $this), 1200);
		$this->getServer()->getScheduler()->scheduleRepeatedTask(
				new MyCallbackPluginTask(array($this, "repeatedFunction"), array("item 2", "item 3"), $this), 700);
	}
	// end
	public function onDisable(){
	}
	// handle commands
	public function onCommand(CommandSender $issuer, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "echo":
				console(TextFormat::AQUA.$issuer->getName()." has used /".$cmd->getName().".");
				$issuer->sendMessage("You have used /"$cmd->getName().".");
		}
	}
	// handle events
	public function eventHandler(Event $event){
		switch(get_class($event)){
			case "BlockBreakEvent":
			case "BlockPlaceEvent":
				if($event->getPlayer()->getName() !== "PEMapModder") // just an example, ok?
					$event->setCancelled(false);
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
		$this->server = Server::getInstance();
	}
}

// convenient callback plugin task class

class MyCallbackPluginTask extends PluginTask{
	public function __construct(callable $callback, $data = array(), Plugin $p, $callWithDataAsArray = false){
		parent::__construct($p);
		$this->cb = $callback;
		$this->data = $data;
		$this->cwdaa = $callWithDataAsArray;
	}
	public function onRun($tick){
		if($this->cwdaa)
			call_user_func_array($this->cb, $data);
		else call_user_func($this->cb, $data, $tick);
	}
}

// convenient callback event executor class

class MyEventExecutor implements EventExecutor{
	public function __construct(callable $callback){
		$this->cb = $callback;
	}
	public function execute(Listener $l, Event $event){ // we don't use the Listener class if we use this
		call_user_func($this->cb, $event);
	}
}

// EOF
