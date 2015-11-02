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

namespace pemapmodder\worldeditart\user;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\command\CommandSender;
use pocketmine\level\Location;
use pocketmine\permission\PermissionAttachment;
use pocketmine\plugin\Plugin;

class CommandControlledUser extends User{
	const TYPE = "worldeditart.cmdctrl";

	/** @var WorldEditArt */
	private $main;
	/** @var CommandSender */
	private $parent; // I really want to use WeakRef for this :(
	/** @var Location */
	private $location;

	public function __construct(WorldEditArt $main, CommandSender $owner, Location $initLoc){
		$this->main = $main;
		$this->parent = $owner;
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
		return $this->parent->getName();
	}

	public function isPermissionSet($name){
		return $this->parent->isPermissionSet($name);
	}
	public function hasPermission($name){
		return $this->parent->hasPermission($name);
	}
	public function addAttachment(Plugin $plugin, $name = null, $value = null){
		return $this->parent->addAttachment($plugin, $name, $value);
	}
	public function removeAttachment(PermissionAttachment $attachment){
		$this->parent->removeAttachment($attachment);
	}
	public function recalculatePermissions(){
		$this->parent->recalculatePermissions();
	}
	public function getEffectivePermissions(){
		return $this->parent->getEffectivePermissions();
	}
	public function isOp(){
		return $this->parent->isOp();
	}
	public function setOp($value){
		$this->parent->setOp($value);
	}
}
