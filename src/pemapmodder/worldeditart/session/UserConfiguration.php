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

// WHY NO LOMBOK IN PHP!!!
class UserConfiguration{
	private $configModified = false;

	/**
	 * This represents whether the user is default to have sudo mode turned off
	 *
	 * @var bool $sudoModeRequired <code>true</code> if user will be created with sudo mode off (so he has to turn it
	 *      on explicitly), <code>false</code> otherwise.
	 */
	public $sudoModeRequired;

	/**
	 * This represents whether the user has safe mode on
	 *
	 * @var bool $safeModeOn
	 */
	public $safeModeOn;

	/**
	 * This represents the user's preferred language.
	 *
	 * @var string $lang
	 */
	public $lang;

	public function __set($name, $value){
		$this->{$name} = $value;
		$this->configModified = true;
	}

	/**
	 * Returns whether this config has been modified since construction or last {@link
	 * UserConfiguration#resetModifiedTracker} call.
	 *
	 * @return bool
	 */
	public function isConfigModified(){
		return $this->configModified;
	}

	public function resetModifiedTracker(){
		$this->configModified = false;
	}
}
