<?php

/*
 * Small-ZC-Plugins
 *
 * Copyright (C) 2015 PEMapModder and contributors
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
	private $blockChangeThreshold;

	//////////////////
	// OPTIMIZATION //
	//////////////////
	private $maxUndoQueue = 5;
	private $perSecondEdits = 5;

	public function __construct(WorldEditArt $main){

	}
}
