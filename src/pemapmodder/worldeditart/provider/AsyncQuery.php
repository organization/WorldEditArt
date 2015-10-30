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

namespace pemapmodder\worldeditart\provider;

use Exception;
use mysqli;
use mysqli_result;
use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use ReflectionClass;

abstract class AsyncQuery extends AsyncTask{
	const OBJECT_MYSQLI_IDENTIFIER = "com.github.pemapmodder.worldeditart.async.mysqli.identifier";

	const QUERY_NEGLECT = 0;
	const QUERY_FETCH_ASSOC = 1;
	const QUERY_FETCH_ALL = 2;
	const QUERY_INSERT = 3;

	const COLUMN_BOOLEAN = 0;
	const COLUMN_STRING = 1;
	const COLUMN_INTEGER = 2;
	const COLUMN_UNIX_TIMESTAMP = 2;
	const COLUMN_FLOAT = 3;
	const COLUMN_DOUBLE = 3;
	const COLUMN_TIMESTAMP = 4;
	const COLUMN_MYSQL_TIMESTAMP = 4;

	/** @var array connection details from config */
	private $connectionDetails;

	/** @var bool */
	private $success = false;
	/** @var string|null */
	private $error = null;
	/** @var int|null */
	private $insertId = null;
	/** @var array|null */
	private $assocRow = null;
	/** @var bool */
	private $assocNoResult = false;
	/** @var array[]|null */
	private $allRows = null;

	public function __construct(array $connectionDetails){
		$this->connectionDetails = $connectionDetails;
		if($this->getResultType() === self::QUERY_FETCH_ALL or $this->getResultType() === self::QUERY_FETCH_ASSOC){
			if($this->getExpectedColumns() === null){
				throw new Exception(
					"Internal code error: " .
					get_class($this) . " indicates result type of " .
					(($this->getResultType() === self::QUERY_FETCH_ALL) ? "QUERY_FETCH_ALL" : "QUERY_FETCH_ASSOC") .
					" but does not override the getExpectedColumns method. Please report this error to the author of " .
					get_class($this)
				);
			}
		}
	}

	public final function onRun(){
		try{
			$db = $this->getDb();
			$this->onPreQuery($db);
			$result = $this->getQuery($db);
			$this->onPostQuery($db);
			if($result === false){
				throw new Exception("MySQL query error: " . $db->error);
			}
			$resultType = $this->getResultType();
			if($resultType === self::QUERY_NEGLECT){
				if($result instanceof mysqli_result){
					$result->close();
				}
				return;
			}
			if($resultType === self::QUERY_INSERT){
				if($result instanceof mysqli_result){
					$result->close();
					throw new Exception("Internal code error: QUERY_INSERT must not be used with SELECT queries");
				}
				$this->insertId = $db->insert_id;
				return;
			}
			if($resultType === self::QUERY_FETCH_ASSOC){
				if(!($result instanceof mysqli_result)){
					throw new Exception("Internal code error: QUERY_FETCH_ASSOC must be used with SELECT queries");
				}
				$this->assocRow = $result->fetch_assoc();
				if(!is_array($this->assocRow)){
					$this->assocNoResult = true;
					throw new Exception("Unexpected value error: Empty result set");
				}
				$this->processRow($this->assocRow);
			}elseif($resultType === self::QUERY_FETCH_ALL){
				if(!($result instanceof mysqli_result)){
					throw new Exception("Internal code error: QUERY_FETCH_ALL must be used with SELECT queries");
				}
				while(is_array($row = $result->fetch_assoc())){
					$this->processRow($row);
					$this->allRows[] = $row;
				}
			}
		}catch(Exception $e){
			$this->success = false;
			$this->error = $e->getMessage();
		}
	}
	public final function onCompletion(Server $server){
		$main = WorldEditArt::getInstance($server);
		try{
			$this->mainThreadProcess($main);
		}catch(Exception $e){
			$main->getLogger()->error("Error processing result of " . (new ReflectionClass($this))->getShortName() . ": " . $e->getMessage());
			$main->getLogger()->logException($e);
		}
	}
	protected final function getDb(){
		$db = $this->getFromThreadStore(self::OBJECT_MYSQLI_IDENTIFIER);
		if($db === null){
			$options = $this->connectionDetails;
			/** @noinspection PhpUsageOfSilenceOperatorInspection */
			$db = @new mysqli(
				$options["host"], $options["username"], $options["password"], $options["schema"],
				isset($options["port"]) ? $options["port"] : 3306);
			if($db->connect_error){
				throw new Exception("Connection error: " . $db->connect_error);
			}
			$this->saveToThreadStore(self::OBJECT_MYSQLI_IDENTIFIER, $db);
		}
		return $db;
	}

	protected function onPreQuery(mysqli $db){
	}
	protected abstract function getQuery(mysqli $db);
	protected function onPostQuery(mysqli $db){
	}
	/**
	 * Returns one of {@link #QUERY_NEGLECT}, {@link #QUERY_FETCH_ASSOC}, {@link #QUERY_FETCH_ALL} or {@link #QUERY_INSERT}.
	 * @return int
	 */
	protected abstract function getResultType();
	/**
	 * @return int|null
	 */
	protected function getExpectedColumns(){
		return null;
	}
	protected function processRow(array &$row){
		$expected = $this->getExpectedColumns();
		foreach($expected as $column => $type){
			if($type === self::COLUMN_BOOLEAN){
				$row[$column] = isset($row[$column]) ? (bool) $row[$column] : false;
			}elseif($type === self::COLUMN_STRING){
				$row[$column] = isset($row[$column]) ? (string) $row[$column] : "";
			}elseif($type === self::COLUMN_INTEGER){
				$row[$column] = isset($row[$column]) ? (int) $row[$column] : 0;
			}elseif($type === self::COLUMN_FLOAT){
				$row[$column] = isset($row[$column]) ? (double) $row[$column] : 0.0;
			}elseif($type === self::COLUMN_TIMESTAMP){
				$row[$column] = isset($row[$column]) ? strtotime($row[$column]) : 0;
			}
		}
		$this->onRowFetched($row);
	}
	protected function onRowFetched($row){
	}
	protected function mainThreadProcess(WorldEditArt $main){
	}

	/**
	 * @return boolean
	 */
	public function isSuccess(){
		return $this->success;
	}
	/**
	 * @return null|string
	 */
	public function getError(){
		return $this->error;
	}
	/**
	 * @return int|null
	 */
	public function getInsertId(){
		return $this->insertId;
	}
	/**
	 * @return array|null
	 */
	public function getAssocRow(){
		return $this->assocRow;
	}
	/**
	 * @return boolean
	 */
	public function isAssocNoResult(){
		return $this->assocNoResult;
	}
	/**
	 * @return \array[]|null
	 */
	public function getAllRows(){
		return $this->allRows;
	}
}
