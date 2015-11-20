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

require_once __DIR__ . "/functions.php";

echo "Thank you for using WorldEditArt Gamma by PEMapModder.", PHP_EOL;
echo "In this wizard, we are going to configure the plugin.", PHP_EOL;

$config = ["configVersion" => 1];
echo "Let's start with the default user config.", PHP_EOL;
echo "[?] Which item should be used as the default wand? The following are some example replies.", PHP_EOL;
echo "> Type `1` for item of ID 1 and any damage.", PHP_EOL;
echo "> Type `1:3` for item of ID 1 and damage of 3.", PHP_EOL;
echo "> Type `iron ingot` for the iron ingot item and any damage.", PHP_EOL;
echo "> Type `iron ingot:3` for the iron ingot item and damage of 3.", PHP_EOL;
loadItem(readConsole(), $id, $damage);
$config["defaultConfig"]["wand"]["id"] = $id;
$config["defaultConfig"]["wand"]["damage"] = $damage;
echo "[?] What about the item for the jump action? ";
loadItem(readConsole(), $id, $damage);
$config["defaultConfig"]["jump"]["id"] = $id;
$config["defaultConfig"]["jump"]["damage"] = $damage;

echo "WorldEditArt provides a mechanism called \"safe mode\". When a user is in safe mode,", PHP_EOL;
echo "  he/she will only be able to carry out WorldEditArt actions in \"Under-Construction Zones\".", PHP_EOL;
echo "[?] Do you want to enable safe mode for users by default? ";
$config["defaultConfig"]["safety"]["safeMode"] = queryYN(false);
echo "A cool feature in WorldEditArt is that a user has to run the //sudo command ", PHP_EOL;
echo "  before he/she can run any other WorldEditArt commands. This feature can ", PHP_EOL;
echo "  help avoid accidental or unintended WorldEditArt actions.", PHP_EOL;
echo "[?] Do you want to enable this feature? ";
$config["defaultConfig"]["safety"]["sudoRequired"] = queryYN(true);

echo yaml_emit($config);

echo "That's all! You can start your server now.", PHP_EOL;

exec("pause");
