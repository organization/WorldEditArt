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

namespace pemapmodder\worldeditart\lang;

use pemapmodder\worldeditart\WorldEditArt;

class TranslationManager{
	/** @var WorldEditArt */
	private $main;

	/** @var Phrase[][] */
	private $langs = [];

	public function __construct(WorldEditArt $main, $files = []){
		$this->main = $main;
		$availableLangs = json_decode(file_get_contents($main->getDataFolder() . "lang/index.json"), true);
		foreach($availableLangs as $lang){
			if(isset($files[$lang])){
				unset($files[$lang]);
			}
			$path = "lang/$lang.json";
			$data = json_decode(file_get_contents($main->getDataFolder() . $path), true);
			$browser = new LanguageBrowser($data);
			$list = $browser->getPhrases();
			foreach($list as $k => $v){
				$this->langs[$k][$lang] = $v;
			}

			$data = json_decode($main->getResourceContents($path), true);
			$browser = new LanguageBrowser($data);
			$list = $browser->getPhrases();
			foreach($list as $k => $v){
				if(!isset($this->langs[$k][$lang])){
					$this->langs[$k][$lang] = $v;
				}
			}
		}
		if(count($files) > 0){
			$main->getLogger()->notice("These language(s): [" . implode(", ", array_keys($files)) . "] are not being loaded because they are not specified in index.json. Update {$main->getDataFolder()}index.json and restart the server to get them loaded.");
		}
	}

	/**
	 * @param string $key
	 * @param string $lang
	 * @return Phrase|null
	 */
	public function get($key, $lang){
		if(isset($this->langs[$key])){
			if(isset($this->langs[$key][$lang])){
				return $this->langs[$key][$lang];
			}
			if(isset($this->langs[$key]["en"])){
				return $this->langs[$key]["en"];
			}
		}
		$this->main->getLogger()->warning("Returning null for unknown translation string ID '$key'");
		return null;
	}
}
