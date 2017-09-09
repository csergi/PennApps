CREATE DATABASE IF NOT EXISTS pennapps;
USE pennapps;

DROP TABLE IF EXISTS posts; #so if we are changing the tables, we get the info
CREATE TABLE posts (
	id BIGINT AUTO_INCREMENT NOT NULL,
	name TEXT,
	body TEXT,
	tags TEXT, #serialized
	type INT, #0 if its a question, 1 if its answer,
	thread TEXT,
	time TIMESTAMP,
	PRIMARY KEY(id)
);
