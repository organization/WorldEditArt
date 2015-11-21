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

namespace pemapmodder\worldeditart\libworldedit;

use pocketmine\block\Block;

class BlockEntry{
	/** @var Block */
	public $block;
	/** @var number */
	public $weight;

	public function __construct(Block $block, $weight){
		$this->block = $block;
		$this->weight = $weight;
	}
}
