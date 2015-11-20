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

namespace pemapmodder\worldeditart\session\queue;

use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\session\WorldEditSession;

class Queue{
	/** @var WorldEditSession */
	private $owner;
	/** @var Rheostat[] */
	private $rheostats = [];

	public function __construct(WorldEditSession $owner){
		$this->owner = $owner;
	}

	public function tip(){
		$tip = $this->owner->translate(Lang::QUEUE_TIP_TITLE) . "\n";
		foreach($this->rheostats as $rheostat){
			$tip .= $this->owner->translate(Lang::QUEUE_TIP_ENTRY, [
					"TASK_NAME" => $rheostat->name(),
					"PROGRESS_PERC" => round($rheostat->done() / $rheostat->total() * 100, 1),
			]);
		}
		return trim($tip);
	}
}
