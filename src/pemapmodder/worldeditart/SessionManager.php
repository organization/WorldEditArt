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

use pemapmodder\worldeditart\user\PlayerSession;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class SessionManager implements Listener{
	/** @var PlayerSession[] */
	private $playerUsers = [];
	public function quit(PlayerQuitEvent $event){
		if(isset($this->playerUsers[$event->getPlayer()->getId()])){
			$this->playerUsers[$event->getPlayer()->getId()]->finalize();
			unset($this->playerUsers[$event->getPlayer()->getId()]);
		}
	}
}
