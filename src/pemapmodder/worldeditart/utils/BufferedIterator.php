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

namespace pemapmodder\worldeditart\utils;

abstract class BufferedIterator{
	private $backArray = [];
	private $valid = true;

	protected abstract function loadNext();

	public function __construct(){
		$this->backArray[] = $this->loadNext();
	}

	public function key(){
		return count($this->backArray) - 1;
	}

	public function next(){
		$next = $this->loadNext();
		if($next === null){
			$this->valid = false;
		}
		$this->backArray[] = $next;
	}

	public function valid(){
		return $this->valid;
	}

	public function current(){
		return end($this->backArray);
	}
}
