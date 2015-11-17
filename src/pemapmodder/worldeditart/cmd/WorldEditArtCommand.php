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
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

class WorldEditArtCommand extends Command implements PluginIdentifiableCommand{
	/** @var WorldEditArt */
	private $main;
	/** @var BaseCmd[] */
	private $cmds = [];

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$this->registerCmds();
		$aliases = [];
		foreach($this->cmds as $cmd){
			foreach($cmd->getNames() as $name){
				$aliases[] = "/" . strtolower($name);
			}
		}
		parent::__construct("/", "WorldEditArt main command.", "Use `//` for detailed help.", $aliases);
		$main->getServer()->getCommandMap()->register("/", $this);
	}
	private function registerCmds(){
		$this->registerCmd(new VersionCmd);
	}

	public function registerCmd(BaseCmd $cmd){
		foreach($cmd->getNames() as $name){
			$this->cmds[strtolower($name)] = $cmd;
		}
	}

	public function execute(CommandSender $sender, $alias, array $args){
		if(!($sender instanceof Player)){
			$sender->sendMessage("Please run this command in-game or through a CCS (run \"ccs\" for help).");
			return true;
		}
		$session = $this->main->getSessionCollection()->getSession($sender);
		if($session === null or !$session->isValid()){
			$sender->sendMessage("Your account is still being loaded. Please wait...");
			return true;
		}
		if($alias{0} !== "/"){
			return false; // how come this could even happen!
		}
		if($alias === "/"){
			$this->displayHelp($sender);
			return true;
		}else{
			$cmdName = substr($alias, 1);
			var_dump($cmdName);
			$cmd = $this->findCommand($cmdName);
			if($cmd === null){
				return false;
			}
			$ret = $cmd->run($session, $args);
			if(is_string($ret)){
				$session->sendMessage($ret);
			}elseif(is_int($ret)){
				// TODO
			}
			return true;
		}
	}

	/**
	 * Returns a {@link BaseCommand} for the given <code>$name</code>, or <code>null</code> if not found.
	 *
	 * @param string $name
	 * @return BaseCmd|null
	 */
	public function findCommand($name){
		return isset($this->cmds[strtolower($name)]) ? $this->cmds[strtolower($name)] : null;
	}

	/**
	 * @return WorldEditArt
	 */
	public function getPlugin(){
		return $this->main;
	}
	private function displayHelp(CommandSender $sender){
		// TODO
	}
}
