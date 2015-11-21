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

class BlockCollection{
	/** @var BlockEntry[] */
	private $blocks;

	/**
	 * BlockCollection constructor.
	 *
	 * @param BlockEntry[] $blocks
	 */
	public function __construct(array $blocks = []){
		$this->blocks = $blocks;
	}

	public function getBlocks(){
		return $this->blocks;
	}

	public function addBlock(BlockEntry $entry){
		$this->blocks[] = $entry;
	}

	public function getRandomBlock(){
		$max = 0;
		foreach($this->blocks as $block){
			$max += $block->weight;
		}
		$rand = mt_rand() / (mt_getrandmax() + 1) * $max;
		foreach($this->blocks as $block){
			$rand -= $block->weight;
			if($rand < 0){
				return $block->block;
			}
		}
		throw new \RuntimeException("This is supposed to be dead code!");
	}
}
