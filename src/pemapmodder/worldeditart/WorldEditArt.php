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

use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;

class WorldEditArt extends PluginBase{
	public function onEnable(){
		$this->saveDefaultConfig();
		$fh = $this->getResource("permissions.json");
		$json = stream_get_contents($fh);
		fclose($fh);
		$perms = json_decode($json, true);
		$stack = [];
		$this->walkPerms($stack, $perms);
		var_dump($perms);
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
}
