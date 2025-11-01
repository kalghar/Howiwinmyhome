<?php
/**
 * Modèle Game - HOW I WIN MY HOME V1
 * 
 * Gère toutes les données liées au jeu :
 * - Tickets d'achat
 * - QCM et résultats
 * - Lettres de motivation
 */

class Game {
    
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // ========================================
    // GESTION DES TICKETS
    // ========================================
    
    /**
     * Crée un nouveau ticket
     */
    public function createTicket($data) {
        try {
            // Générer un numéro de ticket unique
            $numeroTicket = 'TKT-' . date('YmdHis') . '-' . $data['user_id'] . '-' . $data['listing_id'];
            
            $sql = "INSERT INTO tickets (user_id, listing_id, numero_ticket, ticket_price, status, date_achat) 
                    VALUES (:user_id, :listing_id, :numero_ticket, :ticket_price, :status, :date_achat)";
            
            return $this->db->insert($sql, [
                'user_id' => $data['user_id'],
                'listing_id' => $data['listing_id'],
                'numero_ticket' => $numeroTicket,
                'ticket_price' => $data['ticket_price'],
                'status' => $data['status'],
                'date_achat' => $data['date_achat']
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur création ticket: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère un ticket par utilisateur et annonce
     */
    public function getTicketByUserAndListing($userId, $listingId) {
        try {
            $sql = "SELECT * FROM tickets WHERE user_id = :user_id AND listing_id = :listing_id";
            return $this->db->fetch($sql, ['user_id' => $userId, 'listing_id' => $listingId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération ticket: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère tous les tickets d'un utilisateur pour une annonce
     */
    public function getTicketsByUserAndListing($userId, $listingId) {
        try {
            $sql = "SELECT * FROM tickets WHERE user_id = :user_id AND listing_id = :listing_id ORDER BY created_at DESC";
            return $this->db->fetchAll($sql, ['user_id' => $userId, 'listing_id' => $listingId]);
        } catch (Exception $e) {
            error_log("Erreur getTicketsByUserAndListing: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère tous les tickets d'un utilisateur
     */
    public function getTicketsByUserId($userId) {
        try {
            $sql = "SELECT * FROM tickets WHERE user_id = :user_id ORDER BY date_achat DESC";
            return $this->db->fetchAll($sql, ['user_id' => $userId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération tickets: " . $e->getMessage());
            return [];
        }
    }
    
    // ========================================
    // GESTION DU QCM
    // ========================================
    
    /**
     * Récupère des questions aléatoires
     */
    public function getRandomQuestions($limit = 10) {
        try {
            $sql = "SELECT * FROM qcm_questions ORDER BY RAND() LIMIT :limit";
            return $this->db->fetchAll($sql, ['limit' => $limit]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération questions: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère une question par ID
     */
    public function getQuestionById($questionId) {
        try {
            $sql = "SELECT * FROM qcm_questions WHERE id = :id";
            return $this->db->fetch($sql, ['id' => $questionId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération question: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crée un résultat de QCM
     */
    public function createQcmResult($data) {
        try {
            $sql = "INSERT INTO qcm_results (user_id, listing_id, ticket_id, score, total_questions, pourcentage, status, detail_reponses, date_fin) 
                    VALUES (:user_id, :listing_id, :ticket_id, :score, :total_questions, :pourcentage, :status, :detail_reponses, :date_fin)";
            
            return $this->db->insert($sql, [
                'user_id' => $data['user_id'],
                'listing_id' => $data['listing_id'],
                'ticket_id' => $data['ticket_id'],
                'score' => $data['score'],
                'total_questions' => $data['total_questions'],
                'pourcentage' => $data['percentage'],
                'status' => $data['status'],
                'detail_reponses' => $data['answers'],
                'date_fin' => $data['completed_at']
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur création résultat QCM: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère un résultat de QCM par utilisateur et annonce
     */
    public function getQcmResultByUserAndListing($userId, $listingId) {
        try {
            $sql = "SELECT * FROM qcm_results WHERE user_id = :user_id AND listing_id = :listing_id";
            return $this->db->fetch($sql, ['user_id' => $userId, 'listing_id' => $listingId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération résultat QCM: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère tous les résultats d'un utilisateur
     */
    public function getQcmResultsByUserId($userId) {
        try {
            $sql = "SELECT * FROM qcm_results WHERE user_id = :user_id ORDER BY date_fin DESC";
            return $this->db->fetchAll($sql, ['user_id' => $userId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération résultats QCM: " . $e->getMessage());
            return [];
        }
    }
    
    // ========================================
    // GESTION DES LETTRES DE MOTIVATION
    // ========================================
    
    /**
     * Crée une nouvelle lettre de motivation
     */
    public function createLetter($data) {
        try {
            $sql = "INSERT INTO letters (user_id, listing_id, ticket_id, contenu, status, date_creation) 
                    VALUES (:user_id, :listing_id, :ticket_id, :contenu, :status, :date_creation)";
            
            return $this->db->insert($sql, [
                'user_id' => $data['user_id'],
                'listing_id' => $data['listing_id'],
                'ticket_id' => $data['ticket_id'],
                'contenu' => $data['contenu'],
                'status' => $data['status'],
                'date_creation' => $data['date_creation']
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur création lettre: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère une lettre par ID
     */
    public function getLetterById($letterId) {
        try {
            $sql = "SELECT * FROM letters WHERE id = :id";
            return $this->db->fetch($sql, ['id' => $letterId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération lettre: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère une lettre par utilisateur et annonce
     */
    public function getLetterByUserAndListing($userId, $listingId) {
        try {
            $sql = "SELECT * FROM letters WHERE user_id = :user_id AND listing_id = :listing_id";
            return $this->db->fetch($sql, ['user_id' => $userId, 'listing_id' => $listingId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération lettre: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère toutes les lettres d'un utilisateur
     */
    public function getLettersByUserId($userId) {
        try {
            $sql = "SELECT l.*, 
                           li.title as listing_titre, 
                           li.city as listing_ville, 
                           li.city as listing_city,
                           li.property_size as listing_surface,
                           li.price as listing_prix
                    FROM letters l 
                    LEFT JOIN listings li ON l.listing_id = li.id 
                    WHERE l.user_id = :user_id 
                    ORDER BY l.date_creation DESC";
            return $this->db->fetchAll($sql, ['user_id' => $userId]);
            
        } catch (Exception $e) {
            error_log("Erreur récupération lettres: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Met à jour une lettre
     */
    public function updateLetter($letterId, $data) {
        try {
            $sql = "UPDATE letters SET 
                    contenu = :contenu, 
                    titre = :titre, 
                    version = :version, 
                    date_modification = :date_modification, 
                    mots_cles = :mots_cles 
                    WHERE id = :id";
            
            return $this->db->update($sql, [
                'contenu' => $data['contenu'],
                'titre' => $data['titre'],
                'version' => $data['version'],
                'date_modification' => $data['date_modification'],
                'mots_cles' => $data['mots_cles'] ?? '',
                'id' => $letterId
            ]) > 0;
            
        } catch (Exception $e) {
            error_log("Erreur mise à jour lettre: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprime une lettre
     */
    public function deleteLetter($letterId) {
        try {
            $sql = "DELETE FROM letters WHERE id = :id";
            return $this->db->delete($sql, ['id' => $letterId]) > 0;
            
        } catch (Exception $e) {
            error_log("Erreur suppression lettre: " . $e->getMessage());
            return false;
        }
    }
    
    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================
    
    /**
     * Récupère les statistiques de jeu pour un utilisateur
     */
    public function getUserGameStats($userId) {
        try {
            $stats = [
                'tickets' => 0,
                'qcm_completed' => 0,
                'qcm_qualified' => 0,
                'letters_submitted' => 0,
                'letters_evaluated' => 0,
                'letters_won' => 0
            ];
            
            // Tickets
            $sql = "SELECT COUNT(*) FROM tickets WHERE user_id = :user_id";
            $stats['tickets'] = $this->db->fetchColumn($sql, ['user_id' => $userId]);
            
            // QCM complétés
            $sql = "SELECT COUNT(*) FROM qcm_results WHERE user_id = :user_id";
            $stats['qcm_completed'] = $this->db->fetchColumn($sql, ['user_id' => $userId]);
            
            // QCM qualifiés
            $sql = "SELECT COUNT(*) FROM qcm_results WHERE user_id = :user_id AND status = 'qualifie'";
            $stats['qcm_qualified'] = $this->db->fetchColumn($sql, ['user_id' => $userId]);
            
            // Lettres soumises
            $sql = "SELECT COUNT(*) FROM letters WHERE user_id = :user_id";
            $stats['letters_submitted'] = $this->db->fetchColumn($sql, ['user_id' => $userId]);
            
            // Lettres évaluées
            $sql = "SELECT COUNT(*) FROM letters WHERE user_id = :user_id AND status IN ('evalue', 'gagnant')";
            $stats['letters_evaluated'] = $this->db->fetchColumn($sql, ['user_id' => $userId]);
            
            // Lettres gagnantes
            $sql = "SELECT COUNT(*) FROM letters WHERE user_id = :user_id AND status = 'gagnant'";
            $stats['letters_won'] = $this->db->fetchColumn($sql, ['user_id' => $userId]);
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Erreur statistiques jeu: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère les statistiques des tickets
     */
    public function getTicketStats() {
        try {
            $sql = "SELECT COUNT(*) as total_tickets FROM tickets";
            $result = $this->db->fetch($sql);
            return ['total_tickets' => $result['total_tickets']];
        } catch (Exception $e) {
            error_log("Erreur getTicketStats: " . $e->getMessage());
            return ['total_tickets' => 0];
        }
    }
    
    /**
     * Récupère les statistiques des lettres
     */
    public function getLetterStats() {
        try {
            $sql = "SELECT COUNT(*) as total_lettres FROM letters";
            $result = $this->db->fetch($sql);
            return ['total_lettres' => $result['total_lettres']];
        } catch (Exception $e) {
            error_log("Erreur getLetterStats: " . $e->getMessage());
            return ['total_lettres' => 0];
        }
    }
    
    /**
     * Récupère les statistiques d'une annonce spécifique
     * @param int $listingId ID de l'annonce
     * @return array Statistiques de l'annonce
     */
    public function getStatsByListing($listingId) {
        try {
            $stats = [
                'total_tickets' => 0,
                'total_participants' => 0,
                'total_qcm_completed' => 0,
                'total_letters' => 0
            ];
            
            // Nombre total de tickets vendus pour cette annonce
            $sql = "SELECT COUNT(*) FROM tickets WHERE listing_id = :listing_id";
            $stats['total_tickets'] = $this->db->fetchColumn($sql, ['listing_id' => $listingId]);
            
            // Nombre de participants uniques (utilisateurs ayant acheté des tickets)
            $sql = "SELECT COUNT(DISTINCT user_id) FROM tickets WHERE listing_id = :listing_id";
            $stats['total_participants'] = $this->db->fetchColumn($sql, ['listing_id' => $listingId]);
            
            // Nombre de QCM complétés pour cette annonce
            $sql = "SELECT COUNT(*) FROM qcm_results WHERE listing_id = :listing_id";
            $stats['total_qcm_completed'] = $this->db->fetchColumn($sql, ['listing_id' => $listingId]);
            
            // Nombre de lettres soumises pour cette annonce
            $sql = "SELECT COUNT(*) FROM letters WHERE listing_id = :listing_id";
            $stats['total_letters'] = $this->db->fetchColumn($sql, ['listing_id' => $listingId]);
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Erreur getStatsByListing: " . $e->getMessage());
            return [
                'total_tickets' => 0,
                'total_participants' => 0,
                'total_qcm_completed' => 0,
                'total_letters' => 0
            ];
        }
    }
    
    /**
     * Compte le nombre de tickets vendus pour une annonce
     */
    public function countTicketsByListing($listingId) {
        try {
            $sql = "SELECT COUNT(*) FROM tickets WHERE listing_id = :listing_id";
            return $this->db->fetchColumn($sql, ['listing_id' => $listingId]);
            
        } catch (Exception $e) {
            error_log("Erreur countTicketsByListing: " . $e->getMessage());
            return 0;
        }
    }
}
