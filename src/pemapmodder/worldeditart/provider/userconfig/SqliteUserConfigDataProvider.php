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

namespace pemapmodder\worldeditart\provider\userconfig;

use pemapmodder\worldeditart\user\User;
use pemapmodder\worldeditart\WorldEditArt;
use SQLite3;

class SqliteUserConfigDataProvider implements UserConfigDataProvider{
	private $main;
	/** @var SQLite3 */
	private $db;

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$this->db = new SQLite3($main->getDataFolder() . $main->getConfig()->getNested("dataProvider.userConfig.options.sqlite.path", "users.sqlite3"));
		$this->db->exec($main->getResourceContents("users/sqlite_init.sql"));
	}
	public function loadUserConfig(User $user){
		$stmt = $this->db->prepare("SELECT * FROM users WHERE type=:type AND name=:name");
		$stmt->bindValue(":type", $user->getType(), SQLITE3_TEXT);
		$stmt->bindValue(":name", $user->getName(), SQLITE3_TEXT);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);
		$config = new UserConfig($this->main);
		if(is_array($row)){
			$keys = [
				"wandId",
				"wandDamage",
				"jumpId",
				"jumpDamage",
				"safeMode",
				"sudoRequired",
				"defaultSudoSession",
				"maxUndoQueue",
				"tickEditThreshold",
			];
			foreach($keys as $key){
				if(isset($row[$key])){
					$config->setDatum($key, $row[$key]);
				}
			}
		}
		$user->loadUserConfigCallback($config);
	}
	public function saveUserConfig(User $user, UserConfig $config){
		$stmt = $this->db->prepare("INSERT INTO users
			(type,name,wandId,wandDamage,jumpId,jumpDamage,safeMode,sudoRequired,defaultSudoSession,maxUndoQueue,tickEditThreshold)
			VALUES (:type,:name,:wandId,:wandDamage,:jumpId,:jumpDamage,:safeMode,:sudoRequired,:defaultSudoSession,:maxUndoQueue,:tickEditThreshold)");
		$stmt->bindValue(":type", $user->getType(), SQLITE3_INTEGER);
		$stmt->bindValue(":name", $user->getName(), SQLITE3_TEXT);
		$stmt->bindValue(":wandId", $config->getWandId(), SQLITE3_INTEGER);
		$stmt->bindValue(":wandDamage", $config->getWandDamage(), SQLITE3_INTEGER);
		$stmt->bindValue(":jumpId", $config->getJumpId(), SQLITE3_INTEGER);
		$stmt->bindValue(":jumpDamage", $config->getJumpDamage(), SQLITE3_INTEGER);
		$stmt->bindValue(":safeMode", $config->getSafeMode(), SQLITE3_INTEGER);
		$stmt->bindValue(":sudoRequired", $config->getSudoRequired(), SQLITE3_INTEGER);
		$stmt->bindValue(":defaultSudoSession", $config->getDefaultSudoSession(), SQLITE3_INTEGER);
		$stmt->bindValue(":maxUndoQueue", $config->getMaxUndoQueue(), SQLITE3_INTEGER);
		$stmt->bindValue(":tickEditThreshold", $config->getTickEditThreshold(), SQLITE3_INTEGER);
		$stmt->execute();
	}
	public function close(){
		$this->db->close();
	}
}
