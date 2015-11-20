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

require_once __DIR__ . "/item-names.php";

function getOS(){
	$uname = php_uname("s");
	if(stripos($uname, "Darwin") !== false){
		if(strpos(php_uname("m"), "iP") === 0){
			$os = "ios";
		}else{
			$os = "mac";
		}
	}elseif(stripos($uname, "Win") !== false or $uname === "Msys"){
		$os = "win";
	}elseif(stripos($uname, "Linux") !== false){
		/** @noinspection PhpUsageOfSilenceOperatorInspection */
		$os = @file_exists("/system/build.prop") ? "android" : "linux";
	}elseif(stripos($uname, "BSD") !== false or $uname === "DragonFly"){
		$os = "bsd";
	}else{
		$os = "other";
	}
	return $os;
}

if(!defined("STDIN")){
	define("STDIN", fopen("php://stdin", "r"));
}

function readConsole($acceptEmpty = false){
	do{
		$line = trim(fgets(STDIN));
	}while(!$acceptEmpty and $line === "");
	return $line;
}

/**
 * @param bool $default
 *
 * @return bool
 */
function queryYN($default){
	$default = (bool) $default;
	echo $default ? "(Y/n)" : "(y/N)";
	$line = strtolower(readConsole(true));
	if($line{0} === "y"){
		return true;
	}elseif($line{0} === "n"){
		return false;
	}else{
		return $default;
	}
}

function loadItem($input, &$id, &$damage){
	$damage = -1;
	if(($pos = strpos($input, ":")) !== false){
		$damage = (int) substr($input, $pos + 1);
		$input = substr($input, 0, $pos);
	}
	if(ctype_digit($input)){
		$id = (int) $input;
		return;
	}
	$item = "MC_ITEM_" . strtoupper(str_replace(" ", "_", $input));
	if(defined($item)){
		$id = (int) constant($item);
		return;
	}
	echo "[!] WARNING: Unknown item $input! Assumed as air.", PHP_EOL;
	$id = 0;
	return;
}
