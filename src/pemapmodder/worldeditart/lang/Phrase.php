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

class Phrase{
	/** @var string */
	private $name;
	/** @var string|string[] */
	private $value;
	/** @var string */
	private $updated;
	/** @var string[] */
	private $params = [];

	public function __construct($name, $array){
		$this->name = $name;
		$this->value = $array["value"];
		$this->updated = $array["updated"];
		$this->params = isset($array["params"]) ? $array["params"] : [];
	}

	/**
	 * @return string|string[]
	 */
	public function getValue(){
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getUpdated(){
		return $this->updated;
	}

	/**
	 * @param string[] $params
	 * @return string
	 */
	public function format($params){
		if(!is_string($this->value)){
			throw new \RuntimeException("Could not format non-string phrase");
		}
		$keys = array_fill_keys($this->params, true);
		foreach($params as $k => $v){
			if(isset($keys[$k])){
				unset($keys[$k]);
			}
		}
		if(count($keys) > 0){
			throw new \RuntimeException("Missing translation parameter " . implode(", ", array_keys($keys)) . " for phrase $this->name");
		}
		$format = $this->value;
		foreach($params as $key => $param){
			$format = str_replace("%$key%", $param, $format);
		}
		return $format;
	}
}
