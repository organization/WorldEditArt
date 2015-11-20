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

namespace pemapmodder\worldeditart\libworldedit\space;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use Serializable;

/**
 * Parent class of any classes that represent a space in a {@link Level}.<br>
 * To save spaces in databases (such as for purposes like saving UCZs), implementations of {@link Space} must be {@link
 * Serializable serializable}.
 */
abstract class Space implements Serializable{
	/** @var string */
	protected $levelName;

	/** @var WorldEditArt */
	private $main;

	/**
	 * Checks whether the passed {@link Vector3} is included in this {@link Space}.<br>
	 * Floating point vectors should be accepted.
	 *
	 * @param Vector3 $v the coordinates to check.
	 *
	 * @return bool whether <code> $v</code> is inside the space.
	 */
	public abstract function isInside(Vector3 $v);

	/**
	 * Returns the {@link Level} of the space.
	 *
	 * @param bool $load whether to attempt to load the level if unable to find.
	 *
	 * @return Level|null returns the {@link Level} object of the space, or <code>null</code> if not available.
	 */
	public function getLevel($load = false){
		if(!$this->isValid()){
			return null;
		}
		$server = $this->getMain()->getServer();
		if($load and !$server->isLevelLoaded($this->levelName)){
			$server->loadLevel($this->levelName);
		}
		return $server->getLevelByName($this->levelName);
	}

	public function getMain(){
		return $this->main;
	}
	/**
	 * Initializes the object with an instance of the main class.
	 *
	 * @param WorldEditArt $main
	 */
	public function setMain(WorldEditArt $main){
		$this->main = $main;
	}

	public function isValid(){
		return isset($this->main);
	}
}
