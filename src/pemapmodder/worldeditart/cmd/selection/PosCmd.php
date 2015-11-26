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

namespace pemapmodder\worldeditart\cmd\selection;

use pemapmodder\worldeditart\cmd\Cmd;
use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\libworldedit\space\CuboidSpace;
use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\utils\FormattedArguments;
use pocketmine\Player;

class PosCmd implements Cmd{
	/** @var bool */
	private $isTwo;

	/**
	 * PosCmd constructor.
	 *
	 * @param bool $isTwo
	 */
	public function __construct($isTwo){
		$this->isTwo = $isTwo;
	}

	public function getNames(){
		return $this->isTwo ? ["2", "pos2"] : ["1", "pos1"];
	}
	public function getDescription(){
		return Lang::SELECTION_POS_DESCRIPTION;
	}
	public function getUsage(){
		return $this->isTwo ? Lang::SELECTION_POS_USAGE_2 : Lang::SELECTION_POS_USAGE_1;
	}
	public function canUse(WorldEditSession $session){
		return $session->hasPermission("worldeditart.builder.selection.pos");
	}
	public function run(WorldEditSession $session, array $args){
		$params = new FormattedArguments($args);
		if($params->enabled("b")){
			$pos = $session->getBookmark();
			$level = $pos->getLevel($session->getMain()->getServer());
		}elseif(($name = $params->opt("b")) !== null){
			$pos = $session->getBookmark($name);
			$level = $pos->getLevel($session->getMain()->getServer());
		}elseif(($name = $params->opt("p")) !== null){
			$pos = $session->getMain()->getServer()->getPlayer($name);
			$level = $pos->getLevel();
			if(!($pos instanceof Player)){
				return Lang::ERR_OFFLINE;
			}
		}else{
			$pos = $session->getLocation();
			$level = $pos->getLevel();
		}
		$name = $params->opt("n", "default");
		$sel = $session->getSelection($name);
		$X = "x";
		$Y = "y";
		$Z = "z";
		if($this->isTwo){
			$X .= "2";
			$Y .= "2";
			$Z .= "2";
		}else{
			$X .= "1";
			$Y .= "1";
			$Z .= "1";
		}
		if(!($sel instanceof CuboidSpace)){
			$sel = new CuboidSpace;
			$sel->setLevelName($level->getName());
			$session->setSelection($sel, $name);
		}
		$sel->{$X} = $pos->x;
		$sel->{$Y} = $pos->y;
		$sel->{$Z} = $pos->z;
		$session->sendMessage(Lang::SELECTION_POS_SUCCESS, [
			"COORD_X" => $pos->x,
			"COORD_Y" => $pos->y,
			"COORD_Z" => $pos->z,
			"POINT_ID" => $this->isTwo ? "2" : "1",
		]);
		$session->sendMessage(Lang::SELECTION_POS_INFO);
		$session->sendMessageDirect($sel->name($session));
		return null;
	}
}
