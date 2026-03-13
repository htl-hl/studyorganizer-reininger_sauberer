USE STUDYORGANIZER;

-- 1. Fächer (Subjects)
INSERT INTO Subjects (name, status) VALUES
                                        ('Mathematik', 1),
                                        ('Deutsch', 1),
                                        ('Englisch', 1),
                                        ('Informatik', 1),
                                        ('Physik', 1),
                                        ('Geschichte', 0);

-- 2. Lehrer (Teachers)
INSERT INTO Teachers (firstname, lastname, subject_id, status) VALUES
                                                                   ('Max', 'Mustermann', NULL, 1),
                                                                   ('Erika', 'Schmidt', NULL, 1),
                                                                   ('Christian', 'Weber', NULL, 1),
                                                                   ('Susanne', 'Wagner', NULL, 1);

-- 3. Verknüpfung Lehrer <-> Fächer (Subject_Has_Teacher)
INSERT INTO Subject_Has_Teacher (teacher_id, subject_id) VALUES
                                                             (1, 1), (1, 5), -- Mustermann: Mathe, Physik
                                                             (2, 2), (2, 3), -- Schmidt: Deutsch, Englisch
                                                             (3, 4),         -- Weber: Informatik
                                                             (4, 1), (4, 4); -- Wagner: Mathe, Informatik

-- 4. Test-User
INSERT INTO Users (firstname, lastname, username, password, role, authKey)
VALUES ('Max', 'Schüler', 'user1', '$2y$12$.tvio0XGMEiZtVV8IEEC0.VSIaaubjuvpvvXbNyJU0BHc4t1REOzS', 'user', 'user_auth_key');

-- 5. Hausaufgaben (Homework)
INSERT INTO Homework (title, description, due_date, status, user_id, subject_id, teacher_id) VALUES
                                                                                                 ('Mathe S. 44', 'Aufgaben 1 bis 5 im Buch.', '2026-03-20', 'Open', 2, 1, 1),
                                                                                                 ('Vokabeln Unit 3', 'Alle Vokabeln der Unit 3 lernen.', '2026-03-15', 'Open', 2, 3, 2),
                                                                                                 ('Physik Experiment', 'Protokoll zum Versuch.', '2026-03-05', 'Finished', 2, 5, 1),
                                                                                                 ('Aufsatz Analyse', 'Interpretation von Faust.', '2026-02-28', 'Finished', 2, 2, 2);