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

namespace pemapmodder\worldeditart\session\clips;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Byte;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Int;
use pocketmine\nbt\tag\String;

class Clip{
	/** @var string */
	private $name;
	/** @var string */
	private $owner;
	/** @var int */
	private $creationTime;
	/** @var bool */
	private $isWritable;
	/** @var Vector3 */
	private $anchor;
	/** @var string (don't save level instance directly to prevent memory leak) */
	private $levelName;
	/** @var Block[] an array of Block objects, containing the ID, damage and coordinates relative to anchor, with null level entries */
	private $entries = [];

	protected function __construct($name, $isWritable){
		$this->name = $name;
		$this->isWritable = $isWritable;
	}

	/**
	 * @param Block $sourceBlock a Block with the absolute X, Y and Z from the original level
	 */
	public function addEntry(Block $sourceBlock){
		if(!$this->isWritable){
			throw new \InvalidStateException("This clip is not writable");
		}
		if(!$sourceBlock->isValid()){
			throw new \InvalidArgumentException("Source block must contain a level and absolute coords");
		}
		if($sourceBlock->getLevel()->getName() !== $this->levelName){
			throw new \InvalidStateException("Block is not from the level clip is being written in");
		}
		$delta = $sourceBlock->subtract($this->anchor);
		$insert = Block::get($sourceBlock->getId(), $sourceBlock->getDamage(), Position::fromObject($delta->subtract($this->anchor)));
		$this->entries[] = $insert;
	}

	public function toBinary(){
		$data = new Compound($this->name);
		// we will have trouble updating these NBT tags to PHP 7 :(
		// But hopefully it will be as simple as search & replace with regex:
		// /(String|Int|Byte|blah)/   ->    $1Tag
		// depends if shoghicp does even more destruction :P
		$data->Name = new String("Name", $this->name);
		// unique name of the WorldEditSession
		$data->Owner = new String("Owner", $this->owner);
		// OK, I know that this will no longer work after 2038 January, but who will use this plugin until 2038?
		$data->Creation = new Int("Creation", $this->creationTime);
		$entries = new Enum("Entries", array_map(function (Block $block){
			$compound = new Compound;
			$compound->X = new Int("X", $block->x);
			$compound->Y = new Int("Y", $block->y);
			$compound->Z = new Int("Z", $block->z);
			$compound->Id = new Byte("Id", $block->getId());
			$compound->Damage = new Byte("Damage", $block->getDamage());
			return $compound;
		}, $this->entries));
		$data->Entries = $entries;
		$nbt = new NBT();
		$nbt->setData($data);
		return $nbt->writeCompressed();
	}

	/**
	 * Creates a new clip for writing
	 *
	 * @param Level   $level
	 * @param Vector3 $anchor
	 * @param string  $owner
	 * @param string  $name
	 *
	 * @return Clip
	 */
	public static function createForWrite(Level $level, Vector3 $anchor, $owner, $name = "default"){
		$clip = new Clip($name, true);
		$clip->levelName = $level->getName();
		$clip->anchor = new Vector3($anchor->x, $anchor->y, $anchor->z); // prevent memory leak
		$clip->creationTime = time();
		$clip->owner = $owner;
		return $clip;
	}

	/**
	 * Creates a new clip using data from database for reading
	 *
	 * @param string $compressed
	 *
	 * @return Clip
	 */
	public static function createFromSaved($compressed){
		$nbt = new NBT();
		$nbt->readCompressed($compressed);
		$data = $nbt->getData();
		$instance = new Clip($data["Name"], false);
		$instance->owner = $data["Owner"];
		$instance->creationTime = $data["Creation"];
		/** @var Enum $entriesTag */
		$entriesTag = $data["Entries"];
		for($i = 0; $i < $entriesTag->getCount(); $i++){
			$blockTag = $entriesTag[$i];
			$block = Block::get($blockTag["Id"], $blockTag["Damage"],
				new Position($blockTag["X"], $blockTag["Y"], $blockTag["Z"]));
			$instance->entries[] = $block;
		}
		return $instance;
	}
}
