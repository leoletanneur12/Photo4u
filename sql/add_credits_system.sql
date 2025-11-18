-- ============================================
-- Script d'ajout du système de crédits
-- À exécuter dans phpMyAdmin
-- ============================================

USE photo4u;

-- 1. Ajouter la colonne credits dans la table users
ALTER TABLE users 
ADD COLUMN credits DECIMAL(10, 2) DEFAULT 0.00 AFTER role;

-- 2. Créer la table pour l'historique des crédits
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

-- 3. Donner des crédits de démo (compatible avec safe mode)
UPDATE users 
SET credits = CASE 
    WHEN role = 'client' THEN 50.00
    WHEN role = 'photographe' THEN 0.00
    WHEN role = 'admin' THEN 100.00
    ELSE 0.00
END
WHERE id > 0;

-- 4. Vérification (facultatif)
SELECT username, role, credits FROM users ORDER BY role;
