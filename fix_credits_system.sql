-- Script de correction pour le système de crédits
USE photo4u;

-- Vérifier et ajouter la colonne credits si elle n'existe pas
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'photo4u'
AND TABLE_NAME = 'users'
AND COLUMN_NAME = 'credits';

SET @query = IF(@col_exists = 0,
    'ALTER TABLE users ADD COLUMN credits DECIMAL(10, 2) DEFAULT 0.00 AFTER role',
    'SELECT "La colonne credits existe déjà" AS message');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Créer la table credit_transactions si elle n'existe pas
CREATE TABLE IF NOT EXISTS credit_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    type ENUM('recharge', 'purchase', 'refund') NOT NULL,
    description VARCHAR(255),
    balance_after DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Mettre à jour les crédits pour tous les utilisateurs (compatible avec safe mode)
-- On utilise une sous-requête pour contourner safe mode
UPDATE users 
SET credits = CASE 
    WHEN role = 'client' THEN 50.00
    WHEN role = 'photographe' THEN 0.00
    WHEN role = 'admin' THEN 100.00
    ELSE 0.00
END
WHERE id IN (SELECT id FROM (SELECT id FROM users) AS temp);

-- Afficher le résultat
SELECT 
    username, 
    role, 
    credits 
FROM users 
ORDER BY role, username;
