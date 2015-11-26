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
use pemapmodder\worldeditart\libworldedit\space\CylinderSpace;
use pemapmodder\worldeditart\session\WorldEditSession;
use pemapmodder\worldeditart\utils\FormattedArguments;
use pocketmine\Player;

class CylinderPosCmd implements Cmd{
	/** @var bool */
	private $isTop;

	/**
	 * PosCmd constructor.
	 *
	 * @param bool $isTop
	 */
	public function __construct($isTop){
		$this->isTop = $isTop;
	}

	public function getNames(){
		return $this->isTop ? ["c2", "cyl2", "2c"] : ["c1", "cyl1", "1c"];
	}
	public function getDescription(){
		return Lang::SELECTION_CYLINDER_POS_DESCRIPTION;
	}
	public function getUsage(){
		return $this->isTop ? Lang::SELECTION_CYLINDER_POS_USAGE_2 : Lang::SELECTION_CYLINDER_POS_USAGE_1;
	}
	public function canUse(WorldEditSession $session){
		return $session->hasPermission("worldeditart.builder.selection.cylpos");
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
		if(!($sel instanceof CylinderSpace)){
			$sel = new CylinderSpace;
			$sel->setLevelName($level->getName());
			$session->setSelection($sel, $name);
		}
		if($this->isTop){
			$sel->setTopCenter($pos);
		}else{
			$sel->setBaseCenter($pos);
		}
		$session->sendMessage(Lang::SELECTION_POS_SUCCESS, [
			"COORD_X" => $pos->x,
			"COORD_Y" => $pos->y,
			"COORD_Z" => $pos->z,
			"TOP_OR_BASE" => $session->translate($this->isTop ? Lang::PHRASE_TOP_CENTER : Lang::PHRASE_BASE_CENTER),
		]);
		$session->sendMessage(Lang::SELECTION_POS_INFO);
		$session->sendMessageDirect($sel->name($session));
		return null;
	}
}
