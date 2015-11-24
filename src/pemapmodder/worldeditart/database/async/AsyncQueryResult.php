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

namespace pemapmodder\worldeditart\database\async;

class AsyncQueryResult{
	const TYPE_ERROR = 0;
	const TYPE_MESSAGE = 1;
	const TYPE_NIL = 2;
	const TYPE_INSERT = 3;
	const TYPE_ASSOC = 4;
	const TYPE_ALL = 5;

	/** @var int */
	public $type;
	/** @var \Exception */
	public $error;
	/** @var string */
	public $message;
	/** @var string[] */
	public $params;
	/** @var int */
	public $insert;
	/** @var array */
	public $assoc;
	/** @var array[] */
	public $all;

	/**
	 * @param \Exception $e
	 *
	 * @return AsyncQueryResult
	 */
	public static function exception(\Exception $e){
		$me = new self(self::TYPE_ERROR);
		$me->error = $e;
		return $me;
	}

	/**
	 * @param string   $message
	 * @param string[] $params
	 *
	 * @return AsyncQueryResult
	 */
	public static function message($message, array $params = []){
		$me = new self(self::TYPE_MESSAGE);
		$me->message = $message;
		$me->params = $params;
		return $me;
	}

	/**
	 * @return AsyncQueryResult
	 */
	public static function nil(){
		return new self(self::TYPE_NIL);
	}

	/**
	 * @param int $insertId
	 *
	 * @return AsyncQueryResult
	 */
	public static function insert($insertId){
		$me = new self(self::TYPE_INSERT);
		$me->insert = $insertId;
		return $me;
	}

	/**
	 * @param array $row
	 *
	 * @return AsyncQueryResult
	 */
	public static function assoc($row){
		$me = new self(self::TYPE_ASSOC);
		$me->assoc = $row;
		return $me;
	}

	/**
	 * @param array[] $rows
	 *
	 * @return AsyncQueryResult
	 */
	public static function all($rows){
		$me = new self(self::TYPE_ASSOC);
		$me->all = $rows;
		return $me;
	}

	private function __construct($type){
		$this->type = $type;
	}
}
