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

namespace pemapmodder\worldeditart\libworldedit\space\iterator\cylinder;

use pocketmine\math\Vector3;

class CylinderAllBlocksIterator extends CylinderBlockIterator{
	protected function getBlock($offset){
		if(!$this->standardCylinderOffset($offset, $x, $y, $z)){
			$this->end();
			return null;
		}
		$v3 = new Vector3($x, $y, $z);
		if($this->space->isInside($v3)){
			$b = $this->coll->getRandomBlock();
			$b->x = $x;
			$b->y = $y;
			$b->z = $z;
			$b->level = $this->level;
			return $b;
		}
		return null;
	}
	public function estimatedSize(){
		return $this->space->getRadiusSquared() * M_PI * $this->space->getBaseCenter()->distance($this->space->getTopCenter());
	}
}
