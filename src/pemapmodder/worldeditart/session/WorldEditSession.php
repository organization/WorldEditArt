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

namespace pemapmodder\worldeditart\session;

use pemapmodder\worldeditart\libworldedit\space\Space;
use pemapmodder\worldeditart\session\clips\Clipboard;
use pemapmodder\worldeditart\session\queue\Queue;
use pemapmodder\worldeditart\WorldEditArt;
use pocketmine\level\Location;
use pocketmine\permission\Permission;

/**
 * Represents a user of WorldEditArt
 */
abstract class WorldEditSession{
	/** @var bool */
	private $valid = false, $closed = false;

	/** @var UserConfiguration */
	private $config;
	/** @var bool */
	private $sudoModeOn;
	/** @var Space[] */
	private $selections = [];
	/** @var Bookmark[] */
	private $bookmarks = [];
	/** @var Clipboard */
	private $clipboard;
	/** @var Queue */
	private $queue;

	public function __construct(){
		$this->getMain()->getDataProvider()->loadSession($this);
//		$config = new UserConfiguration;
//		$config->lang = "en";
//		$config->safeModeOn = false;
//		$config->sudoModeRequired = false;
//		$this->init($config);
	}

	/**
	 * This method is separated from the constructor.
	 * It shall only be called by data providers that fetched data (configuration of the user)
	 * and pass the result back to the user.<br>
	 * WARNING: This method may not be called immediately inside the constructor call
	 * (e.g. if the data providers execute asynchronously).
	 * Hence, please call the {@link #isValid} method before trying to access any properties in
	 * this class. {@link \RuntimeException}s may be thrown for illegal state.
	 *
	 * @param UserConfiguration $config
	 */
	public function init(UserConfiguration $config){
		$this->valid = true;
		$this->config = $config;
		$this->sudoModeOn = !$config->sudoModeRequired;
		// if sudo mode is not required, the player should have sudo mode automatically turned on
		// in such way, he doesn't need to turn on sudo mode with a command.
		// on the other hand, if the user has sudoModeRequired,
		// he should have sudo mode turned off when he starts, so he has to explicitly turn it on.
	}

	/**
	 * Finalizes the session.
	 * This includes the saving data.
	 */
	public function close(){
		$this->valid = false;
		// TODO save session data
		$this->closed = true;
	}

	/**
	 * Returns the type name of the session.<br>
	 * A good practice is to prefix the type with a plugin name, such as "worldeditart.player"
	 *
	 * @return string
	 */
	public abstract function getType();

	/**
	 * Returns the name of the user.<br>
	 * This name has to be unique among users of the same {@link #getType type}, such that
	 * the data providers can connect users to their configuration files by a type+name match directly.
	 *
	 * @return string
	 */
	public abstract function getName();

	public function getUniqueName(){
		return $this->getType() . "/" . $this->getName();
	}

	/**
	 * Returns the position, world and the rotation of the user
	 *
	 * @return Location
	 */
	public abstract function getLocation();

	/**
	 * @param Permission|string $permission
	 *
	 * @return bool
	 */
	public abstract function hasPermission($permission);

	public abstract function sendMessageDirect($text);

	/**
	 * @return WorldEditArt
	 */
	public abstract function getMain();

	public function getConfig(){
		return $this->config;
	}

	/**
	 * Returns the selection made by the user with the name <code>$name</code>, or <code>null</code> if nonexistent.<br>
	 * If no parameters are provided, the function will return the selections in the following priority list:
	 * <ol>
	 *      <li>The selection named "default"</li>
	 *      <li>The only selection in the list of selections</li>
	 *      <li>A random (usually the first) selection created</li>
	 *      <li><code>null</code>, if the array is empty
	 * </ol>
	 *
	 * @param string|null $name
	 *
	 * @return Space|null
	 */
	public function getSelection($name = null){
		if($name === null){
			if(isset($this->selections["default"])){
				return $this->selections["default"];
			}
			if(count($this->selections) >= 1){
				return array_values($this->selections)[0];
			}
			return null;
		}
		return isset($this->selections[$name]) ? $this->selections[$name] : null;
	}
	/**
	 * Gets an array of the selections the user has made.<br>
	 *
	 * @return Space[]
	 */
	public function getSelections(){
		return $this->selections;
	}
	/**
	 * @param Space[] $selections
	 */
	public function setSelections($selections){
		$this->selections = $selections;
	}
	public function setSelection(Space $sel, $name = "default"){
		$this->selections[$name] = $sel;
	}

