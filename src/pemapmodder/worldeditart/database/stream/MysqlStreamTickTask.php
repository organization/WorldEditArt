<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pemapmodder\worldeditart\database\stream;

use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\scheduler\PluginTask;

class MysqlStreamTickTask extends PluginTask{
	/** @var WorldEditArt */
	private $main;
	/** @var MysqlStream */
	private $stream;

	public function __construct(WorldEditArt $main, MysqlStream $stream){
		parent::__construct($this->main = $main);
		$this->stream = $stream;
	}
	public function onRun($currentTick){
		$this->stream->tick($this->main);
	}
}
