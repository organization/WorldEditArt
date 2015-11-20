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

namespace pemapmodder\worldeditart;

use pemapmodder\worldeditart\session\PlayerSession;
use pemapmodder\worldeditart\session\WorldEditSession;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;

class SessionCollection implements Listener{
	/** @var WorldEditArt */
	private $main;

	/** @var WorldEditSession[] */
	private $sessions = [];

	public function __construct(WorldEditArt $main){
		$this->main = $main;
		$main->getServer()->getPluginManager()->registerEvents($this, $main);

		foreach($main->getServer()->getOnlinePlayers() as $player){
			$this->internal_onJoin($player);
		}
	}
	public function close(){
		foreach($this->main->getServer()->getOnlinePlayers() as $player){
			$this->internal_onQuit($player);
		}
	}

	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$this->internal_onJoin($player);
	}
	private function internal_onJoin(Player $player){
		$this->sessions[$player->getId()] = new PlayerSession($this->main, $player);
	}

	public function onQuit(PlayerQuitEvent $event){
		$this->internal_onQuit($event->getPlayer());
	}
	private function internal_onQuit(Player $player){
		if(isset($this->sessions[$player->getId()])){
			$this->sessions[$player->getId()]->close();
			unset($this->sessions[$player->getId()]);
		}
	}

	/**
	 * Returns the
	 *
	 * @param Player|string $player the partial name (same rules as {@link \pocketmine\Server#getPlayer}) of the
	 *                              player, or the {@link Player} object representing the player.
	 *
	 * @return WorldEditSession|null An instance of {@link WorldEditSession} if found, <code>null</code> otherwise.
	 */
	public function getSession($player){
		if(is_string($player)){
			$player = $this->main->getServer()->getPlayer($player);
		}
		if(!($player instanceof Player)){
			return null;
		}
		return isset($this->sessions[$player->getId()]) ? $this->sessions[$player->getId()] : null;
	}
}
