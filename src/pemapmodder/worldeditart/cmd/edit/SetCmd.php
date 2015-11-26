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

namespace pemapmodder\worldeditart\cmd\edit;

use pemapmodder\worldeditart\cmd\Cmd;
use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\libworldedit\BlockCollection;
use pemapmodder\worldeditart\libworldedit\BlockEntry;
use pemapmodder\worldeditart\session\queue\Rheostat;
use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\utils\FormattedArguments;

class SetCmd implements Cmd{
	public function getNames(){
		return ["set", "s"];
	}
	public function getDescription(){
		return Lang::EDIT_SET_DESCRIPTION;
	}
	public function getUsage(){
		return Lang::EDIT_SET_USAGE;
	}
	public function canUse(WorldEditSession $session){
		return $session->hasPermission("worldeditart.builder.edit.set");
	}
	public function run(WorldEditSession $session, array $args){
		$params = new FormattedArguments($args);
		$sel = $session->getSelection($params->opt("n", "default"));
		if($sel === null){
			return Lang::ERR_NO_SEL;
		}
		$coll = new BlockCollection();
		while(($string = $params->nextPlain()) !== null){
			$entry = BlockEntry::fromString($string);
			// TODO handle exception
			$coll->addBlock($entry);
		}
		$bb = $sel->iteratorAllBlocks($coll);
		$rheostat = new Rheostat($session->getQueue(), $bb, $session->translate(Lang::SPACE_SET_BLOCKS, [
			"SPACE" => $sel->name($session),
		]));
		$session->getQueue()->addTask($rheostat);
		return Lang::EDIT_SET_IN_PROGRESS;
	}
}
