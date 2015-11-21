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

class CuboidAllBlocksIterator extends CuboidBlockIterator{
	protected function getBlock($offset){
		if(!$this->standardCuboidOffset($offset, $x, $y, $z)){
			$this->end();
			return null;
		}
		$block = $this->coll->getRandomBlock();
		$block->x = $x;
		$block->y = $y;
		$block->z = $z;
		$block->level = $this->level;
		return $block;
	}
}
