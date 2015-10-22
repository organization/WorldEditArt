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
use pocketmine\level\Location;

abstract class User{
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

	public function saveUserConfig(){
		// TODO implement function
	}
}
