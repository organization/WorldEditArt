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

use pemapmodder\worldeditart\session\WorldEditSession;

class MysqlDataProvider extends BaseDataProvider{
	public function init(){
	}

	protected function loadZones(){
	}
	protected function addZoneImpl(Zone $zone){
		// TODO: Implement addZoneImpl() method.
	}
	protected function removeZoneImpl(Zone $zone){
		// TODO: Implement removeZoneImpl() method.
	}
	protected function loadSessionImpl($callbackId){
		// TODO: Implement loadSessionImpl() method.
	}
	public function saveSession(WorldEditSession $session){
		// TODO: Implement saveSession() method.
	}
}
