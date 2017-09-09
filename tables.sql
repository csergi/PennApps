CREATE TABLE posts(
	id BIGINT,
	name TEXT,
	body TEXT,
	tags TEXT,
	type INT, --0 if its a question, 1 if its answer,
	thread INT,
	time TIMESTAMP,
	PRIMARY KEY(id)
);
