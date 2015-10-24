<?php

namespace pemapmodder\worldeditart;

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

use pemapmodder\worldeditart\provider\userconfig\UserConfigDataProvider;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;

class WorldEditArt extends PluginBase{
	/** @var UserConfigDataProvider */
	private $userConfigDataProvider;

	public function onEnable(){
		$this->saveDefaultConfig();
		$json = $this->getResourceContents("permissions.json");
		$perms = json_decode($json, true);
		$stack = [];
		$this->walkPerms($stack, $perms);
		Permission::loadPermissions($perms);
	}
	private function walkPerms(array $stack, array &$perms){
		$prefix = implode(".", $stack) . ".";
		foreach(array_keys($perms) as $key){
			$perms[$prefix . $key] = $perms[$key];
			unset($perms[$key]);
			$stack2 = $stack;
			$stack2[] = $key;
			if(isset($perms[$prefix . $key]["children"])){
				$this->walkPerms($stack2, $perms[$prefix . $key]["children"]);
			}
		}
	}

	public function getResourceContents($path){
		$fh = $this->getResource($path);
		$out = stream_get_contents($fh);
		fclose($fh);
		return $out;
	}

	/**
	 * @return UserConfigDataProvider
	 */
	public function getUserConfigDataProvider(){
		return $this->userConfigDataProvider;
	}
	/**
	 * @param UserConfigDataProvider $userConfigDataProvider
	 */
	public function setUserConfigDataProvider($userConfigDataProvider){
		if($this->userConfigDataProvider !== null){
			$this->userConfigDataProvider->close();
		}
		$this->userConfigDataProvider = $userConfigDataProvider;
	}
}
