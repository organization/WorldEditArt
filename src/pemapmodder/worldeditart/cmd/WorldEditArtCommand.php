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
	public static function registerAll(WorldEditArt $main){
		$main->getServer()->getCommandMap()->registerAll("wea", [

		]);
	}

	/** @var WorldEditArt */
	private $main;

	public function __construct(WorldEditArt $main, $name, $desc, $usage, $perm, ...$aliases){
		parent::__construct($name, $desc, $usage, $aliases);
		$this->setPermission($perm);
		$this->main = $main;
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!($sender instanceof Player)){
		}
	}

	/**
	 * @return WorldEditArt
	 */
	public function getPlugin(){
		return $this->main;
	}
}
