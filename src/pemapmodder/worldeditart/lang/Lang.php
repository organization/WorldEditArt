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

namespace pemapmodder\worldeditart\lang;

/**
 * This file contains the string constants to known translation string IDs.
 */
interface Lang{
	const META_LANGUAGE = "meta.language";
	const META_NATIVE = "meta.native";
	const META_AUTHORS = "meta.authors";
	const ERR_NO_PERM = "error.noperm";
	const CMDS_VERSION_DESCRIPTION = "cmds.version.description";
	const CMDS_VERSION_USAGE = "cmds.version.usage";
	const CMDS_VERSION_RESPONSE = "cmds.version.response";
	const QUEUE_TIP_TITLE = "queue.tip.title";
	const QUEUE_TIP_ENTRY = "queue.tip.entry";
	const QUEUE_TIP_WARNED_ENTRY = "queue.tip.warnedEntry";
}
