-- Tasks for Today (TSA2): database export. Import into an empty database named tasks_for_today.
-- Includes the TSA2 changes: users.password (hashed) and tasks.is_archived (soft delete).
-- Demo login: username jdelacruz, password password123

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'pending',
  task_date DATE NOT NULL,
  created_at DATETIME NOT NULL,
  is_archived TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) DEFAULT NULL,
  created_at DATETIME NOT NULL
);

-- Dates are relative to the day you import, so "today" always has tasks.
INSERT INTO tasks (title, status, task_date, created_at) VALUES
('Submit weekly progress report',  'completed',   CURDATE() - INTERVAL 1 DAY, NOW()),
('Review pull requests',           'completed',   CURDATE() - INTERVAL 1 DAY, NOW()),
('Team stand-up meeting',          'completed',   CURDATE(), NOW()),
('Fix login page bug',             'in_progress', CURDATE(), NOW()),
('Update project documentation',   'pending',     CURDATE(), NOW()),
('Email client about deployment',  'pending',     CURDATE(), NOW()),
('Prepare sprint demo slides',     'pending',     CURDATE() + INTERVAL 1 DAY, NOW()),
('Database backup check',          'pending',     CURDATE() + INTERVAL 1 DAY, NOW()),
('Deploy version 1.2 to production','pending',    CURDATE() + INTERVAL 2 DAY, NOW());

-- The password column holds a bcrypt hash, never the plain password.
INSERT INTO users (username, full_name, email, password, created_at) VALUES
('jdelacruz', 'Juan Dela Cruz', 'juan.delacruz@example.com', '$2y$10$c4QCKmJLftDiJfFCah7fqOV3/Y7FHnX7tASCvrcTpqkD5FLR3jiZu', NOW());
