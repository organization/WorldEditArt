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

use pemapmodder\worldeditart\cmd\StartConfigurationCommand;
use pemapmodder\worldeditart\cmd\WorldEditArtCommand;
use pemapmodder\worldeditart\database\DataProvider;
use pemapmodder\worldeditart\lang\TranslationManager;
use pemapmodder\worldeditart\utils\Fridge;
use Phar;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

class WorldEditArt extends PluginBase{
	private static $PLUGIN_NAME = "WorldEditArt";

	/** @var SessionCollection */
	private $sessionCollection;
	/** @var TranslationManager */
	private $translationManager;
	/** @var Fridge */
	private $objectPool;
	/** @var DataProvider */
	private $dataProvider;

	public function onLoad(){
		self::$PLUGIN_NAME = $this->getDescription()->getName();
	}
	public function onEnable(){
		if(!is_dir($this->getDataFolder())){
			mkdir($this->getDataFolder(), 0777, true);
		}

		$buildInfo = json_decode($this->getResourceContents("meta.build.json"));

		$configFile = $this->getDataFolder() . "config.yml";
		$os = Utils::getOS();
		if($os === "win"){
			$this->getServer()->getCommandMap()->register("wea", new StartConfigurationCommand($this));
		} // register //wea-config

		if(is_file($configFile)){
			$lines = file($configFile);
			if(trim($lines[0]) !== "---" or trim($lines[1]) !== "### WORLDEDITART GAMMA CONFIG FILE ###"){
				$this->getLogger()->warning("Using outdated config file! plugins/WorldEditArt/config.yml will be renamed into config.yml.old");
				rename($configFile, $configFile . ".old");
			}else{
				$ok = true;
			}
		} // warn old config
		if(!isset($ok)){
			$this->getLogger()->notice("Thank you for using WorldEditArt Gamma!");
			$this->getLogger()->notice("You are strongly encouraged to configure WorldEditArt using our installer.");
			if($os === "win"){
				$this->getLogger()->notice("Type `/wea-config` then enter on console to start the installer.");
			}else{
				$this->getLogger()->notice("Please stop the server and run the SHELL COMMAND `" . PHP_BINARY . " " . Phar::running(false) . "` to start the installer.");
			}
			$this->getLogger()->notice("WorldEditArt will continue to run with the default configuration.");
		} // save default config

		$this->objectPool = new Fridge($this);

		if(true){
			$langs = [];
			foreach(scandir($par = rtrim(Phar::running(), "/") . "/resources/lang/") as $file){
				$path = $this->getDataFolder() . "lang/$file";
				if(!is_dir($this->getDataFolder() . "lang")){
					mkdir($this->getDataFolder() . "lang");
				}
				if($file !== "index.json" and strtolower(substr($path, -5)) === ".json"){
					$langs[substr($file, 0, -5)] = true;
				}
				if(!file_exists($path)){
					file_put_contents($path, file_get_contents($par . $file));
				}
			}
			$this->translationManager = new TranslationManager($this, $langs);
		} // initialize translations

		if(true){
			$json = $this->getResourceContents("permissions.json");
			$perms = json_decode($json, true);
			$stack = [];
			$this->walkPerms($stack, $perms);
			Permission::loadPermissions($perms);
		} // register permission nodes

		$this->sessionCollection = new SessionCollection($this);

		new WorldEditArtCommand($this);

		// TODO initialize data provider

		$em1 = TextFormat::GOLD;
		$em2 = TextFormat::LIGHT_PURPLE;
		$green = TextFormat::DARK_GREEN;
		$iso = $buildInfo->buildTime->ISO8601;
		$this->getLogger()->info("$green Enabled$em1 WorldEditArt $buildInfo->type " .
			"#{$buildInfo->typeVersion}$green compiled on $em2" . str_replace("T", " ", $iso));
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
	 * Returns the {@link SessionCollection} for WorldEditArt.
	 *
	 * @return SessionCollection
	 */
	public function getSessionCollection(){
		return $this->sessionCollection;
	}

	/**
	 * Returns the {@link TranslationManager} for WorldEditArt.
	 *
	 * @return TranslationManager
	 */
	public function getTranslationManager(){
		return $this->translationManager;
	}

	/**
	 * Returns the {@link OrderedObjectPool} for WorldEditArt.
	 *
	 * @return Fridge
	 */
	public function getObjectPool(){
		return $this->objectPool;
	}

	/**
	 * Returns the {@link DataProvider} for WorldEditArt.
	 *
	 * @return DataProvider
	 */
	public function getDataProvider(){
		return $this->dataProvider;
	}

	/**
	 * Generates a pseudo-random 6-character string composed of
	 * <code>0-9</code>, <code>A-Z</code> and <code>a-z</code>.
	 *
	 * @return string
	 */
	public static function randomName(){
		return str_replace(["+", "/", "="], ["Q", "Z", ""], base64_encode(self::numToBytes(mt_rand(mt_getrandmax() >> 1, mt_getrandmax()))));
	}
	private static function numToBytes($num){
		$output = "";
		while($num > 0){
			$output = chr($num & 0xFF) . $output;
			$num >>= 8;
		}
		return $output;
	}

	/**
	 * @param Server $server
	 *
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
