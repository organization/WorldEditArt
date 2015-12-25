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

use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\session\WorldEditSession;

interface Cmd extends Lang{
	/**
	 * Sends the command's usage message to the player.
	 */
	const RET_USAGE = 1;
	/**
	 * Tells the player that he has no permission to use this command.
	 */
	const RET_NO_PERM = 2;

	/**
	 * Returns a string or an array of strings. (If only one is returned, it will be casted into an array)<br>
	 * The first item in the array will be taken as the main command name shown at //help.<br>
	 *
	 * @return string|string[]
	 */
	public function getNames();

	/**
	 * Returns the translation string ID of the description, or <code>"%raw%Raw description message"</code>
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 * Returns the translation string ID of the usage, or <code>"%raw%Raw usage message"</code>
	 *
	 * @return string
	 */
	public function getUsage();

	/**
	 * Returns whether the passed {@link WorldEditSession} can use this command.
	 *
	 * @param WorldEditSession $session
	 *
	 * @return bool
	 */
	public function canUse(WorldEditSession $session);

	/**
	 * Executes the command.<br>
	 * If a string is returned, it will be triggered upon {@link WorldEditSession::sendMessage}.<br>
	 * If an int is returned and it is one of the <code>RET_***</code> constants in the {@link Cmd} interface,
	 * action described in the constant's documentation will be executed.<br>
	 * Nothing will be done if any other values of any types are returned.
	 *
	 * @param WorldEditSession $session
	 * @param string[]         $args
	 *
	 * @return string|int|null|void
	 */
	public function run(WorldEditSession $session, array $args);
}
