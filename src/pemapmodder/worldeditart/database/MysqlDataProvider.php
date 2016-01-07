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

use mysqli;
use pemapmodder\worldeditart\database\listener\FetchZonesListener;
use pemapmodder\worldeditart\database\stream\MysqlStream;
use pemapmodder\worldeditart\database\stream\QueryRequest;
use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\WorldEditArt;

class MysqlDataProvider extends BaseDataProvider{
	/** @var mysqli */
	private $direct;
	/** @var MysqlStream */
	private $stream;

	public function __construct(WorldEditArt $main){
		parent::__construct($main);
	}

	public function init(){
		$conn = $this->getMain()->getConfig()->getNested("dataProvider.userConfig.options.mysql.connection");
		$this->direct = new mysqli($conn["host"], $conn["user"], $conn["password"], $conn["schema"], $conn["port"]);
		$this->stream = new MysqlStream($conn["host"], $conn["user"], $conn["password"], $conn["schema"], $conn["port"]);
		$queries = $this->getMain()->getResourceContents("queries/mysql.sql");
//		foreach(explode(";", $queries) as $query){
//			$this->stream->addQuery(new QueryRequest($this->getMain(), $query));
//		}
		$this->direct->multi_query($queries);
	}

	protected function loadZones(){
		$this->stream->addQuery(new QueryRequest($this->getMain(), "SELECT * FROM wea_zones ORDER BY type", new FetchZonesListener));
	}

	protected function addZoneImpl(Zone $zone){
		$this->stream->addQuery(new QueryRequest($this->getMain(), "INSERT INTO wea_zones (type, space)" .
			"VALUES ({$zone->getType()}, {$this->esc(serialize($zone->getSpace()))})", new InsertZoneListener($this, $zone)));
	}

	protected function removeZoneImpl(Zone $zone){
		$this->stream->addQuery(new QueryRequest($this->getMain(), "DELETE FROM wea_zones WHERE id={$zone->getId()}"));
	}

	protected function loadSessionImpl($callbackId){
		// TODO: Implement loadSessionImpl() method.
	}

	public function saveSession(WorldEditSession $session){
		// TODO: Implement saveSession() method.
	}

	protected function esc($string){
		return "'" . $this->direct->escape_string($string) . "'";
	}
}
