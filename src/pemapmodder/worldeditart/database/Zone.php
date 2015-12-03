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

namespace pemapmodder\worldeditart\database;

use pemapmodder\worldeditart\libworldedit\space\Space;

class Zone{
	const TYPE_UNDER_CONSTRUCTION = 1;

	/** @var int */
	private $id;
	/** @var Space */
	private $space;
	/** @var int */
	private $type;

	/**
	 * Zone constructor.
	 *
	 * @param Space $space
	 * @param int   $type
	 */
	public function __construct(Space $space, $type){
		$this->space = $space;
		$this->type = $type;
	}

	public function getSpace(){
		return $this->space;
	}

	public function getType(){
		return $this->type;
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		if(isset($this->id)){
			throw new \RuntimeException("ID is already set");
		}
		$this->id = $id;
		return $this;
	}
}
