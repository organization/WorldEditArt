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

use pemapmodder\worldeditart\database\Zone;
use pemapmodder\worldeditart\libworldedit\space\iterator\BufferedBlockIterator;
use pocketmine\block\Block;

/**
 * A rheostat refers to a series of actions.<br>
 * A rheostat slides <em>forwards</em> or <em>backwards</em>, where backwards sliding
 * is undoing and forward sliding is redoing.
 */
class Rheostat{
	const DIRECTION_FORWARDS = true;
	const DIRECTION_BACKWARDS = false;

	/** @var Queue */
	private $queue;
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
	/** @var BufferedBlockIterator */
	private $blockStream;
	/** @var int */
	private $estimatedSize;

	/** @var int */
	private $blocksOutOfBounds = 0;

	/** @var bool */
	private $slideDirection = self::DIRECTION_FORWARDS;

	/** @var string */
	private $name;

	/**
	 * Rheostat constructor.
	 *
	 * @param Queue                 $queue
	 * @param BufferedBlockIterator $blocks
	 * @param string                $name
	 */
	public function __construct(Queue $queue, BufferedBlockIterator $blocks, $name){
		$this->queue = $queue;
		$this->blockStream = $blocks;
		$this->name = $name;
	}

	public function slide(){
		return $this->slideDirection === self::DIRECTION_FORWARDS ? $this->slideForwards() : $this->slideBackwards();
	}
	private function slideForwards(){
		/** @var Block $next */
		if(count($this->forwardRecords) === 0){
			$this->blockStream->next();
			if(!$this->blockStream->valid()){
				return false;
			}
			$next = $this->blockStream->current();
			if($this->queue->getOwner()->getConfig()->safeModeOn){
				$zones = $this->queue->getOwner()->getMain()->getDataProvider()->getZones($next);
				foreach($zones as $zone){
					if($zone->getType() === Zone::TYPE_UNDER_CONSTRUCTION){
						$ok = true;
						break;
					}
				}
				if(!isset($ok)){
					$this->blocksOutOfBounds++;
					return true;
				}
			}
		}else{
			$next = array_shift($this->forwardRecords);
		}
		$original = $next->getLevel()->getBlock($next);
		$next->getLevel()->setBlock($next, $next, false, false);
		array_unshift($this->behindRecords, $original);
		return true;
	}
	private function slideBackwards(){
		if(count($this->behindRecords) === 0){
			return false;
		}
		/** @var Block $original */
		$original = array_shift($this->behindRecords);
		$next = $original->getLevel()->getBlock($original);
		$original->getLevel()->setBlock($original, $original, false, false);
		array_unshift($this->forwardRecords, $next);
		return true;
	}

	public function name(){
		return $this->name;
	}

	public function total(){
		if(isset($this->estimatedSize)){
			return $this->estimatedSize;
		}
		return $this->estimatedSize = $this->blockStream->estimatedSize();
	}
	public function done(){
		return count($this->behindRecords);
	}
	public function left(){
		return $this->total() - $this->done();
	}
	/**
	 * @return int
	 */
	public function getBlocksOutOfBounds(){
		return $this->blocksOutOfBounds;
	}

	public function completed(){
		return $this->slideDirection === self::DIRECTION_FORWARDS and !$this->blockStream->valid();
	}

	public function undone(){
		return $this->slideDirection === self::DIRECTION_BACKWARDS and count($this->behindRecords) === 0;
	}

	public function getSlideDirection(){
		return $this->slideDirection;
	}

	public function setSlideDirection($slideDirection){
		$this->slideDirection = $slideDirection;
	}
}
