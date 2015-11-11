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

namespace pemapmodder\worldeditart\session;

use pocketmine\Player;

class PlayerSession extends WorldEditSession{
	const TYPE = "worldeditart.player";

	/** @var Player */
	private $player;

	public function __construct(Player $player){
		$this->player = $player;
	}

	public function getLocation(){
		return $this->player->getLocation();
	}

	public function hasPermission($permission){
		return $this->player->hasPermission($permission);
	}

	public function getType(){
		return self::TYPE;
	}

	public function getName(){
		return strtolower($this->player->getName());
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}

	/**
	 * {@inheritdoc}
	 * This method also checks whether the player is online.
	 */
	public function isValid(){
		return parent::isValid() and $this->player instanceof Player and $this->player->isOnline();
	}
}
