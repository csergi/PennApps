CREATE TABLE posts(
	id BIGINT AUTO_INCREMENT NOT NULL,
	name TEXT,
	body TEXT,
	tags TEXT, --serialized
	type INT, --0 if its a question, 1 if its answer,
	thread BIGINT,
	time TIMESTAMP,
	PRIMARY KEY(id)
);
