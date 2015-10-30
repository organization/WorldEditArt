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

namespace pemapmodder\worldeditart\provider\zone;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Position;
use SplObjectStorage;

abstract class BaseZoneDataProvider implements ZoneDataProvider{
	/** @var Zone[] */
	private $zones = [];
	/** @var SplObjectStorage */
	private $addingZones;
	/** @var WorldEditArt */
	private $main;

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$this->addingZones = new SplObjectStorage;
		$this->load($main);
	}

	public function getZones(){
		return array_merge($this->zones, $this->addingZones);
	}
	public function getZone(Position $pos){
		foreach($this->getZones() as $zone){
			if($zone->getSpace()->isInside($pos)){
				return $zone;
			}
		}
		return null;
	}
	public function addZone(Zone $zone){
		/** @noinspection PhpIllegalArrayKeyTypeInspection */
		$this->addingZones[$zone] = true;
	}
	public function addZoneCallback(Zone $zone){

	}
	/**
	 * @return WorldEditArt
	 */
	public function getMain(){
		return $this->main;
	}
	protected abstract function load(WorldEditArt $main);
	protected abstract function addZoneImpl(Zone $zone);
}
