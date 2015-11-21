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

namespace pemapmodder\worldeditart\libworldedit\space\iterator\cuboid;

use pemapmodder\worldeditart\libworldedit\BlockCollection;
use pemapmodder\worldeditart\libworldedit\space\CuboidSpace;
use pemapmodder\worldeditart\libworldedit\space\iterator\BufferedBlockIterator;
use pocketmine\level\Level;

abstract class CuboidBlockIterator extends BufferedBlockIterator{
	/** @var CuboidSpace */
	protected $space;
	protected $level;
	/** @var BlockCollection */
	protected $coll;

	public function __construct(CuboidSpace $space, BlockCollection $coll){
		$this->space = $space;
		$this->level = $space->getLevel(true);
		if(!($this->level instanceof Level)){
			throw new \RuntimeException("Could not load level");
		}
		$this->coll = $coll;
	}

	public function standardCuboidOffset($offset, &$x, &$y, &$z){
		$lengthX = $this->space->getLengthX();
		$lengthY = $this->space->getLengthY();
		$lengthZ = $this->space->getLengthZ();
		if($offset < $lengthX * $lengthY * $lengthZ){
			$z = $offset % $lengthZ;
			$offset = (int) ($offset / $lengthZ);
			$y = $offset % $lengthY;
			$offset = (int) ($offset / $lengthY);
			$x = $offset;

			$x = $this->space->getMinX() + $x;
			$y = $this->space->getMinY() + $y;
			$z = $this->space->getMinZ() + $z;
			return true;
		}
		return false;
	}
}
