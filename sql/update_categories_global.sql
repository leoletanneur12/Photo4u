-- Migration : Rendre les catégories globales (partagées entre tous les photographes)
USE photo4u;

-- Modifier la colonne photographer_id pour accepter NULL
ALTER TABLE categories 
MODIFY COLUMN photographer_id INT NULL;

-- Mettre à jour toutes les catégories existantes en les rendant globales (NULL = pour tous)
UPDATE categories 
SET photographer_id = NULL 
WHERE id > 0;

-- Afficher les catégories pour vérification
SELECT id, name, description, icon, photographer_id 
FROM categories 
ORDER BY name;
