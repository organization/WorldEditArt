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

use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\scheduler\FileWriteTask;

class FileDataProvider extends BaseDataProvider{
	/** @var string */
	private $dir;
	/** @var int */
	private $nextId;

	public function __construct(WorldEditArt $main, $dir){
		parent::__construct($main);
		$this->dir = $dir;
		if(!is_dir($dir)){
			mkdir($dir, 0777, true);
		}
		$this->nextId = (int) file_get_contents($dir . "nextId"); // boolean false will be casted to 0 if file was not created
	}

	protected function loadZones(){
		$dir = $this->dir . "zones/";
		$zones = [];
		/** @var \SplFileInfo $info */
		foreach(new \DirectoryIterator($dir) as $info){
			if($info->getExtension() === "weaz"){
				$data = json_decode(file_get_contents($info));
				$id = $data->Id;
				$space = unserialize($data->Space);
				$type = $data->Type;
				$zone = (new Zone($space, $type))->setId($id);
				$zones[$type][$id] = $zone;
			}
		}
		$this->onZonesLoadedCallback($zones);
	}

	protected function addZoneImpl(Zone $zone){
		$zone->setId($this->nextId());
		$this->getMain()->getServer()->getScheduler()->scheduleAsyncTask(
			new FileWriteTask($this->dir . "zones/" . $zone->getId() . ".weaz", json_encode([
				"Id" => $zone->getId(),
				"Space" => serialize($zone->getSpace()),
				"Type" => $zone->getType(),
			])));
	}

	protected function removeZoneImpl(Zone $zone){
		// TODO: Implement removeZoneImpl() method.
	}

	protected function loadSessionImpl($callbackId){
		// TODO: Implement loadSessionImpl() method.
	}

	/**
	 * Saves data for the given {@link WorldEditSession}.<br>
	 * No feedback action is required after session data have been saved.
	 *
	 * @param WorldEditSession $session
	 */
	public function saveSession(WorldEditSession $session){
		// TODO: Implement saveSession() method.
	}

	private function nextId(){
		$out = $this->nextId++;
		$this->getMain()->getServer()->getScheduler()->scheduleAsyncTask(
			new FileWriteTask($this->dir . "nextId", (string) $this->nextId));
		return $out;
	}
}
