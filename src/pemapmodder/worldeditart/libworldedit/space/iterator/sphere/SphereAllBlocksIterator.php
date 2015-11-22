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

class SphereAllBlocksIterator extends SphereBlockIterator{
	public function estimatedSize(){
		return 4 / 3 * $this->space->getRadius() ** 3 * M_PI;
	}
	protected function getBlock($offset){
		if(!$this->standardSphereOffset($offset, $x, $y, $z)){
			$this->end();
			return null;
		}
		$c = $this->space->getCenter();
		$distSq = ($c->x - $x) ** 2 + ($c->y - $y) ** 2 + ($c->z - $z) ** 2;
		if($distSq < $this->space->getRadiusSquared()){
			$block = $this->coll->getRandomBlock();
			$block->x = $x;
			$block->y = $y;
			$block->z = $z;
			return $block;
		}
		return null;
	}
}
