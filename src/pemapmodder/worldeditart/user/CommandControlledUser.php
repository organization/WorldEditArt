<?php

/*
 * Small-ZC-Plugins
 *
 * Copyright (C) 2015 PEMapModder and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace pemapmodder\worldeditart\user;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\command\CommandSender;
use pocketmine\level\Location;

class CommandControlledUser extends User{
	const TYPE = "worldeditart.cmdctrl";
	/** @var WorldEditArt */
	private $main;
	/** @var string */
	private $owner;
	/** @var Location */
	private $location;

	public function __construct(WorldEditArt $main, CommandSender $owner, Location $initLoc){
		$this->main = $main;
		$this->owner = $owner->getName();
		$this->location = $initLoc;
	}
	public function getMain(){
		return $this->main;
	}
	public function getLocation(){
		return clone $this->location;
	}
	public function getType(){
		return self::TYPE;
	}
	public function getName(){
		return $this->owner;
	}
}
