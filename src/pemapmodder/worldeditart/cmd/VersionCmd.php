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

class VersionCmd implements BaseCmd{
	public function getNames(){
		return ["version", "v"];
	}
	public function getDescription(){
		return self::CMDS_VERSION_DESCRIPTION;
	}
	public function getUsage(){
		return self::CMDS_VERSION_USAGE;
	}
	public function canUse(WorldEditSession $session){
		return true;
	}
	public function run(WorldEditSession $session, array $args){
		$phrase = $session->getMain()->getTranslationManager()->get(Lang::META_LANGUAGE, $session->getConfig()->lang);
		$session->sendMessage(Lang::CMDS_VERSION_RESPONSE, [
			"PLUGIN_VERSION" => $session->getMain()->getDescription()->getVersion(),
			"PLUGIN_AUTHORS" => implode(", ", $session->getMain()->getDescription()->getAuthors()),
			"LANG_NAME" => $session->translate(Lang::META_NATIVE),
			"LANG_VERSION" => $phrase->getUpdated(),
			"LANG_AUTHORS" => implode(", ", $session->translate(Lang::META_AUTHORS)),
		]);
	}
}
