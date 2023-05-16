CREATE DATABASE IF NOT EXISTS posledne;
USE posledne;

CREATE TABLE IF NOT EXISTS answer (
    answer_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    teacher_id INT DEFAULT NULL,
    question_id INT NOT NULL,
    date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    answer mediumtext,
    poINTs INT DEFAULT NULL,
    submitted tinyINT NOT NULL DEFAULT '0'
);

CREATE TABLE latex (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    name varchar(64) NOT NULL,
    latex mediumtext NOT NULL,
    enabled tinyINT(1) NOT NULL,
    max_poINTs INT NOT NULL
);

CREATE TABLE question (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name varchar(64) NOT NULL,
    question mediumtext NOT NULL,
    solution mediumtext NOT NULL,
    file_name varchar(128) NOT NULL,
    latex_id int NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    firstname VARCHAR(50),
    surname VARCHAR(50),
    password VARCHAR(255),
    role VARCHAR(50),
    lan VARCHAR(50)
);

ALTER TABLE question ADD CONSTRAINT question_ibfk_1 FOREIGN KEY (latex_id) REFERENCES latex (id) ON DELETE CASCADE ON UPDATE RESTRICT;

INSERT INTO users (username, firstname, surname, password, role, lan) VALUES ("student", "Jozko", "Mrkvicka", "$argon2id$v=19$m=65536,t=4,p=1$Q1FnaEcxQnpaLlNOb1VZSA$SdqhiC84kyMe4oHDm0PLlyeocehVpxxP0yQwG7Hw64k", "student", "slovak");