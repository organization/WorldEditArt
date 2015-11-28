<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pemapmodder\worldeditart\database\stream;

use pemapmodder\worldeditart\WorldEditArt;

class QueryRequest{
	/** @var WorldEditArt */
	private $main;
	/** @var string */
	private $query;
	/** @var QueryListener|int|null */
	private $listener;

	/**
	 * QueryEntry constructor.
	 *
	 * @param WorldEditArt  $main
	 * @param               $query
	 * @param QueryListener $listener
	 */
	public function __construct(WorldEditArt $main, $query, $listener = null){
		$this->main = $main;
		$this->query = $query;
		$this->listener = $listener;
	}
	/**
	 * Returns a clone of this object that is thread-safe (can be stored as a field in a {@link Threaded}
	 *
	 * @return QueryRequest
	 */
	public function getThreadSafeClone(){
		$clone = clone $this;
		$clone->makeThreadSafe();
		return $clone;
	}
	private function makeThreadSafe(){
		if($this->listener !== null){
			$this->listener = $this->main->getObjectPool()->store($this->listener);
		}
		unset($this->main);
	}
	public function makeThreadUnsafe(WorldEditArt $main){
		$this->main = $main;
		if($this->listener !== null){
			$this->listener = $main->getObjectPool()->get($this->listener);
		}
	}

	public function getMain(){
		return $this->main;
	}
	public function getQuery(){
		return $this->query;
	}
	public function getListener(){
		return $this->listener;
	}

}
