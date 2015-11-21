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

abstract class BufferedIterator implements \Iterator{
	private $backLog = [];
	private $pointer = 0;
	private $valid = true;

	protected abstract function loadNext();
	public function preBuffer($el){
		array_unshift($this->backLog, $el);
		$this->pointer++;
	}

	public function current(){
		if(!$this->valid()){
			return null;
		}
		return $this->backLog[$this->pointer];
	}
	public function next(){
		if($this->pointer === 0){
			$next = $this->loadNext();
			if($next === null){
				$this->valid = false;
			}
			array_unshift($this->backLog, $next);
		}
		--$this->pointer;
	}
	public function key(){
		return count($this->backLog) - $this->pointer - 1;
	}
	public function valid(){
		return $this->valid and count($this->backLog) > 0;
	}
	public function rewind(){
		$this->pointer = count($this->backLog) - 1;
	}
}
