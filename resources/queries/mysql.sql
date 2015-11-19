-- `a_b` means that the column is `b` of `a` (like $a->b in PHP)
-- `aB` means that the column is about `a b`.

CREATE TABLE IF NOT EXISTS wea_users (
	user_type VARCHAR(127),
	user_name VARCHAR(127),
	wand_id INT UNSIGNED,
	wand_damage INT,
	jump_id INT UNSIGNED,
	jump_damage INT,
	safety_safeMode TINYINT,
	safety_sudoRequired TINYINT,
	safety_defaultSudoSession INT,
	opti_maxUndoQueue SMALLINT,
	opti_tickEditThreshold SMALLINT,
	PRIMARY KEY(user_type, user_name)
);

CREATE TABLE IF NOT EXISTS wea_zones (
	id INT PRIMARY KEY AUTO_INCREMENT,
	type INT,
	space VARCHAR(16383),
	INDEX type (type)
);

CREATE TABLE IF NOT EXISTS wea_sels (
	user_type VARCHAR(127),
	user_name VARCHAR(127),
	sel_name VARCHAR(127),
	space VARCHAR(16383),
	PRIMARY KEY (user_type, user_name, sel_name)
);
