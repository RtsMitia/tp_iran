CREATE DATABASE IF NOT EXISTS tp_iran;
USE tp_iran;

-- 1. Create the Articles table
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,            -- For the H1 tag
    slug VARCHAR(255) NOT NULL UNIQUE,     -- For Requirement #1 (URL Rewriting)
    excerpt VARCHAR(160) NOT NULL,         -- For Requirement #2 (Meta Description < 160c)
    content LONGTEXT NOT NULL,             -- For TinyMCE (H2, H3, etc.)
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Create the Images table
CREATE TABLE IF NOT EXISTS images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    path VARCHAR(255) NOT NULL,            -- Path to the file in /assets/uploads/
    alt VARCHAR(255) NOT NULL,             -- Requirement #5 (Alt text on images)
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Create the Users table for Admin Access
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Will store the hashed version
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Insert "Dummy" data for testing
-- The password below is 'admin123' hashed using PHP's password_hash
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$12$U3xGtM1h1Ch5mVOBuBhnCeWQo1u.uwTqxgrbqZ2v5i5/OmJwEVZvu', 'admin@example.com')
ON DUPLICATE KEY UPDATE
password = VALUES(password),
email = VALUES(email);

INSERT INTO articles (title, slug, excerpt, content) VALUES 
('The History of the Iran-Iraq War', 
 'history-iran-iraq-war', 
 'An overview of the 1980-1988 conflict between Iran and Iraq, its causes, and its lasting impact.', 
 '<h2>Introduction</h2><p>The war began in September 1980...</p><h3>Key Battles</h3><p>The Siege of Abadan was a major turning point...</p>');

INSERT INTO images (article_id, path, alt) VALUES 
(1, 'iran_war_1980.jpg', 'Soldiers in the desert during the 1980 Iran-Iraq conflict');