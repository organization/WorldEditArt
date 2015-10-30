CREATE TABLE IF NOT EXISTS users (
	type TEXT,
	name TEXT,
	wandId INTEGER,
	wandDamage INTEGER,
	jumpId INTEGER,
	jumpDamage INTEGER,
	safeMode INTEGER,
	sudoRequired INTEGER,
	defaultSudoSession INTEGER,
	maxUndoQueue INTEGER,
	tickEditThreshold INTEGER,
	PRIMARY KEY (type, name) ON CONFLICT REPLACE
);
