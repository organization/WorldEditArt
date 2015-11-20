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

	/**
	 * Sets the sphere's radius.
	 *
	 * @param number $radius the radius of the sphere to set to.
	 */
	public function setRadius($radius){
		$this->radius = $radius;
		$this->radiusSquared = $radius ** 2;
	}
}
