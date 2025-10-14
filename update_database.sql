-- Mise à jour de la base de données pour les catégories personnalisées
USE photo4u;

-- Table des catégories personnalisées
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photographer_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'bi-folder',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (photographer_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_category_per_photographer (photographer_id, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modifier la table photos pour supporter les catégories personnalisées
ALTER TABLE photos 
    MODIFY category VARCHAR(100) NOT NULL,
    ADD COLUMN category_id INT NULL AFTER category,
    ADD FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL;

-- Insérer des catégories par défaut pour le photographe de démonstration (ID 2)
INSERT INTO categories (photographer_id, name, description, icon) VALUES
(2, 'Paysages', 'Photos de paysages naturels et urbains', 'bi-image'),
(2, 'Portraits', 'Portraits professionnels et artistiques', 'bi-person-circle'),
(2, 'Événements', 'Mariages, anniversaires, événements professionnels', 'bi-calendar-event'),
(2, 'Architecture', 'Photos d\'architecture et de bâtiments', 'bi-building'),
(2, 'Nature', 'Faune, flore et macrophotographie', 'bi-tree');

-- Mettre à jour les photos existantes avec les nouvelles catégories
UPDATE photos SET category_id = (SELECT id FROM categories WHERE name = 'Paysages' AND photographer_id = 2 LIMIT 1) WHERE category = 'paysage';
UPDATE photos SET category_id = (SELECT id FROM categories WHERE name = 'Portraits' AND photographer_id = 2 LIMIT 1) WHERE category = 'portrait';
UPDATE photos SET category_id = (SELECT id FROM categories WHERE name = 'Événements' AND photographer_id = 2 LIMIT 1) WHERE category = 'evenement';
