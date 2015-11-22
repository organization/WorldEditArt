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

class CuboidMarginBlocksIterator extends CuboidBlockIterator{
	/*
	 * section 0: low Z, all X, all Y
	 * section 1: high Z, all X, all Y
	 * section 2: low X, all Y, all exclusive Z
	 * section 3: high X, all Y, all exclusive Z
	 * section 4: low Y, all exclusive X, all exclusive Z
	 * section 5: high Y, all exclusive X, all exclusive Z
	 */

	protected function getBlock($offset){
		$mx = $this->space->getMinX();
		$my = $this->space->getMinY();
		$mz = $this->space->getMinZ();
		$lx = $this->space->getLengthX();
		$ly = $this->space->getLengthY();
		$lz = $this->space->getLengthZ();
		$xy = $lx * $ly;

		// ZYX
		if($offset < $xy){
			$block = $this->coll->getRandomBlock();
			$block->z = $mz;
			$block->x = $mx + $offset % $lx;
			$offset = (int) ($offset / $lx);
			$block->y = $my + $offset;
			$block->level = $this->level;
			return $block;
		}
		$offset -= $xy;
		if($offset < $xy){
			$block = $this->coll->getRandomBlock();
			$block->z = $mz + $lz - 1;
			$block->x = $mx + $offset % $lx;
			$offset = (int) ($offset / $lx);
			$block->y = $my + $offset;
			$block->level = $this->level;
			return $block;
		}
		$offset -= $xy;

		// XZY
		$yz = $ly * ($lz - 2);
		if($offset < $yz){
			$block = $this->coll->getRandomBlock();
			$block->x = $mx;
			$block->y = $my + $offset % ($ly - 2);
			$offset = (int) ($offset / ($ly - 2));
			$block->z = $mz + $offset;
			$block->level = $this->level;
			return $block;
		}
		$offset -= $yz;
		if($offset < $yz){
			$block = $this->coll->getRandomBlock();
			$block->x = $mx + $lx - 1;
			$block->y = $my + $offset % ($ly - 2);
			$offset = (int) ($offset / ($ly - 2));
			$block->z = $mz + $offset + 1;
			$block->level = $this->level;
			return $block;
		}
		$offset -= $yz;

		// YXZ
		$xz = ($lx - 2) * ($lz - 2);
		if($offset < $xz){
			$block = $this->coll->getRandomBlock();
			$block->y = $my;
			$block->z = $mz + $offset % ($lz - 2);
			$offset = (int) ($offset / ($lz - 2));
			$block->x = $mx + $offset + 1;
			$block->level = $this->level;
			return $block;
		}
		$offset -= $xz;
		if($offset < $xz){
			$block = $this->coll->getRandomBlock();
			$block->y = $my + $ly - 1;
			$block->z = $mz + $offset % ($lz - 2);
			$offset = (int) ($offset / ($lz - 2));
			$block->x = $mx + $offset + 1;
			$block->level = $this->level;
			return $block;
		}

		return null;
	}
}
