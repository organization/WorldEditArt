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

namespace pemapmodder\worldeditart\session;

use pocketmine\command\CommandSender;
use pocketmine\level\Location;

class CommandControlledSession extends WorldEditSession{
	const TYPE = "worldeditart.cmdctrl";

	/** @var CommandSender */
	private $owner;
	/** @var Location */
	private $location;

	public function __construct(CommandSender $owner){
		$this->owner = $owner;
	}

	public function getType(){
		return self::TYPE;
	}

	public function getName(){
		return $this->owner->getName();
	}

	public function getLocation(){
		return $this->location;
	}

	public function hasPermission($permission){
		return $this->owner->hasPermission($permission);
	}
}
