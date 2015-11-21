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

namespace pemapmodder\worldeditart\libworldedit\space\iterator;

use pemapmodder\worldeditart\utils\BufferedIterator;

abstract class BufferedBlockIterator extends BufferedIterator{
	private $offset = 0;
	private $ended = false;
	public function loadNext(){
		while(!$this->ended){
			$block = $this->getBlock($this->offset++);
			if($block !== null){
				return $block;
			}
		}
		return null;
	}
	protected function end(){
		$this->ended = true;
	}
	protected abstract function getBlock($offset);
}
