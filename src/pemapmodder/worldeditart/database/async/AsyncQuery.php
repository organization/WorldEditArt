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

use Exception;
use mysqli;
use pocketmine\scheduler\AsyncTask;
use RuntimeException;

abstract class AsyncQuery extends AsyncTask{
	const MYSQL_NAME = "pemapmodder.worldeditart.database.async.mysql";

	const RESULT_NIL = 0;
	const RESULT_INSERT = 1;
	const RESULT_ASSOC = 0x10;
	const RESULT_ALL = 0x11;

	const COLUMN_STRING = "";
	const COLUMN_INT = 0;
	const COLUMN_FLOAT = 0.0;
	const COLUMN_BOOLEAN = 0;

	/** @var array */
	private $connDetails;

	public function __construct($connDetails){
		$this->connDetails = $connDetails;
		if($this->getResultType() & 0x10){
			if($this->getExpectedColumns() === null){
				throw new RuntimeException("Query " . get_class($this) . " declared with result type {$this->getResultType()} but didn't implement getExpectedColumns() properly!");
			}
		}
	}

	public function onRun(){
		$db = $this->getDb();
		try{
			$this->preQuery($db);
			$result = $db->query($query = $this->query());
			$this->postQuery($db);
			if($result === false){
				throw new AsyncQueryMysqlError(get_class($this), $query, $db->error);
			}
			$type = $this->getResultType();
			if($type === self::RESULT_INSERT){
				if($result instanceof \mysqli_result){
					$result->close();
				}
				$this->setResult(AsyncQueryResult::insert($db->insert_id));
				return;
			}
			if($type === self::RESULT_NIL){
				if($result instanceof \mysqli_result){
					$result->close();
				}
				$this->setResult(AsyncQueryResult::nil());
				return;
			}
			if($type !== self::RESULT_ASSOC and $type !== self::RESULT_ALL){
				throw new RuntimeException("Unknown result type: $type");
			}
//			assert($result instanceof \mysqli_result);
			if($type === self::RESULT_ASSOC){
				$row = $result->fetch_assoc();
				if(is_array($row)){
					$this->preprocessRow($row);
				}
				$result->close();
				$this->setResult(AsyncQueryResult::assoc($row));
				return;
			}
			$rows = [];
			while(is_array($row = $result->fetch_assoc())){
				$this->preprocessRow($row);
				$rows[] = $row;
			}
			$result->close();
			$this->setResult(AsyncQueryResult::all($rows));
		}catch(Exception $e){
			$this->onError($e);
		}
	}
	/**
	 * @return mysqli
	 */
	public function getDb(){
		$db = $this->getFromThreadStore(self::MYSQL_NAME);
		if(!($db instanceof mysqli)){
			$db = new mysqli($this->connDetails["host"], $this->connDetails["username"], $this->connDetails["password"], $this->connDetails["schema"], $this->connDetails["port"]);
			if($db->connect_error){
				throw new RuntimeException("Could not connect to MySQL: " . $db->connect_error);
			}
			$this->saveToThreadStore(self::MYSQL_NAME, $db);
		}
		return $db;
	}

	/**
	 * Executes whatever code required before running the query from {@link #query}
	 *
	 * @param mysqli $db
	 *
	 * @throws AsyncQueryTerminator throw an {@link AsyncQueryTerminator} to gracefully stop the query and send a
	 *                              message to the user. This will cause the query result to become a {@link
	 *                              AsyncQueryResult#TYPE_MESSAGE MESSAGE} result.
	 */
	protected function preQuery(mysqli $db){
	}
	/**
	 * Returns the query string.
	 *
	 * @return string
	 */
	protected abstract function query();
	/**
	 * Executes whatever code required after running the query from {@link #query}
	 *
	 * @param mysqli $db
	 *
	 * @throws AsyncQueryTerminator throw an {@link AsyncQueryTerminator} to gracefully stop the query processing and
	 *                              send a message to the user. This will cause the query result to become a {@link
	 *                              AsyncQueryResult#TYPE_MESSAGE MESSAGE} result.
	 */
	protected function postQuery(mysqli $db){
	}
	/**
	 * Indicates the expected query result type. Accepted constants:
	 * - {@link AsyncQuery#RESULT_NIL}: no results are expected.
	 * - {@link AsyncQuery#RESULT_INSERT}: the result is expected to contain an integer that specifies the {@link
	 * mysqli#insert_id insert ID} of the query (if existent)
	 * - {@link AsyncQuery#RESULT_ASSOC}: one row
	 *
	 * @return int
	 */
	protected abstract function getResultType();
	protected function getExpectedColumns(){
		return null;
	}
	protected function onError(Exception $e){
		$this->setResult($e instanceof AsyncQueryTerminator ? AsyncQueryResult::message($e->getMessage()) : AsyncQueryResult::exception($e));
	}

	protected function esc($string){
		return is_string($string) ? ("'" . $this->getDb()->escape_string($string) . "'") : (string) $string;
	}
	protected function preprocessRow(&$row){
		foreach($this->getExpectedColumns() as $column => $type){
			if(!isset($row[$column])){
				$row[$column] = $type;
			}elseif(is_string($column)){
				if($type === self::COLUMN_BOOLEAN){
					$row[$column] = $row[$column] === "1";
				}elseif($type === self::COLUMN_INT){
					$row[$column] = (int) $row[$column];
				}elseif($type === self::COLUMN_FLOAT){
					$row[$column] = (float) $row[$column];
				}
			}
		}
	}
}
