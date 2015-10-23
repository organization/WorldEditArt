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

namespace pemapmodder\worldeditart\user;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\Player;

class PlayerSession extends User{
	const TYPE = "worldeditart.player";

	const TOUCH_MODE_NONE = 0;

	/** @var WorldEditArt */
	private $main;
	/** @var Player */
	private $player;

	private $blockTouchMode = self::TOUCH_MODE_NONE;

	################
	## INHERITIED ##
	##   METHODS  ##
	################
	public function getType(){
		return self::TYPE;
	}
	public function getName(){
		return $this->player->getName();
	}

	###################
	## GENERAL UTILS ##
	##   FUNCTIONS   ##
	###################
	public function __construct(WorldEditArt $main, Player $player){
		$this->player = $player;
		$this->main = $main;
	}
	public function finalize(){
		$this->saveUserConfig();
	}

	#########################
	##        COMMON       ##
	## GETTERS AND SETTERS ##
	#########################
	public function getMain(){
		return $this->main;
	}
	public function getLocation(){
		return $this->player->getLocation();
	}
	/**
	 * @return int
	 */
	public function getBlockTouchMode(){
		return $this->blockTouchMode;
	}
	/**
	 * @param int $blockTouchMode
	 */
	public function setBlockTouchMode($blockTouchMode){
		$this->blockTouchMode = $blockTouchMode;
	}
	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}
}
