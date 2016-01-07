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

use pocketmine\level\Position;
use pocketmine\utils\Binary;

/**
 * @deprecated use NBT instead
 *
 * @package    pemapmodder\worldeditart\utils@
 */
class ByteBufferWriter{
	public $buffer = "";

	public function putRaw($raw){
		$this->buffer .= $raw;
	}

	public function putByte($byte){
		$this->buffer .= chr($byte);
	}

	public function putShort($short){
		$this->buffer .= Binary::writeLShort($short);
	}

	public function putInt($int){
		$this->buffer .= Binary::writeLInt($int);
	}

	public function putLong($long){
		$this->buffer .= Binary::writeLLong($long);
	}

	public function putFloat($float){
		$this->buffer .= Binary::writeLFloat($float);
	}

	public function putDouble($double){
		$this->buffer .= Binary::writeLDouble($double);
	}

	public function putString($string){
		$this->buffer .= Binary::writeLShort(strlen($string));
		$this->buffer .= $string;
	}

	public function putPosition(Position $position){
		$this->putLong($position->x);
		$this->putShort($position->y);
		$this->putLong($position->z);
		$this->putString($position->isValid() ? $position->getLevel()->getName() : "");
	}
}
