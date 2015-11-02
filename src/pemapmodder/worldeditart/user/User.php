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

use pemapmodder\worldeditart\provider\userconfig\UserConfig;
use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Location;
use pocketmine\permission\Permissible;

abstract class User implements Permissible{
	/** @var UserConfig */
	private $userConfig;

	/** @var bool */
	private $sudo = false;

	///////////////
	// INTERFACE //
	//  SECTION  //
	///////////////
	/**
	 * Returns the main class
	 * @return WorldEditArt
	 */
	public abstract function getMain();
	/**
	 * Returns a {@link Location} value whose only reference is held by the caller, e.g. a clone of the real object.
	 * @return Location
	 */
	public abstract function getLocation();
	public abstract function getType();
	public abstract function getName();
	public final function getFullName(){
		return $this->getType() . "/" . $this->getName();
	}

	/**
	 * <em>WARNING</em>: Only use this function with user config callback!
	 * @param UserConfig $config
	 */
	public function loadUserConfigCallback(UserConfig $config){
		$this->userConfig = $config;
	}
	public function saveUserConfig(){
		$this->getMain()->getUserConfigDataProvider()->saveUserConfig($this, $this->getUserConfig());
	}
	/**
	 * @return UserConfig
	 */
	public function getUserConfig(){
		return $this->userConfig;
	}

	public function isInitialized(){
		return $this->userConfig instanceof UserConfig;
	}
	/**
	 * @return boolean
	 */
	public function isSudo(){
		return $this->sudo;
	}
	/**
	 * @param boolean $sudo
	 */
	public function setSudo($sudo){
		$this->sudo = $sudo;
	}
}
