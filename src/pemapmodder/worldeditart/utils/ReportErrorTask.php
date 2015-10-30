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

namespace pemapmodder\worldeditart\utils;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Utils;

class ReportErrorTask extends AsyncTask{
	private $url;
	private $extraHeaders = [];
	public function __construct($url, $extraHeaders = []){
		$this->url = $url;
		$this->extraHeaders = $extraHeaders;
	}
	public function onRun(){
		Utils::getURL($this->url, 30, $this->extraHeaders);
	}
	public static function fromArgs($url, array $args){
		$url .= "?";
		foreach($args as $k => $v){
			$url .= urlencode($k) . "=" . urlencode($v) . "&";
		}
		return new ReportErrorTask(substr($url, -1));
	}
}
