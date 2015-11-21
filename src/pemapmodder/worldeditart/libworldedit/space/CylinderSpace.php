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

use pemapmodder\worldeditart\libworldedit\BlockCollection;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

/**
 * Represents a cylinder-shaped {@link Space}.
 */
class CylinderSpace extends Space{
	/** @var Vector3 */
	private $baseCenter, $topCenter;
	/** @var number */
	private $radius, $radiusSquared;

	/**
	 * String representation of object
	 *
	 * @link  http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize(){
		return serialize([
			$this->baseCenter->x,
			$this->baseCenter->y,
			$this->baseCenter->z,
			$this->topCenter->x,
			$this->topCenter->y,
			$this->topCenter->z,
			$this->radius,
		]);
	}
	/**
	 * Constructs the object
	 *
	 * @link  http://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized <p>
	 *                           The string representation of the object.
	 *                           </p>
	 *
	 * @return void
	 * @since 5.1.0
	 */
	public function unserialize($serialized){
		list($bx, $by, $bz, $tx, $ty, $tz, $r) = unserialize($serialized);
		$this->baseCenter = new Vector3((float) $bx, (float) $by, (float) $bz);
		$this->topCenter = new Vector3((float) $tx, (float) $ty, (float) $tz);
		$this->setRadius((float) $r);
	}
	/**
	 * Checks whether the passed {@link Vector3} is included in this {@link Space}.<br>
	 * Floating point vectors are accepted.<br>
	 * <br>
	 * The contents of this function are based on <a
	 * href="http://mathworld.wolfram.com/Point-LineDistance3-Dimensional.html">Wolfram|MathWorld: Point-Lne Distance
	 * (3-Dimensional)</a>, whereas {@code X0 = $v, X1 = $this->baseCenter, X2 = $this->topCenter}.
	 *
	 * @param Vector3 $v the coordinates to check.
	 *
	 * @return bool whether <code> $v</code> is inside the space.
	 */
	public function isInside(Vector3 $v){
		if(!$this->isValid()){
			return false;
		}
		if($v instanceof Position and $v->getLevel()->getName() !== $this->levelName){
			return false;
		}
		$distSquared =
			$v->subtract($this->baseCenter)->                               // (X0 - X1)
			cross                                                           // x
			($v->subtract($this->topCenter))// (X0 - X2)
			->lengthSquared() /                                         // |---| /
			$this->topCenter->subtract($this->baseCenter)// (X2 - X1)
			->lengthSquared();                                          // |---|
		return $distSquared <= $this->radiusSquared; // |(X0 - X1) x (X0 - X2)| / |X2 - X1|
	}
	/**
	 * @return Vector3
	 */
	public function getBaseCenter(){
		return $this->baseCenter;
	}
	/**
	 * @param Vector3 $baseCenter
	 */
	public function setBaseCenter($baseCenter){
		$this->baseCenter = $baseCenter;
	}
	/**
	 * @return Vector3
	 */
	public function getTopCenter(){
		return $this->topCenter;
	}
	/**
	 * @param Vector3 $topCenter
	 */
	public function setTopCenter($topCenter){
		$this->topCenter = $topCenter;
	}
	/**
	 * @return number
	 */
	public function getRadius(){
		return $this->radius;
	}
	/**
	 * @param number $radius
	 */
	public function setRadius($radius){
		$this->radius = $radius;
		$this->radiusSquared = $radius ** 2;
	}

	public function iteratorAllBlocks(BlockCollection $coll){
		// TODO: Implement iteratorAllBlocks() method.
	}
}
