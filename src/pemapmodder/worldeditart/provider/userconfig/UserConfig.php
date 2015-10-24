<?php

/*
 * WorldEditArt
 *
 * Copyright (C) 2015 PEMapModder
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General private License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace pemapmodder\worldeditart\provider\userconfig;

use pemapmodder\worldeditart\WorldEditArt;

class UserConfig{
	const UNSPECIFIED_INT = -1;
	///////////
	// ITEMS //
	///////////
	/** @var int */
	private $wandId;
	/** @var int */
	private $wandDamage;
	/** @var int */
	private $jumpId;
	/** @var int */
	private $jumpDamage;

	////////////
	// SAFETY //
	////////////
	/** @var bool */
	private $safeMode;
	/** @var bool */
	private $sudoRequired;
	/** @var int */
	private $defaultSudoSession;

	//////////////////
	// OPTIMIZATION //
	//////////////////
	/** @var int */
	private $maxUndoQueue;
	/** @var int */
	private $tickEditThreshold;

	public function __construct(WorldEditArt $main){
		$c = $main->getConfig();
		$this->wandId = $c->getNested("defaultConfig.wand.id", 294);
		$this->wandDamage = $c->getNested("defaultConfig.wand.damage", self::UNSPECIFIED_INT);
		$this->jumpId = $c->getNested("defaultConfig.jump.id", 345);
		$this->jumpDamage = $c->getNested("defaultConfig.jump.damage", self::UNSPECIFIED_INT);
		$this->safeMode = $c->getNested("defaultConfig.safety.safeMode", false);
		$this->sudoRequired = $c->getNested("defaultConfig.safety.sudoRequired", true);
		$this->defaultSudoSession = $c->getNested("defaultConfig.safety.defaultSudoSession", self::UNSPECIFIED_INT);
		$this->maxUndoQueue = $c->getNested("defaultConfig.optimization.maxUndoQueue", 5);
		$this->tickEditThreshold = $c->getNested("defaultConfig.optimization.tickEditThreshold", self::UNSPECIFIED_INT);
	}

	/**
	 * @return int
	 */
	public function getWandId(){
		return $this->wandId;
	}
	/**
	 * @return int
	 */
	public function getWandDamage(){
		return $this->wandDamage;
	}
	/**
	 * @return int
	 */
	public function getJumpId(){
		return $this->jumpId;
	}
	/**
	 * @return int
	 */
	public function getJumpDamage(){
		return $this->jumpDamage;
	}
	/**
	 * @return bool
	 */
	public function getSafeMode(){
		return $this->safeMode;
	}
	/**
	 * @return bool
	 */
	public function getSudoRequired(){
		return $this->sudoRequired;
	}
	/**
	 * @return int
	 */
	public function getDefaultSudoSession(){
		return $this->defaultSudoSession;
	}
	/**
	 * @return int
	 */
	public function getMaxUndoQueue(){
		return $this->maxUndoQueue;
	}
	/**
	 * @return int
	 */
	public function getTickEditThreshold(){
		return $this->tickEditThreshold;
	}
	/**
	 * @param int
	 */
	public function setWandId($wandId){
		$this->wandId = $wandId;
	}
	/**
	 * @param int
	 */
	public function setWandDamage($wandDamage){
		$this->wandDamage = $wandDamage;
	}
	/**
	 * @param int
	 */
	public function setJumpId($jumpId){
		$this->jumpId = $jumpId;
	}
	/**
	 * @param int
	 */
	public function setJumpDamage($jumpDamage){
		$this->jumpDamage = $jumpDamage;
	}
	/**
	 * @param bool
	 */
	public function setSafeMode($safeMode){
		$this->safeMode = $safeMode;
	}
	/**
	 * @param bool
	 */
	public function setSudoRequired($sudoRequired){
		$this->sudoRequired = $sudoRequired;
	}
	/**
	 * @param int
	 */
	public function setDefaultSudoSession($defaultSudoSession){
		$this->defaultSudoSession = $defaultSudoSession;
	}
	/**
	 * @param int
	 */
	public function setMaxUndoQueue($maxUndoQueue){
		$this->maxUndoQueue = $maxUndoQueue;
	}
	/**
	 * @param int
	 */
	public function setTickEditThreshold($tickEditThreshold){
		$this->tickEditThreshold = $tickEditThreshold;
	}
	public function setDatum($name, $value){
		if(isset($this->{$name})){
			$this->{$name} = $value;
		}
	}
}
