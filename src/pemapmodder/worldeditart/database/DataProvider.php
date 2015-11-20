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
use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Position;

interface DataProvider{
	const ZONE_NONE = 0;
	const ZONE_TYPE_UNDER_CONSTRUCTION = 1;

	/**
	 * Loads data for the given {@link WorldEditSession}, synchronously or asynchronously,
	 * which will subsequently trigger {@link WorldEditSession#init $session->init()} when
	 * data are loaded.
	 *
	 * @param WorldEditSession $session
	 */
	public function loadSession(WorldEditSession $session);

	/**
	 * Saves data for the given {@link WorldEditSession}.<br>
	 * No feedback action is required after session data have been saved.
	 *
	 * @param WorldEditSession $session
	 */
	public function saveSession(WorldEditSession $session);

	/**
	 * Returns the {@link Zone}s <code>$pos</code> is in.
	 *
	 * @param Position $pos
	 *
	 * @return Zone[]|\Iterator<Zone>
	 */
	public function getZones(Position $pos);

	/**
	 * Adds <code>$zone</code> into the database (and the cache).
	 *
	 * @param Zone $zone
	 */
	public function addZone(Zone $zone);

	/**
	 * Removes <code>$zone</code> from the database (and the cache).
	 *
	 * @param Zone $zone
	 */
	public function removeZone(Zone $zone);

	/**
	 * Returns a reference to the main plugin object.
	 *
	 * @return WorldEditArt
	 */
	public function getMain();
}
