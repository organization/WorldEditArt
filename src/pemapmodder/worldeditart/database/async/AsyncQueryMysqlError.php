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

class AsyncQueryMysqlError extends \RuntimeException{
	/**
	 * AsyncQueryMysqlError constructor.
	 *
	 * @param string $class
	 * @param string $query
	 * @param string $error
	 */
	public function __construct($class, $query, $error){
		parent::__construct("Error in query $class: $error - Query: $query");
	}
}
