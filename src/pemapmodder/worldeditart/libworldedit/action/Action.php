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

namespace pemapmodder\worldeditart\libworldedit\action;

/**
 * This interface is for any actions that can be put into a player's undo queue.<br>
 * Actions are supposed to be executed synchronously, called from a scheduled task.
 */
interface Action{
	/**
	 * Execute the redo action for the next tick.
	 *
	 * @return bool Returns <code>true</code> if there is still something more to redo, <code>false</code> if the
	 *              action has completed.
	 */
	public function redo();

	/**
	 * Undo the action for the next tick.
	 *
	 * @return bool Returns <code>true</code> if there is still something more to undo, <code>false</code> if the
	 *              action has been completely redone.
	 */
	public function undo();
}
