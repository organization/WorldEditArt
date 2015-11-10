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
use pocketmine\Server;

class WorldEditArt extends PluginBase{
	private static $PLUGIN_NAME = "WorldEditArt";

	public function onLoad(){
		self::$PLUGIN_NAME = $this->getDescription()->getName();
	}
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
	 * @param Server $server
	 * @return WorldEditArt|null
	 */
	public static function getInstance(Server $server){
		$plugin = $server->getPluginManager()->getPlugin(self::$PLUGIN_NAME);
		if($plugin instanceof self and $plugin->isEnabled()){
			return $plugin;
		}
		return null;
	}
}
