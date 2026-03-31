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
('Exportations militaires : Le succes des drones Shahed', 
 'exportations-militaires-le-succes-des-drones-shahed', 
 'De la Russie à lAfrique, lindustrie de larmement iranienne simpose comme un acteur majeur des conflits asymétriques modernes.', 
 '<h2>Une technologie low-cost mais redoutable</h2>
<p>Les drones de la famille Shahed ont prouv&eacute; leur efficacit&eacute; en saturant les d&eacute;fenses antia&eacute;riennes les plus co&ucirc;teuses, offrant un rapport qualit&eacute;-prix imbattable pour de nombreux pays.</p>
<p><img src="../../assets/uploads/img_69cb8c8c1c5500.70414503.jpg" alt="drones-shahed" width="1500" height="1000"></p>
<h3>Une autonomie industrielle compl&egrave;te</h3>
<p>Malgr&eacute; des d&eacute;cennies dembargo, lIran a r&eacute;ussi &agrave; cr&eacute;er une cha&icirc;ne de production nationale capable de produire des milliers dunit&eacute;s par an sans d&eacute;pendre de composants occidentaux majeurs.</p>');

INSERT INTO images (article_id, path, alt) VALUES 
(1, 'img_69cb8c8c1c5500.70414503.jpg', 'drones-shahed');