CREATE DATABASE taskdb;
DROP DATABASE taskdb;
CREATE DATABASE college;
USE college;
CREATE TABLE student(
	id INT PRIMARY KEY,
    name VARCHAR(50),
    age INT NOT NULL
);
INSERT INTO student VALUES(1, "Pritom", 21);
INSERT INTO student VALUES(2,"Shefa",22);
SELECT * FROM student;x