CREATE DATABASE IF NOT EXISTS PennApps;
USE PennApps;

DROP TABLE IF EXISTS posts; #so if we are changing the tables, we get the info
CREATE TABLE posts (
	id BIGINT NOT NULL,
	name TEXT,
	title TEXT,
	body TEXT,
	tags TEXT, #serialized
	type INT, #0 if its a question, 1 if its answer,
	thread TEXT,
	views INT DEFAULT 0,
	upvotes INT DEFAULT 0,
	downvotes INT DEFAULT 0,
	time TIMESTAMP,
	PRIMARY KEY(id)
);
