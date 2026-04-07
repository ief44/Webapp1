CREATE TABLE IF NOT EXISTS Admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Gerechten (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255) NOT NULL,
    beschrijving TEXT,
    prijs DECIMAL(10,2),
    type VARCHAR(255)
);

INSERT INTO Admins (username, password_hash) VALUES ('admin', '$2y$10$abcdefghijklmnopqrstuvwx') ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash);