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

namespace pemapmodder\worldeditart\database;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Position;

abstract class BaseDataProvider implements DataProvider{
	/** @var Zone[] */
	private $zones = [];
	/** @var WorldEditArt */
	private $main;

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$this->loadZones();
	}
	protected abstract function loadZones();

	public function getAllZones(){
		return $this->zones;
	}

	/**
	 * @param Position $pos
	 * @return \Generator
	 */
	public function getZones(Position $pos){
		foreach($this->zones as $zone){
			if($zone->getSpace()->isInside($pos)){
				yield $zone->getId() => $zone;
			}
		}
	}

	public function addZone(Zone $zone){
		$this->zones[$zone->getId()] = $zone;
		$this->addZoneImpl($zone);
	}
	protected abstract function addZoneImpl(Zone $zone);

	public function removeZone(Zone $zone){
		if(isset($this->zones[$zone->getId()])){
			unset($this->zones[$zone->getId()]);
			$this->removeZoneImpl($zone);
		}
	}
	protected abstract function removeZoneImpl(Zone $zone);

	public function getMain(){
		return $this->main;
	}
}
