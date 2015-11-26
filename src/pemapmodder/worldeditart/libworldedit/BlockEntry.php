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
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

class BlockEntry{
	/** @var Block */
	public $block;
	/** @var number */
	public $weight;

	public function __construct(Block $block, $weight){
		$this->block = $block;
		$this->weight = $weight;
	}

	public static function fromString($string){
		$pos = strpos($string, "/");
		$weight = 1.0;
		if($pos !== false){
			$weight = (float) substr($string, 0, $pos);
			$string = substr($string, $pos + 1);
		}
		$pos = strpos($string, ":");
		$damage = 0;
		if($pos !== false){
			$damage = (int) substr($string, $pos + 1);
			$id = substr($string, 0, $pos);
		}else{
			$id = $string;
		}
		$block = Item::fromString($id);
		if(!($block instanceof ItemBlock)){
			throw new NonExistentBlockException($id);
		}
		$block = $block->getBlock();
		$block->setDamage($damage);
		return new BlockEntry($block, $weight);
	}
}
