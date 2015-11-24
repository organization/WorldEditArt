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
use pemapmodder\worldeditart\libworldedit\space\iterator\cylinder\CylinderAllBlocksIterator;
use pemapmodder\worldeditart\session\WorldEditSession;
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

	private $cachedHeight;
	private $cachedPaddingX;
	private $cachedPaddingY;
	private $cachedPaddingZ;
	private $cachedAngleYZ;
	private $cachedAngleZX;
	private $cachedAngleXY;

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
			($v->subtract($this->topCenter))->                              // (X0 - X2)
			lengthSquared() /                                               // |---| /
			$this->topCenter->subtract($this->baseCenter)->                 // (X2 - X1)
			lengthSquared();                                                // |---|
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
		unset(
				$this->cachedPaddingX,
				$this->cachedPaddingY,
				$this->cachedPaddingZ,
				$this->cachedHeight,
				$this->cachedAngleYZ,
				$this->cachedAngleZX,
				$this->cachedAngleXY
		);
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
		unset(
				$this->cachedPaddingX,
				$this->cachedPaddingY,
				$this->cachedPaddingZ,
				$this->cachedHeight,
				$this->cachedAngleYZ,
				$this->cachedAngleZX,
				$this->cachedAngleXY
		);
	}
	/**
	 * @return number
	 */
	public function getRadius(){
		return $this->radius;
	}
	/**
	 * @return number
	 */
	public function getRadiusSquared(){
		return $this->radiusSquared;
	}
	/**
	 * @param number $radius
	 */
	public function setRadius($radius){
		$this->radius = $radius;
		$this->radiusSquared = $radius ** 2;
		unset(
				$this->cachedPaddingX,
				$this->cachedPaddingY,
				$this->cachedPaddingZ
		);
	}

	// calculated cached values
	public function getHeight(){
		if(isset($this->cachedHeight)){
			return $this->cachedHeight;
		}
		return $this->cachedHeight = $this->baseCenter->distance($this->topCenter);
	}
	public function getAngleAgainstYZ(){
		if(isset($this->cachedAngleYZ)){
			return $this->cachedAngleYZ;
		}
		$y1 = min($this->baseCenter->y, $this->topCenter->y);
		$y2 = max($this->baseCenter->y, $this->topCenter->y);
		$z1 = min($this->baseCenter->z, $this->topCenter->z);
		$z2 = max($this->baseCenter->z, $this->topCenter->z);
		return $this->cachedAngleYZ = acos(sqrt(($y2 - $y1) ** 2 + ($z2 - $z1) ** 2) / $this->getHeight());
	}
	public function getAngleAgainstZX(){
		if(isset($this->cachedAngleZX)){
			return $this->cachedAngleZX;
		}
		$z1 = min($this->baseCenter->z, $this->topCenter->z);
		$z2 = max($this->baseCenter->z, $this->topCenter->z);
		$x1 = min($this->baseCenter->x, $this->topCenter->x);
		$x2 = max($this->baseCenter->x, $this->topCenter->x);
		return $this->cachedAngleZX = acos(sqrt(($z2 - $z1) ** 2 + ($x2 - $x1) ** 2) / $this->getHeight());
	}
	public function getAngleAgainstXY(){
		if(isset($this->cachedAngleXY)){
			return $this->cachedAngleXY;
		}
		$x1 = min($this->baseCenter->x, $this->topCenter->x);
		$x2 = max($this->baseCenter->x, $this->topCenter->x);
		$y1 = min($this->baseCenter->y, $this->topCenter->y);
		$y2 = max($this->baseCenter->y, $this->topCenter->y);
		return $this->cachedAngleZX = acos(sqrt(($x2 - $x1) ** 2 + ($y2 - $y1) ** 2) / $this->getHeight());
	}
	public function getPaddingX(){
		if(isset($this->cachedPaddingX)){
			return $this->cachedPaddingX;
		}
		return $this->cachedPaddingX = $this->radius * cos($this->getAngleAgainstYZ());
	}
	public function getPaddingY(){
		if(isset($this->cachedPaddingY)){
			return $this->cachedPaddingY;
		}
		return $this->cachedPaddingY = $this->radius * cos($this->getAngleAgainstZX());
	}
	public function getPaddingZ(){
		if(isset($this->cachedPaddingZ)){
			return $this->cachedPaddingZ;
		}
		return $this->cachedPaddingZ = $this->radius * cos($this->getAngleAgainstXY());
	}
	public function getMinX(){
		return min($this->baseCenter->x, $this->topCenter->x) - $this->getPaddingX();
	}
	public function getMinY(){
		return min($this->baseCenter->y, $this->topCenter->y) - $this->getPaddingY();
	}
	public function getMinZ(){
		return min($this->baseCenter->z, $this->topCenter->z) - $this->getPaddingZ();
	}
	public function getMaxX(){
		return max($this->baseCenter->x, $this->topCenter->x) - $this->getPaddingX();
	}
	public function getMaxY(){
		return max($this->baseCenter->y, $this->topCenter->y) - $this->getPaddingY();
	}
	public function getMaxZ(){
		return max($this->baseCenter->z, $this->topCenter->z) - $this->getPaddingZ();
	}
	public function getLengthX(){
		return abs($this->baseCenter->x - $this->topCenter->x) + $this->getPaddingX() * 2;
	}
	public function getLengthY(){
		return abs($this->baseCenter->y - $this->topCenter->y) + $this->getPaddingY() * 2;
	}
	public function getLengthZ(){
		return abs($this->baseCenter->z - $this->topCenter->z) + $this->getPaddingZ() * 2;
	}

	public function name(WorldEditSession $session){
		if(!$this->isValid()){
			return null;
		}
		return $session->translate(Lang::SPACE_CYLINDER_TO_STRING, [
				"X_BASE" => $this->baseCenter->x,
				"Y_BASE" => $this->baseCenter->y,
				"Z_BASE" => $this->baseCenter->z,
				"X_TOP" => $this->topCenter->x,
				"Y_TOP" => $this->topCenter->y,
				"Z_TOP" => $this->topCenter->z,
				"RADIUS" => $this->radius,
		]);
	}

	public function iteratorAllBlocks(BlockCollection $coll){
		return new CylinderAllBlocksIterator($this, $coll);
	}
}
