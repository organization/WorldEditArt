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

namespace pemapmodder\worldeditart\database\listener;

use pemapmodder\worldeditart\database\MysqlDataProvider;
use pemapmodder\worldeditart\database\stream\QueryListener;
use pemapmodder\worldeditart\database\stream\QueryResult;
use pemapmodder\worldeditart\database\Zone;

class FetchZonesListener implements QueryListener{
	public function onResult(QueryResult $result){
		$main = $result->src->getMain();
		/** @var MysqlDataProvider $db */
		$db = $main->getDataProvider();
		$zones = [];
		foreach($result->rows as $row){
			$zones[(int) $row["type"]][(int) $row["id"]] = (new Zone(unserialize($row["space"]), (int) $row["type"]))->setId((int) $row["id"]);
		}
		$db->onZonesLoadedCallback($zones);
	}
}
