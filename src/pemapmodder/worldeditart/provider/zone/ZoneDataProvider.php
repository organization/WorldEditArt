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

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Position;

interface ZoneDataProvider{
	/**
	 * Gets an array of all zones. Array keys have no meaning.<br>
	 * The {@link Zone}s may have null ID if they are adding-in-progress.
	 * @return Zone[]
	 */
	public function getZones();
	/**
	 * Gets the oldest zone that includes the {@link Position} passed.<br>
	 * The result {@link Zone} may have null ID if it is adding-in-progress.
	 * @param Position $pos
	 * @return Zone|null
	 */
	public function getZone(Position $pos);
	/**
	 * This method adds the {@link Zone} into the database and assigns a new zone ID to the {@link Zone}
	 * @param Zone $zone
	 */
	public function addZone(Zone $zone);
	/**
	 * @return WorldEditArt
	 */
	public function getMain();
}
