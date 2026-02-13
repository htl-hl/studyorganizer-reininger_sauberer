DROP DATABASE IF EXISTS STUDYORGANIZER;
CREATE DATABASE STUDYORGANIZER;
USE STUDYORGANIZER;


CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL
);

CREATE TABLE Subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    status boolean NOT NULL
);

CREATE TABLE Teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    status boolean NOT NULL
);

CREATE TABLE Subject_Has_Teacher (
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    PRIMARY KEY (subject_id, teacher_id),
    FOREIGN KEY (subject_id) REFERENCES Subjects(id),
    FOREIGN KEY (teacher_id) REFERENCES Teachers(id)
);

CREATE TABLE Homework (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    due_date DATE,
    status VARCHAR(20),
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (subject_id) REFERENCES Subjects(id)
);
