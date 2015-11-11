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

	private $langs = [];

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$availableLangs = json_decode($main->getResourceContents("lang/index.json"), true);
		foreach($availableLangs as $lang){
			$path = "lang/$lang.json";
			$data = json_decode($main->getResourceContents($path), true);
			$walker = new ArrayWalker($data);
			$list = $walker->getPlainList();
			foreach($list as $k => $v){
				$list[$k][$lang] = $v;
			}
		}
	}
	public function get($key, $lang){
		if(isset($this->langs[$key])){
			if(isset($this->langs[$key][$lang])){
				return $this->langs[$key][$lang];
			}
			if(isset($this->langs[$key]["en"])){
				return $this->langs[$key]["en"];
			}
		}
		return null;
	}
}
