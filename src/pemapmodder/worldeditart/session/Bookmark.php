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

use pocketmine\math\Vector3;
use pocketmine\Server;

class Bookmark extends Vector3{
	private $levelName;

	/**
	 * Bookmark constructor.
	 *
	 * @param number $x
	 * @param number $y
	 * @param number $z
	 * @param string $levelName
	 */
	public function __construct($x, $y, $z, $levelName){
		parent::__construct($x, $y, $z);
		$this->levelName = $levelName;
	}

	public function getLevelName(){
		return $this->levelName;
	}

	public function getLevel(Server $server){
		return $server->getLevelByName($this->levelName);
	}
}
