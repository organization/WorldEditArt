<?php

/*
 * WEA
 *
 * Copyright (C) 2015 LegendsOfMCPE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author LegendsOfMCPE
 */

namespace pemapmodder\worldeditart\session\queue;

use pemapmodder\worldeditart\libworldedit\BlockEntry;
use pocketmine\block\Block;

class ArrayBackedBlockFilter implements BlockFilter{
	/** @var BlockEntry[] */
	private $entries = [];

	/**
	 * @param BlockEntry[] $entries
	 */
	public function __construct(array $entries){
		$this->entries = $entries;
	}

	public function acceptsBlock(Block $block){
		foreach($this->entries as $entry){
			if($entry->matches($block)){
				return true;
			}
		}
		return false;
	}
}
