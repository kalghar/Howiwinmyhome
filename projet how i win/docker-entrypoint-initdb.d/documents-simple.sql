-- ========================================
-- DOCUMENTS SIMPLIFIÉS - BASE DE DONNÉES
-- ========================================

-- Table simple pour les documents
CREATE TABLE IF NOT EXISTS documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    listing_id INT NULL,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    document_type ENUM('identity_document', 'property_document', 'tax_document', 'energy_certificate', 'other') DEFAULT 'other',
    status ENUM('uploaded', 'verified', 'rejected', 'deleted') DEFAULT 'uploaded',
    notes TEXT NULL,
    admin_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Index simples
    INDEX idx_user_id (user_id),
    INDEX idx_listing_id (listing_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    
    -- Contraintes de base
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- COMMENTAIRES SIMPLIFIÉS
-- ========================================

-- Cette table simple permet de :
-- - Stocker les documents des utilisateurs
-- - Suivre leur statut (uploaded, verified, rejected)
-- - Lier les documents aux annonces
-- - Garder un historique basique
-- 
-- Pour plus tard, on pourra ajouter :
-- - Chiffrement des fichiers
-- - Audit des accès
-- - Permissions granulaires
-- - Rotation des clés
