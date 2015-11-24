<?php

/*
 * WorldEditArt
 *
 * Copyright (C) 2015 PEMapModder
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace pemapmodder\worldeditart\cmd;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\Utils;

class StartConfigurationCommand extends Command implements PluginIdentifiableCommand{
	private static $executions = 0;

	private $main;

	public function __construct(WorldEditArt $main){
		if(Utils::getOS() !== "win"){
			throw new \RuntimeException("//wea-config is only for Windows.");
		}
		$this->main = $main;
		parent::__construct("/wea-config", "Start WorldEditArt installer(will stop the server)", "/wea-config");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!($sender instanceof ConsoleCommandSender)){
			$sender->sendMessage("Please run this command on console.");
			return false;
		}
		$token = strtoupper(substr(sha1(BOOTUP_RANDOM . ":" . $sender->getServer()->getServerUniqueId() . ":" . self::$executions), 6, 6));
		if(!isset($args[0]) or $args[0] !== $token){
			$sender->sendMessage("This command will stop the server and start an installer window.");
			$sender->sendMessage("Type `/wea-config $token` then enter, to start the installer.");
			return false;
		}
		foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player){
			$player->kick("Server stop", false);
		}
		$cmd = "start " . escapeshellarg(PHP_BINARY) . " " . \Phar::running(false);
		exec($cmd);
		$this->getPlugin()->getServer()->shutdown();
		return true;
	}

	public function getPlugin(){
		return $this->main;
	}

	public function testPermissionSilent(CommandSender $target){
		return $target instanceof ConsoleCommandSender;
	}
}
