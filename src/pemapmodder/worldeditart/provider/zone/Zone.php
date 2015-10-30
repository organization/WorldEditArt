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

namespace pemapmodder\worldeditart\provider\zone;

use pemapmodder\worldeditart\libworldedit\space\Space;

class Zone{
	/** @var int|null */
	private $id = null;
	/** @var Space */
	private $space;
	/** @var int */
	private $type;

	public function __construct(Space $space, $type){
		$this->space = $space;
		$this->type = $type;
	}

	/**
	 * @return Space
	 */
	public function getSpace(){
		return $this->space;
	}
	/**
	 * @return int
	 */
	public function getType(){
		return $this->type;
	}
	/**
	 * @return int|null
	 */
	public function getId(){
		return $this->id;
	}
	/**
	 * @param int|null $id
	 */
	public function setId($id){
		$this->id = $id;
	}
}
