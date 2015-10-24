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

namespace pemapmodder\worldeditart\provider\userconfig;

use pemapmodder\worldeditart\user\User;

interface UserConfigDataProvider{
	public function loadUserConfig(User $user);
	public function saveUserConfig(User $user, UserConfig $config);
	public function close();
}
