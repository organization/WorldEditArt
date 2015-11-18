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

namespace pemapmodder\worldeditart\session\queue;

use pocketmine\block\Block;

/**
 * A rheostat refers to a series of actions.<br>
 * A rheostat slides <em>forwards</em> or <em>backwards</em>, where backwards sliding
 * is undoing and forward sliding is redoing.
 */
class Rheostat{
	const DIRECTION_FORWARDS = true;
	const DIRECTION_BACKWARDS = false;

	/**
	 * In descending order, where the 0th record was the last action done,
	 * and the last record was the first action done and is the last action to be undone.
	 *
	 * @var Block[]
	 */
	private $behindRecords = [];
	/**
	 * In ascending order, where the 0th record is the next action to be done,
	 * and the last record is the last action to be done.
	 *
	 * @var Block[]
	 */
	private $forwardRecords = [];

	private $slideDirection = self::DIRECTION_FORWARDS;

	/**
	 * Rheostat constructor.
	 * @param Block[] $forwardRecords
	 */
	public function __construct($forwardRecords){
		$this->forwardRecords = $forwardRecords;
	}

	public function slide(){
		if($this->slideDirection === self::DIRECTION_FORWARDS){
			$this->slideForwards();
		}else{
			$this->slideBackwards();
		}
	}
	private function slideForwards(){
		/** @var Block $next */
		$next = array_shift($this->forwardRecords);
		$original = $next->getLevel()->getBlock($next);
		$next->getLevel()->setBlock($next, $next, false, false);
		array_unshift($this->behindRecords, $original);
	}
	private function slideBackwards(){
		/** @var Block $original */
		$original = array_shift($this->behindRecords);
		$next = $original->getLevel()->getBlock($original);
		$original->getLevel()->setBlock($original, $original, false, false);
		array_unshift($this->forwardRecords, $next);
	}
}