	/**
	 * Adds the given <code>$sel</code> into the array of selections.
	 *
	 * @param Space  $sel
	 * @param string $name default "default"
	 *
	 * @return bool
	 */
	public function addSelection(Space $sel, $name = "default"){
		$hadExisted = isset($this->selections[$name]);
		$this->selections[$name] = $sel;
		return $hadExisted;
	}

	/**
	 * Returns the bookmark made by the user with the name <code>$name</code>, or <code>null</code> if nonexistent.<br>
	 * If no parameters are provided, the function will return the bookmarks in the following priority list:
	 * <ol>
	 *      <li>The bookmark named "default"</li>
	 *      <li>The only bookmark in the list of bookmarks</li>
	 *      <li>A random (usually the first) bookmark created</li>
	 *      <li><code>null</code>, if the array is empty
	 * </ol>
	 *
	 * @param string|null $name
	 *
	 * @return Bookmark|null
	 */
	public function getBookmark($name = null){
		if($name === null){
			if(isset($this->bookmarks["default"])){
				return $this->bookmarks["default"];
			}
			if(count($this->bookmarks) >= 1){
				return array_values($this->bookmarks)[0];
			}
			return null;
		}
		return isset($this->bookmarks[$name]) ? $this->bookmarks[$name] : null;
	}
	/**
	 * Gets an array of the bookmarks the user has made.<br>
	 *
	 * @return Bookmark[]
	 */
	public function getBookmarks(){
		return $this->bookmarks;
	}
	/**
	 * @param Bookmark[] $bookmarks
	 */
	public function setBookmarks($bookmarks){
		$this->bookmarks = $bookmarks;
	}
	/**
	 * Adds the given <code>$pos</code> into the array of bookmarks.
	 *
	 * @param Bookmark $pos
	 * @param string   $name default "default"
	 *
	 * @return bool
	 */
	public function addBookmark(Bookmark $pos, $name = "default"){
		$hadExisted = isset($this->bookmarks[$name]);
		$this->bookmarks[$name] = $pos;
		return $hadExisted;
	}

	/**
	 * Returns the {@link Queue} for this session
	 *
	 * @return Queue
	 */
	public function getQueue(){
		return $this->queue;
	}

	/**
	 * Returns whether the user is valid.<br>
	 * A valid user has all properties defined
	 *
	 * @return boolean
	 */
	public function isValid(){
		return $this->valid;
	}

	/**
	 * Returns whether this session has been closed.
	 *
	 * @return bool
	 */
	public function isClosed(){
		return $this->closed;
	}

	/**
	 * Sends a message to the user represented by this session.<br>
	 * If <code>$text</code> starts with <code>%raw%</code>, a message will be sent literally to the user
	 * from the 6th (offset 5) character on.<br>
	 * Otherwise, <code>$text</code> will be regarded as a translation string ID and translation will be attempted.
	 *
	 * @param string   $text
	 * @param string[] $params default <code>[]</code> (empty array)
	 */
	public final function sendMessage($text, $params = []){
		if(substr($text, 0, 5) === "%raw%"){
			$this->sendMessageDirect(substr($text, 5));
		}else{
			$this->sendMessageDirect($this->translate($text, $params));
		}
	}

	/**
	 * @param string   $text
	 * @param string[] $params default <code>[]</code> (empty array)
	 *
	 * @return string|string[]
	 */
	public final function translate($text, $params = []){
		$phrase = $this->getMain()->getTranslationManager()->get($text, $this->config->lang);
		if(is_array($phrase->getValue())){
			return $phrase->getValue();
		}
		return $phrase->format($params);
	}

	/**
	 * @return Clipboard
	 */
	public function getClipboard(){
		return $this->clipboard;
	}
}
