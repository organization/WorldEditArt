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

use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\libworldedit\BlockCollection;
use pemapmodder\worldeditart\libworldedit\space\iterator\cuboid\CuboidAllBlocksIterator;
use pemapmodder\worldeditart\session\WorldEditSession;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

/**
 * Represents a space enclosed by two diagonal points of a cuboid.
 */
class CuboidSpace extends Space{
	/** @var int */
	public $x1, $x2, $y1, $y2, $z1, $z2;

	public function serialize(){
		return "$this->x1:$this->x2:$this->y1:$this->y2:$this->z1:$this->z2:$this->levelName";
	}
	public function unserialize($serialized){
		list($x1, $x2, $y1, $y2, $z1, $z2, $levelName) = unserialize($serialized);
		$this->x1 = (int) $x1;
		$this->y1 = (int) $y1;
		$this->z1 = (int) $z1;
		$this->x2 = (int) $x2;
		$this->y2 = (int) $y2;
		$this->z2 = (int) $z2;
		$this->levelName = $levelName;
	}

	public function isInside(Vector3 $v){
		if(!$this->isValid()){
			return false;
		}
		return
			min($this->x1, $this->x2) <= $v->x and
			min($this->y1, $this->y2) <= $v->y and
			min($this->z1, $this->z2) <= $v->z and
			max($this->x1, $this->x2) >= $v->x and
			max($this->y1, $this->y2) >= $v->y and
			max($this->z1, $this->z2) >= $v->z and
			!($v instanceof Position) or $v->getLevel()->getName() === $this->levelName;
	}

	/**
	 * Returns <code>true</code> if all constraints of this {@link CuboidSpace} are initialized, <code>false</code>
	 * otherwise.
	 *
	 * @return bool whether the {@link CuboidSpace} is valid.
	 */
	public function isValid(){
		return parent::isValid() and isset($this->x1, $this->x2, $this->y1, $this->y2, $this->z1, $this->z2, $this->levelName);
	}

	public function name(WorldEditSession $session){
		if(!$this->isValid()){
			return null;
		}
		return $session->translate(Lang::SPACE_CUBOID_TO_STRING, [
				"LENGTH_X" => round($this->getLengthX(), 1),
				"LENGTH_Y" => round($this->getLengthY(), 1),
				"LENGTH_Z" => round($this->getLengthZ(), 1),
				"TOTAL_SIZE" => ceil($this->getLengthX() * $this->getLengthY() * $this->getLengthZ()),
				"X_1" => round($this->x1, 1),
				"Y_1" => round($this->y1, 1),
				"Z_1" => round($this->z1, 1),
				"X_2" => round($this->x2, 1),
				"Y_2" => round($this->y2, 1),
				"Z_2" => round($this->z2, 1),
		]);
	}

	public function getMinX(){
		return min($this->x1, $this->x2);
	}
	public function getMinY(){
		return min($this->y1, $this->y2);
	}
	public function getMinZ(){
		return min($this->z1, $this->z2);
	}
	public function getMaxX(){
		return max($this->x1, $this->x2);
	}
	public function getMaxY(){
		return max($this->y1, $this->y2);
	}
	public function getMaxZ(){
		return max($this->z1, $this->z2);
	}

	public function getLengthX(){
		return abs($this->x1 - $this->x2) + 1;
	}
	public function getLengthY(){
		return abs($this->y1 - $this->y2) + 1;
	}
	public function getLengthZ(){
		return abs($this->z1 - $this->z2) + 1;
	}

	public function iteratorAllBlocks(BlockCollection $coll){
		return new CuboidAllBlocksIterator($this, $coll);
	}
}
