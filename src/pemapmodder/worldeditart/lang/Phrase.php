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
	/** @var string|string[] */
	private $value;
	/** @var string */
	private $updated;

	public function __construct($array){
		$this->value = $array["value"];
		$this->updated = $array["updated"];
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
}
