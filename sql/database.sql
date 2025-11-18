-- Créer la base de données
CREATE DATABASE IF NOT EXISTS photo4u CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE photo4u;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'photographe', 'client') NOT NULL DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des photos
CREATE TABLE IF NOT EXISTS photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photographer_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    filename VARCHAR(255) NOT NULL,
    category ENUM('paysage', 'portrait', 'evenement') NOT NULL,
    price DECIMAL(10, 2) DEFAULT 1.50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des achats
CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    photo_id INT NOT NULL,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (photo_id) REFERENCES photos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer les utilisateurs par défaut
-- Mot de passe : admin, photo, letanneur (hashé avec password_hash)
INSERT INTO users (username, password, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@photo4u.com', 'admin'),
('photo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'photo@photo4u.com', 'photographe'),
('leo', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'leo@photo4u.com', 'client');

-- Insérer quelques photos de démonstration
INSERT INTO photos (photographer_id, title, description, filename, category, price) VALUES
(2, 'Paysage océan', 'Magnifique coucher de soleil sur l\'océan', 'paysage.jpg', 'paysage', 1.50),
(2, 'Paysage montagne', 'Vue panoramique des montagnes', 'paysage2.jpg', 'paysage', 1.50),
(2, 'Portrait noir et blanc', 'Portrait artistique en noir et blanc', 'portrait.jpg', 'portrait', 2.00),
(2, 'Portrait couleur', 'Portrait professionnel en couleur', 'portrait2.jpg', 'portrait', 2.00),
(2, 'Mariage', 'Photos de mariage romantiques', 'evenement.jpg', 'evenement', 3.00),
(2, 'Ballon d\'Or', 'Couverture événement sportif', 'evenement2.jpg', 'evenement', 3.00);
