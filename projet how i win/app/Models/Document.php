<?php
/**
 * Modèle Document simplifié - How I Win My Home
 * 
 * Gère les documents de manière simple sans chiffrement complexe
 */

class Document {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Crée un nouveau document
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO documents (user_id, listing_id, filename, original_filename, file_path, file_size, mime_type, document_type, status, created_at) 
                    VALUES (:user_id, :listing_id, :filename, :original_filename, :file_path, :file_size, :mime_type, :document_type, :status, :created_at)";
            
            return $this->db->insert($sql, [
                'user_id' => $data['user_id'],
                'listing_id' => $data['listing_id'] ?? null,
                'filename' => $data['filename'],
                'original_filename' => $data['original_filename'],
                'file_path' => $data['file_path'],
                'file_size' => $data['file_size'],
                'mime_type' => $data['mime_type'],
                'document_type' => $data['document_type'],
                'status' => $data['status'] ?? 'uploaded',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur création document: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère un document par ID
     */
    public function getById($documentId, $userId = null) {
        try {
            $sql = "SELECT * FROM documents WHERE id = :id";
            $params = ['id' => $documentId];
            
            if ($userId) {
                $sql .= " AND user_id = :user_id";
                $params['user_id'] = $userId;
            }
            
            return $this->db->fetch($sql, $params);
            
        } catch (Exception $e) {
            error_log("Erreur récupération document: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère tous les documents d'un utilisateur
     */
    public function getByUserId($userId) {
        try {
            $sql = "SELECT * FROM documents WHERE user_id = :user_id ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, ['user_id' => $userId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération documents: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère les documents en attente de validation
     */
    public function getPendingDocuments($limit = 20, $offset = 0) {
        try {
            $sql = "SELECT d.*, u.nom, u.email FROM documents d 
                    LEFT JOIN users u ON d.user_id = u.id 
                    WHERE d.status = 'uploaded' 
                    ORDER BY d.created_at ASC 
                    LIMIT :limit OFFSET :offset";
            
            return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération documents en attente: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Met à jour le statut d'un document
     */
    public function updateStatus($documentId, $status, $adminId = null, $notes = '') {
        try {
            $sql = "UPDATE documents SET status = :status, updated_at = :updated_at";
            $params = [
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $documentId
            ];
            
            if ($adminId) {
                $sql .= ", admin_id = :admin_id";
                $params['admin_id'] = $adminId;
            }
            
            if ($notes) {
                $sql .= ", notes = :notes";
                $params['notes'] = $notes;
            }
            
            $sql .= " WHERE id = :id";
            
            return $this->db->update($sql, $params) > 0;
            
        } catch (Exception $e) {
            error_log("Erreur mise à jour statut document: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprime un document
     */
    public function delete($documentId, $userId = null) {
        try {
            $sql = "DELETE FROM documents WHERE id = :id";
            $params = ['id' => $documentId];
            
            if ($userId) {
                $sql .= " AND user_id = :user_id";
                $params['user_id'] = $userId;
            }
            
            return $this->db->delete($sql, $params) > 0;
            
        } catch (Exception $e) {
            error_log("Erreur suppression document: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les statistiques des documents
     */
    public function getStats() {
        try {
            $sql = "SELECT 
                        SUM(CASE WHEN status = 'uploaded' THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = 'verified' THEN 1 ELSE 0 END) as verified,
                        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                        COUNT(*) as total
                    FROM documents";
            
            return $this->db->fetch($sql);
            
        } catch (Exception $e) {
            error_log("Erreur statistiques documents: " . $e->getMessage());
            return [
                'pending' => 0,
                'verified' => 0,
                'rejected' => 0,
                'total' => 0
            ];
        }
    }
    
    /**
     * Vérifie si un utilisateur peut accéder à un document
     */
    public function canAccess($documentId, $userId, $userRole = 'user') {
        try {
            $document = $this->getById($documentId);
            
            if (!$document) {
                return false;
            }
            
            // L'utilisateur peut accéder à ses propres documents
            if ($document['user_id'] == $userId) {
                return true;
            }
            
            // Les admins peuvent accéder à tous les documents
            if ($userRole === 'admin') {
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("Erreur vérification accès document: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtient le libellé pour un type de document
     */
    public function getDocumentTypeLabel($documentType) {
        $labels = [
            'identity_document' => 'Pièce d\'identité',
            'property_document' => 'Acte de propriété',
            'tax_document' => 'Taxe foncière',
            'energy_certificate' => 'DPE',
            'other' => 'Autre document'
        ];
        
        return $labels[$documentType] ?? 'Document';
    }
    
    /**
     * Obtient le libellé pour un statut
     */
    public function getStatusLabel($status) {
        $labels = [
            'uploaded' => 'En attente',
            'verified' => 'Vérifié',
            'rejected' => 'Rejeté',
            'deleted' => 'Supprimé'
        ];
        
        return $labels[$status] ?? 'Inconnu';
    }
    
    /**
     * Formate la taille d'un fichier
     */
    public function formatFileSize($size) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
