<?php

namespace pemapmodder\worldeditart;


use pocketmine\plugin\PluginBase;

class WorldEditArt extends PluginBase{
	public function onEnable(){
		$this->saveDefaultConfig();
	}
}
