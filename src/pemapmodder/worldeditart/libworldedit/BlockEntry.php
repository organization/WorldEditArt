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
	/** @var bool */
	public $damageSensitive = false;
	/** @var number */
	public $weight;

	protected function __construct(Block $block, $damageSensitive, $weight){
		$this->block = $block;
		$this->damageSensitive = $damageSensitive;
		$this->weight = $weight;
	}

	public function matches(Block $block){
		return $block->getId() === $this->block->getId() and ($this->damageSensitive or $block->getDamage() === $this->block->getDamage());
	}

	public static function fromString($string, $acceptWeightModification = true){
		$weight = 1.0;
		if($acceptWeightModification){
			$pos = strpos($string, "/");
			if($pos !== false){
				$weight = (float) substr($string, 0, $pos);
				$string = substr($string, $pos + 1);
			}
		}
		$pos = strpos($string, ":");
		$damage = 0;
		if($pos !== false){
			$damage = (int) substr($string, $pos + 1);
			$id = substr($string, 0, $pos);
			$damageSensitive = true;
		}else{
			$id = $string;
			$damageSensitive = false;
		}
		$block = Item::fromString($id);
		if(!($block instanceof ItemBlock)){
			throw new NonExistentBlockException($id);
		}
		$block = $block->getBlock();
		$block->setDamage($damage);
		return new BlockEntry($block, $damageSensitive, $weight);
	}
}
