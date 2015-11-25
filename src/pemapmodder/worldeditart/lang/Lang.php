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
	const ERR_OFFLINE = "error.offline";
	const VERSION_DESCRIPTION = "version.description";
	const VERSION_USAGE = "version.usage";
	const VERSION_RESPONSE = "version.response";
	const SELECTION_POS_DESCRIPTION = "selection.pos.description";
	const SELECTION_POS_USAGE_1 = "selection.pos.usage.1";
	const SELECTION_POS_USAGE_2 = "selection.pos.usage.2";
	const SELECTION_POS_SUCCESS = "selection.pos.success";
	const SELECTION_POS_INFO = "selection.pos.info";
	const SELECTION_CYLINDER_POS_DESCRIPTION = "selection.cylPos.description";
	const SELECTION_CYLINDER_POS_USAGE_1 = "selection.cylPos.usage.1";
	const SELECTION_CYLINDER_POS_USAGE_2 = "selection.cylPos.usage.2";
	const SELECTION_CYLINDER_POS_SUCCESS = "selection.cylPos.success";
	const SPACE_CUBOID_TO_STRING = "space.cuboid.toString";
	const SPACE_CYLINDER_TO_STRING = "space.cylinder.toString";
	const SPACE_SPHERE_TO_STRING = "space.sphere.toString";
	const QUEUE_TIP_TITLE = "queue.tip.title";
	const QUEUE_TIP_ENTRY = "queue.tip.entry";
	const QUEUE_TIP_WARNED_ENTRY = "queue.tip.warnedEntry";

	const PHRASE_TOP_CENTER = "phrase.topCenter";
	const PHRASE_BASE_CENTER = "phrase.baseCenter";
}
