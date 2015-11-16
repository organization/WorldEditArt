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

class LanguageBrowser{
	private $keys;
	private $phrases = [];

	public function __construct(array $array){
		$this->keys = [];
		$this->recurseWalk($array);
	}
	private function recurseWalk(array $array){
		$prefix = implode(".", $this->keys) . ".";
		foreach($array as $k => $v){
			if($this->isLanguageArray($v)){
				$this->phrases[$prefix . $k] = new Phrase($v);
			}else{
				$cnt = count($this->keys);
				$this->keys[$cnt] = $k;
				$this->recurseWalk($v);
				unset($this->keys[$cnt]);
			}
		}
	}
	private function isLanguageArray(array $array){
		return count($array) === 2 and isset($array["value"], $array["updated"]);
	}

	/**
	 * @return Phrase[]
	 */
	public function getPhrases(){
		return $this->phrases;
	}
}
