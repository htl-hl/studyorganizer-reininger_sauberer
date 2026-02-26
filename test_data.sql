USE STUDYORGANIZER;

-- 1. Fächer anlegen
INSERT INTO Subjects (name, status)
VALUES ('Mathematik', 1),
       ('Deutsch', 1),
       ('Englisch', 1),
       ('Informatik', 1),
       ('Physik', 1),
       ('Wirtschaft', 1);

-- 2. Lehrer anlegen
INSERT INTO Teachers (firstname, lastname, status)
VALUES ('Max', 'Mustermann', 1),   -- ID 1
       ('Erika', 'Schmidt', 1),    -- ID 2
       ('Klaus', 'Weber', 1),      -- ID 3
       ('Sabine', 'Meyer', 1),     -- ID 4
       ('Christian', 'Wagner', 1), -- ID 5
       ('Johanna', 'Lehmann', 1);
-- ID 6

-- 3. Verknüpfung: Welcher Lehrer unterrichtet welches Fach?
INSERT INTO Subject_Has_Teacher (subject_id, teacher_id)
VALUES (1, 1), -- Max unterrichtet Mathe
       (1, 2), -- Erika unterrichtet auch Mathe
       (2, 3), -- Klaus unterrichtet Deutsch
       (3, 4), -- Sabine unterrichtet Englisch
       (4, 5), -- Christian unterrichtet Informatik
       (5, 1), -- Max unterrichtet auch Physik
       (6, 6);
-- Johanna unterrichtet Wirtschaft

-- 4. (Optional) Ein paar Beispiel-Hausaufgaben für die Übersicht
-- Wichtig: Hier muss die user_id 1 existieren!
INSERT INTO Homework (title, description, due_date, status, user_id, subject_id, teacher_id)
VALUES ('Mathe Übung 1', 'S. 45 Nr. 1-5 im Buch', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Offen', 1, 1, 1),
       ('Deutsch Essay', 'Analyse der Kurzgeschichte', DATE_ADD(CURDATE(), INTERVAL 8 DAY), 'Offen', 1, 2, 3),
       ('Informatik Projekt', 'PHP Controller fertigstellen', DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'In Arbeit', 1, 4,
        5);