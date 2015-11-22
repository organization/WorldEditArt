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

namespace pemapmodder\worldeditart\session\queue;

use pemapmodder\worldeditart\lang\Lang;
use pemapmodder\worldeditart\session\WorldEditSession;

class Queue{
	/** @var WorldEditSession */
	private $owner;
	/** @var Rheostat[] */
	private $future = [];
	/**
	 * tick():<br>
	 * When current is null, this means that there is nothing to be done. tick() can return right away.
	 * When current is not null and the rheostat is set to backwards direction and it is not undone(), it should
	 * continue to be undone.<br> When current is not null and the rheostat is set to backwards direction and it is
	 * undone() and the first rheostat in future is set to backwards direction, the queue should be stopped.<br> When
	 * current is not null and the rheostat is set to backwards direction and it is undone() and the first rheostat in
	 * future is set to forwards direction, current should be replaced by the rheostat shifted from future.<br> When
	 * current is not null and the rheostat is set to forwards direction and it is not completed(), it should continue
	 * to be completed.<br> When current is not null and the rheostat is set to forwards direction and it is
	 * completed(), it should be immediately shifted to history and an attempt should be made to shift a rheostat from
	 * future.<br>
	 * <hr>
	 * undo():<br>
	 * When current is not null and the rheostat is set to backwards direction and it is not undone(), return right
	 * away.<br> When current is not null and the rheostat is set to backwards direction and it is undone() and history
	 * is not empty, shift to future and shift a rheostat from history.<br> When current is not null and the rheostat
	 * is set to backwards direction and it is undone() and history is empty, return right away.<br> When current is
	 * not null and the rheostat is set to forwards direction, change the direction to backwards.<br> When current is
	 * null, attempt to shift a rheostat from history and change its direction to backwards.
	 * <hr>
	 * redo():<br>
	 * When current is not null and the rheostat is set to backwards direction, change its direction to forwards.<br>
	 * Otherwise, return right away.
	 * <hr>
	 * addTask():<br>
	 * When current is null, set current as new rheostat.<br>
	 * When current is not null and the rheostat is set to forwards direction, push to future.<br>
	 * When current is not null and the rheostat is set to backwards direction and it is not undone(), clear future and
	 * push to future.<br> When current is not null and the rheostat is set to backwards direction and it is undone(),
	 * set current as new rheostat.
	 *
	 * @var Rheostat|null $current
	 */
	private $current = null;
	/** @var Rheostat[] */
	private $history = [];

	public function __construct(WorldEditSession $owner){
		$this->owner = $owner;
	}

	public function addTask(Rheostat $rheostat){
		if($this->current === null){
			$this->current = $rheostat;
			return;
		}
		if($this->current->getSlideDirection() === Rheostat::DIRECTION_FORWARDS){
			$this->future[] = $rheostat;
			return;
		}
		if($this->current->undone()){
			$this->current = $rheostat;
			return;
		}
		$this->future = [$rheostat];
	}
	public function tick(){
		if($this->current === null){
			return;
		}
		$this->current->slide();
		if($this->current->getSlideDirection() === Rheostat::DIRECTION_BACKWARDS){
			if(!$this->current->undone()){
				return;
			}
			if(count($this->future) > 0){
				if($this->future[0]->getSlideDirection() === Rheostat::DIRECTION_BACKWARDS){
					return;
				}
				$this->futureToCurrent();
			}
			return;
		}
		if($this->current->completed()){
			$this->currentToHistory();
			if(count($this->future) > 0){
				$this->futureToCurrent();
			}
		}
	}
	public function undo(){
		if($this->current === null){
			if(count($this->history) > 0){
				$this->historyToCurrent();
				$this->current->setSlideDirection(Rheostat::DIRECTION_BACKWARDS);
			}
			return;
		}
		if($this->current->getSlideDirection() === Rheostat::DIRECTION_BACKWARDS){
			if($this->current->undone()){
				if(count($this->history) > 0){
					$this->currentToFuture();
					$this->historyToCurrent();
				}
			}
			return;
		}
		$this->current->setSlideDirection(Rheostat::DIRECTION_BACKWARDS);
	}
	public function redo(){
		if($this->current !== null and $this->current->getSlideDirection() === Rheostat::DIRECTION_BACKWARDS){
			$this->current->setSlideDirection(Rheostat::DIRECTION_FORWARDS);
		}
	}

	private function currentToHistory(){
		array_unshift($this->history, $this->current);
		$this->current = null;
	}
	private function currentToFuture(){
		array_unshift($this->future, $this->current);
		$this->current = null;
	}
	private function historyToCurrent(){
		$this->current = array_shift($this->history);
	}
	private function futureToCurrent(){
		$this->current = array_shift($this->future);
	}

	public function tip(){
		$tip = $this->owner->translate(Lang::QUEUE_TIP_TITLE) . "\n";
		if($this->current->getBlocksOutOfBounds() > 0){
			$tip .= $this->owner->translate(Lang::QUEUE_TIP_WARNED_ENTRY, [
					"TASK_NAME" => $this->current->name(),
					"PROGRESS_PERC" => round($this->current->done() / $this->current->total() * 100, 1),
					"WARNINGS_CNT" => $this->current->getBlocksOutOfBounds(),
			]);
		}else{
			$tip .= $this->owner->translate(Lang::QUEUE_TIP_ENTRY, [
					"TASK_NAME" => $this->current->name(),
					"PROGRESS_PERC" => round($this->current->done() / $this->current->total() * 100, 1),
			]);
		}
		foreach($this->future as $rheostat){
			if($rheostat->getBlocksOutOfBounds() > 0){
				$tip .= $this->owner->translate(Lang::QUEUE_TIP_WARNED_ENTRY, [
						"TASK_NAME" => $rheostat->name(),
						"PROGRESS_PERC" => round($rheostat->done() / $rheostat->total() * 100, 1),
						"WARNINGS_CNT" => $rheostat->getBlocksOutOfBounds(),
				]);
			}else{
				$tip .= $this->owner->translate(Lang::QUEUE_TIP_ENTRY, [
						"TASK_NAME" => $rheostat->name(),
						"PROGRESS_PERC" => round($rheostat->done() / $rheostat->total() * 100, 1),
				]);
			}
		}
		return trim($tip);
	}

	/**
	 * @return WorldEditSession
	 */
	public function getOwner(){
		return $this->owner;
	}
}
