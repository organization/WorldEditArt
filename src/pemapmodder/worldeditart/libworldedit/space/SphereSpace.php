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
use pemapmodder\worldeditart\libworldedit\space\iterator\sphere\SphereAllBlocksIterator;
use pemapmodder\worldeditart\session\WorldEditSession;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

/**
 * Represents a {@link Space} in the shape of a regular sphere.<br>
 * A regular sphere is identified by a {@link Vector3} representing its central point and a number representing its
 * radius.
 */
class SphereSpace extends Space{
	/** @var Vector3 */
	private $center;
	/** @var number */
	private $radius;
	/** @var number */
	private $radiusSquared;

	public function serialize(){
		return serialize([$this->center->x, $this->center->y, $this->center->z, $this->radius, $this->levelName]);
	}
	public function unserialize($serialized){
		list($x, $y, $z, $radius, $this->levelName) = unserialize($serialized);
		$this->center = new Vector3($x, $y, $z);
		$this->setRadius($radius);
	}

	public function isInside(Vector3 $v){
		if(!$this->isValid()){
			return false;
		}
		if($v instanceof Position and $v->getLevel()->getName() !== $this->levelName){
			return false;
		}
		return $v->distanceSquared($this->center) <= $this->radiusSquared;
	}
	public function isValid(){
		return parent::isValid() and isset($this->center, $this->radius);
	}

	/**
	 * Returns the center of the sphere.
	 *
	 * @return Vector3
	 */
	public function getCenter(){
		return $this->center;
	}
	/**
	 * Returns the sphere's radius.
	 *
	 * @return number the radius of the sphere.
	 */
	public function getRadius(){
		return $this->radius;
	}
	public function getRadiusSquared(){
		return $this->radiusSquared;
	}
	/**
	 * Sets the sphere's radius.
	 *
	 * @param number $radius the radius of the sphere to set to.
	 */
	public function setRadius($radius){
		$this->radius = $radius;
		$this->radiusSquared = $radius ** 2;
	}

	public function name(WorldEditSession $session){
		return $session->translate(Lang::SPACE_SPHERE_TO_STRING, [
				"RADIUS" => round($this->radius, 1),
				"X" => round($this->center->x, 1),
				"Y" => round($this->center->y, 1),
				"Z" => round($this->center->z, 1),
		]);
	}

	public function iteratorAllBlocks(BlockCollection $coll){
		return new SphereAllBlocksIterator($this, $coll);
	}
}
