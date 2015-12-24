<?php

/*
 * WEA
 *
 * Copyright (C) 2015 LegendsOfMCPE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author LegendsOfMCPE
 */

namespace pemapmodder\worldeditart\cmd\edit;

use pemapmodder\worldeditart\cmd\Cmd;
use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\libworldedit\BlockCollection;
use pemapmodder\worldeditart\libworldedit\BlockEntry;
use pemapmodder\worldeditart\session\queue\ArrayBackedBlockFilter;
use pemapmodder\worldeditart\session\queue\Rheostat;
use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\utils\FormattedArguments;

class ReplaceCmd implements Cmd{
	public function getNames(){
		return ["replace", "rep"];
	}

	public function getDescription(){
		return Lang::EDIT_REPLACE_DESCRIPTION;
	}

	public function getUsage(){
		return Lang::EDIT_REPLACE_USAGE;
	}

	public function canUse(WorldEditSession $session){
		return $session->hasPermission("worldeditart.builder.edit.replace");
	}

	public function run(WorldEditSession $session, array $args){
		$params = new FormattedArguments($args);
		$sel = $session->getSelection($params->opt("n", "default"));
		if($sel === null){
			return Lang::ERR_NO_SEL;
		}
		$blocks = $params->nextPlain();
		$sources = [];
		foreach(explode(",", $blocks) as $block){
			$sources[] = BlockEntry::fromString($block, false);
		}
		$targets = new BlockCollection();
		while(($string = $params->nextPlain()) !== null){
			$entry = BlockEntry::fromString($string);
			// TODO handle exception
			$targets->addBlock($entry);
		}
		$bb = $sel->iteratorAllBlocks($targets);
		$rheostat = new Rheostat($session->getQueue(), new ArrayBackedBlockFilter($sources), $bb, $session->translate(Lang::SPACE_SET_BLOCKS, [
			"SPACE" => $sel->name($session),
		]));
		$session->getQueue()->addTask($rheostat);
		return Lang::EDIT_REPLACE_IN_PROGRESS;
	}
}
