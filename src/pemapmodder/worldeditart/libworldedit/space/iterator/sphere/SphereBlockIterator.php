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

namespace pemapmodder\worldeditart\libworldedit\space\iterator\sphere;

use pemapmodder\worldeditart\libworldedit\BlockCollection;
use pemapmodder\worldeditart\libworldedit\space\iterator\BufferedBlockIterator;
use pemapmodder\worldeditart\libworldedit\space\SphereSpace;
use pocketmine\level\Level;

abstract class SphereBlockIterator extends BufferedBlockIterator{
	/** @var SphereSpace */
	protected $space;
	protected $level;
	/** @var BlockCollection */
	protected $coll;

	public function __construct(SphereSpace $space, BlockCollection $coll){
		parent::__construct();
		$this->space = $space;
		$this->level = $space->getLevel(true);
		if(!($this->level instanceof Level)){
			throw new \RuntimeException("Could not load level");
		}
		$this->coll = $coll;
	}

	public function standardSphereOffset($offset, &$x, &$y, &$z){
		$radius = $this->space->getRadius();
		$diameter = $this->space->getRadius() * 2;
		if($offset < $diameter ** 3){
			$z = $offset % $diameter;
			$offset = (int) ($offset / $diameter);
			$y = $offset % $diameter;
			$offset = (int) ($offset / $diameter);
			$x = $offset;

			$x += $this->space->getCenter()->x - $radius;
			$y += $this->space->getCenter()->y - $radius;
			$z += $this->space->getCenter()->z - $radius;
			return true;
		}
		return false;
	}
}
