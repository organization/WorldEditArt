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

namespace pemapmodder\worldeditart\cmd;

interface BaseCommand{
	/**
	 * Returns a string or an array of strings. (If only one is returned, it will be casted into an array)<br>
	 * The first item in the array will be taken as the main command name shown at //help.<br>
	 *
	 * @return string|string[]
	 */
	public function getNames();

	public function getDescription();

	public function getUsage();
}
