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

use pemapmodder\worldeditart\libworldedit\space\Space;
use pemapmodder\worldeditart\session\Bookmark;
use pemapmodder\worldeditart\session\UserConfiguration;
use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Position;

abstract class BaseDataProvider implements DataProvider{
	/** @var UserConfiguration */
	private $userConfigs = [];

	/** @var Zone[] */
	private $zones = [];
	/** @var WorldEditArt */
	private $main;

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$this->loadZones();
	}
	protected abstract function loadZones();

	/**
	 * Returns all zones loaded into zone cache.
	 *
	 * @return Zone[]
	 */
	public function getAllZones(){
		return $this->zones;
	}

	/**
	 * @param Position $pos
	 *
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

	public function loadSession(WorldEditSession $session){
		if(isset($this->userConfigs[$session->getUniqueName()])){
			$session->init($this->userConfigs[$session->getUniqueName()]);
		}
		$id = $this->main->getObjectPool()->store($session);
		$this->loadSessionImpl($id);
	}
	protected abstract function loadSessionImpl($callbackId);

	/**
	 * @param UserConfiguration $config
	 * @param Space[]           $selections
	 * @param Bookmark[]        $bookmarks
	 * @param int               $callbackId
	 */
	protected function onLoadedSession(UserConfiguration $config, $selections, $bookmarks, $callbackId){
		$session = $this->main->getObjectPool()->get($callbackId);
		if($session instanceof WorldEditSession and !$session->isClosed()){
			$session->init($config);
			$session->setSelections($selections);
			$session->setBookmarks($bookmarks);
		}
	}
}
