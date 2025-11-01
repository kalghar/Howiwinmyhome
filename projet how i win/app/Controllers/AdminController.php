<?php
/**
 * Contrôleur d'administration - HOW I WIN MY HOME V1
 * 
 * Gère toutes les fonctionnalités d'administration de la plateforme
 * Hérite de BaseController pour utiliser les méthodes communes
 */

// Includes supprimés - gérés par l'autoloader

class AdminController extends BaseController {
    
    // Propriétés pour optimiser les instanciations répétitives
    private $userModel;
    private $listingModel;
    private $gameModel;
    
    /**
     * Constructeur - initialise les modèles une seule fois
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->listingModel = new Listing();
        $this->gameModel = new Game();
    }
    
    /**
     * Affiche le tableau de bord administrateur
     * @return string HTML du tableau de bord admin
     */
    public function index() {
        // CORRECTION : Utilisation de la méthode commune pour vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        // Récupérer les statistiques d'administration
        $data = [
            'title' => 'Administration - How I Win My Home',
            'page' => 'admin',
            'stats' => $this->getAdminStats(),
            'recentActivities' => $this->getRecentActivities(),
            'pendingListings' => $this->getPendingListings(),
            'userStats' => $this->getUserStats(),
            'system' => $this->getSystemInfo(),
            'isLoggedIn' => $this->isUserLoggedIn(),
            'userRole' => $this->getCurrentUserRole()
        ];
        
        return $this->renderLayout('admin/index', $data);
    }
    
    /**
     * Affiche la gestion des utilisateurs
     * @return string HTML de la gestion des utilisateurs
     */
    public function users() {
        // CORRECTION : Utilisation de la méthode commune
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        $data = [
            'title' => 'Gestion des utilisateurs - Administration',
            'page' => 'admin-users',
            'users' => $this->getAllUsers(),
            'userStats' => $this->getUserStats()
        ];
        
        return $this->renderLayout('admin/users', $data);
    }
    
    /**
     * Met à jour le statut d'un utilisateur
     * @return string Redirection ou message d'erreur
     */
    public function updateUserStatus() {
        // CORRECTION : Utilisation des méthodes communes
        if (!$this->requireAdminRole() || !$this->requirePostRequest('/admin/users')) {
            return '';
        }
        
        try {
            // Validation CSRF
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
                return $this->redirect('/admin/users');
            }
            
            // Validation des données avec InputValidator
            $rules = [
                'user_id' => ['required', 'numeric', 'min_value:1'],
                'status' => ['required', 'in:active,inactive,suspended']
            ];
            
            [$validatedData, $validationErrors] = \App\Validators\InputValidator::validatePost($rules);
            
            if (!empty($validationErrors)) {
                return $this->redirect('/admin/users');
            }
            
            $userId = \App\Validators\InputValidator::sanitizeInt($validatedData['user_id']);
            $newStatus = $validatedData['status'];
            
            if ($this->userModel->updateStatus($userId, $newStatus)) {
                // CORRECTION : Message flash supprimé
            } else {
                // CORRECTION : Message flash supprimé
            }
            
            return $this->redirect('/admin/users');
            
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du statut utilisateur: " . $e->getMessage());
            // CORRECTION : Message flash supprimé
            return $this->redirect('/admin/users');
        }
    }
    
    /**
     * Affiche les détails d'une annonce
     * @return string HTML des détails de l'annonce
     */
    public function viewListing($id = null) {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        $listingId = (int)($id ?? 0);
        if (!$listingId) {
            return $this->redirect('/admin/listings');
        }
        
        try {
            // Récupérer l'annonce
            $listing = $this->listingModel->getById($listingId);
            if (!$listing) {
                return $this->redirect('/admin/listings');
            }
            
            // Enrichir avec images et documents
            $listing['images'] = $this->getListingImages($listingId);
            $listing['documents'] = $this->getListingDocuments($listingId);
            
            $data = [
                'title' => 'Détails de l\'annonce - Administration',
                'page' => 'admin-listing-detail',
                'listing' => $listing
            ];
            
            return $this->renderLayout('admin/listing-detail', $data);
            
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération de l'annonce: " . $e->getMessage());
            return $this->redirect('/admin/listings');
        }
    }
    
    /**
     * Affiche les annonces en attente de validation
     * @return string HTML des annonces en attente
     */
    public function pendingListings() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        $pendingListings = $this->getPendingListings();
        
        $data = [
            'title' => 'Annonces en attente - Administration',
            'page' => 'admin-pending-listings',
            'pendingListings' => $pendingListings
        ];
        
        return $this->renderLayout('admin/pending-listings', $data);
    }
    
    /**
     * Valide une annonce
     * @return string Redirection ou message d'erreur
     */
    public function approveListing() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return json_encode(['success' => false, 'message' => 'Accès refusé']);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
        }
        
        try {
            // Récupérer les données JSON
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['listing_id'])) {
                return json_encode(['success' => false, 'message' => 'Données manquantes']);
            }
            
            $listingId = (int)$input['listing_id'];
            
            if (!$listingId) {
                return json_encode(['success' => false, 'message' => 'ID d\'annonce invalide']);
            }
            
            if ($this->listingModel->updateStatus($listingId, 'active')) {
                return json_encode(['success' => true, 'message' => 'Annonce approuvée avec succès']);
            } else {
                return json_encode(['success' => false, 'message' => 'Erreur lors de l\'approbation']);
            }
            
        } catch (Exception $e) {
            error_log("Erreur lors de l'approbation de l'annonce: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Erreur interne du serveur']);
        }
    }
    
    /**
     * Rejette une annonce
     * @return string Redirection ou message d'erreur
     */
    public function rejectListing() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return json_encode(['success' => false, 'message' => 'Accès refusé']);
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
        }
        
        try {
            // Récupérer les données JSON
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input || !isset($input['listing_id'])) {
                return json_encode(['success' => false, 'message' => 'Données manquantes']);
            }
            
            $listingId = (int)$input['listing_id'];
            $reason = $input['reason'] ?? 'Aucune raison spécifiée';
            
            if (!$listingId) {
                return json_encode(['success' => false, 'message' => 'ID d\'annonce invalide']);
            }
            
            if ($this->listingModel->updateStatus($listingId, 'rejected')) {
                // Envoyer un email de notification au vendeur
                $this->notifySellerOfRejection($listingId, $reason);
                return json_encode(['success' => true, 'message' => 'Annonce rejetée avec succès']);
            } else {
                return json_encode(['success' => false, 'message' => 'Erreur lors du rejet']);
            }
            
        } catch (Exception $e) {
            error_log("Erreur lors du rejet de l'annonce: " . $e->getMessage());
            return json_encode(['success' => false, 'message' => 'Erreur interne du serveur']);
        }
    }
    
    /**
     * Affiche toutes les annonces
     * @return string HTML de toutes les annonces
     */
    public function allListings() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        $data = [
            'title' => 'Toutes les annonces - Administration',
            'page' => 'admin-listings',
            'listings' => $this->getAllListings(),
            'listingStats' => $this->getListingStats()
        ];
        
        return $this->renderLayout('admin/all-listings', $data);
    }
    
    /**
     * Met à jour le statut d'une annonce
     * @return string Redirection ou message d'erreur
     */
    public function updateListingStatus() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/listings');
            return '';
        }
        
        try {
            // Validation des données avec InputValidator
            $rules = [
                'listing_id' => ['required', 'numeric', 'min_value:1'],
                'status' => ['required', 'in:active,inactive,pending,rejected,completed']
            ];
            
            [$validatedData, $validationErrors] = \App\Validators\InputValidator::validatePost($rules);
            
            if (!empty($validationErrors)) {
                return $this->redirect('/admin/listings');
            }
            
            $listingId = \App\Validators\InputValidator::sanitizeInt($validatedData['listing_id']);
            $newStatus = $validatedData['status'];
            
            if (!$listingId || !in_array($newStatus, ['active', 'inactive', 'rejected', 'completed'])) {
                // CORRECTION : Message flash supprimé
                return $this->redirect('/admin/listings');
            }
            
            if ($this->listingModel->updateStatus($listingId, $newStatus)) {
                // CORRECTION : Message flash supprimé
            } else {
                // CORRECTION : Message flash supprimé
            }
            
            return $this->redirect('/admin/listings');
            
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du statut d'annonce: " . $e->getMessage());
            // CORRECTION : Message flash supprimé
            return $this->redirect('/admin/listings');
        }
    }
    
    /**
     * Affiche la configuration du système
     * @return string HTML de la configuration
     */
    public function settings() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        $data = [
            'title' => 'Configuration système - Administration',
            'page' => 'admin-settings',
            'settings' => $this->getSystemSettings()
        ];
        
        return $this->renderLayout('admin/settings', $data);
    }
    
    /**
     * Affiche les rapports et analyses
     * @return string HTML des rapports
     */
    public function reports() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        $data = [
            'title' => 'Rapports et analyses - Administration',
            'page' => 'admin-reports',
            'reports' => $this->getReportsData()
        ];
        
        return $this->renderLayout('admin/reports', $data);
    }
    
    /**
     * Calcule le temps d'attente d'un document
     * @param string $createdAt Date de création du document
     * @return string Temps d'attente formaté
     */
    public function calculateTimeWaiting($createdAt) {
        try {
            $now = new DateTime();
            $created = new DateTime($createdAt);
            $diff = $now->diff($created);
            
            if ($diff->days > 0) {
                return $diff->days . ' jour(s)';
            } elseif ($diff->h > 0) {
                return $diff->h . ' heure(s)';
            } elseif ($diff->i > 0) {
                return $diff->i . ' minute(s)';
            } else {
                return 'Moins d\'une minute';
            }
        } catch (Exception $e) {
            return 'N/A';
        }
    }
    
    /**
     * Met à jour les paramètres du système
     * @return string Redirection ou message d'erreur
     */
    public function updateSettings() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
            return '';
        }
        
        try {
            // Validation des données avec InputValidator
            $rules = [
                'max_tickets_per_listing' => ['required', 'numeric', 'min_value:10', 'max_value:10000'],
                'qcm_time_limit' => ['required', 'numeric', 'min_value:60', 'max_value:3600'],
                'min_qcm_score' => ['required', 'numeric', 'min_value:0', 'max_value:100'],
                'auto_approve_listings' => []
            ];
            
            [$validatedData, $validationErrors] = \App\Validators\InputValidator::validatePost($rules);
            
            if (!empty($validationErrors)) {
                return $this->redirect('/admin/settings');
            }
            
            // Traitement des paramètres
            $settings = [
                'max_tickets_per_listing' => \App\Validators\InputValidator::sanitizeInt($validatedData['max_tickets_per_listing']),
                'qcm_time_limit' => \App\Validators\InputValidator::sanitizeInt($validatedData['qcm_time_limit']),
                'min_qcm_score' => \App\Validators\InputValidator::sanitizeInt($validatedData['min_qcm_score']),
                'auto_approve_listings' => isset($_POST['auto_approve_listings']) ? 1 : 0
            ];
            
            if ($this->updateSystemSettings($settings)) {
                // CORRECTION : Message flash supprimé
            } else {
                // CORRECTION : Message flash supprimé
            }
            
            return $this->redirect('/admin/settings');
            
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour des paramètres: " . $e->getMessage());
            // CORRECTION : Message flash supprimé
            return $this->redirect('/admin/settings');
        }
    }
    
    // ========================================
    // MÉTHODES PRIVÉES ET UTILITAIRES
    // ========================================
    
    // Note: La méthode requireAdminRole() est maintenant héritée de BaseController
    
    /**
     * Obtient les statistiques d'administration
     * @return array Statistiques d'administration
     */
    private function getAdminStats() {
        try {
            $stats = [
                'total_users' => 0,
                'total_listings' => 0,
                'pending_listings' => 0,
                'total_tickets' => 0,
                'total_letters' => 0,
                'active_contests' => 0,
                'users_change' => 0,
                'listings_change' => 0,
                'tickets_change' => 0,
                'letters_change' => 0
            ];
            
            // Compter les utilisateurs
            $users = $this->userModel->getAll() ?: [];
            $stats['total_users'] = count($users);
            
            // Compter les annonces
            $listings = $this->listingModel->getAll() ?: [];
            $stats['total_listings'] = count($listings);
            
            // Compter les annonces en attente
            $pendingListings = array_filter($listings, function($listing) {
                return $listing['status'] === 'pending';
            });
            $stats['pending_listings'] = count($pendingListings);
            
            // Compter les tickets
            $ticketStats = $this->gameModel->getTicketStats() ?: [];
            $stats['total_tickets'] = $ticketStats['total_tickets'] ?? 0;
            
            // Compter les lettres
            $letterStats = $this->gameModel->getLetterStats() ?: [];
            $stats['total_letters'] = $letterStats['total_lettres'] ?? 0;
            
            // Compter les concours actifs
            $activeListings = array_filter($listings, function($listing) {
                return $listing['status'] === 'active' && 
                       (!isset($listing['end_date']) || strtotime($listing['end_date']) > time());
            });
            $stats['active_contests'] = count($activeListings);
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des stats admin: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les activités récentes
     * @return array Activités récentes
     */
    private function getRecentActivities() {
        try {
            $activities = [];
            
            // Derniers utilisateurs inscrits
            $recentUsers = $this->userModel->getAll(5, 0) ?: [];
            foreach ($recentUsers as $user) {
                $userName = ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '');
                $userName = trim($userName) ?: 'Utilisateur';
                $activities[] = [
                    'type' => 'user_registration',
                    'message' => 'Nouvel utilisateur inscrit : ' . $userName,
                    'date' => $user['created_at'] ?? 'N/A',
                    'icon' => 'user-plus'
                ];
            }
            
            // Dernières annonces créées
            $recentListings = $this->listingModel->getAll(null, 5, 0) ?: [];
            foreach ($recentListings as $listing) {
                $activities[] = [
                    'type' => 'listing_created',
                    'message' => 'Nouvelle annonce : ' . ($listing['titre'] ?? 'Sans titre'),
                    'date' => $listing['created_at'] ?? 'N/A',
                    'icon' => 'home'
                ];
            }
            
            // Trier par date (plus récent en premier)
            usort($activities, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
            
            return array_slice($activities, 0, 10);
            
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des activités: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient tous les utilisateurs
     * @return array Liste des utilisateurs
     */
    private function getAllUsers() {
        try {
            return $this->userModel->getAll() ?: [];
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des utilisateurs: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les statistiques des utilisateurs
     * @return array Statistiques des utilisateurs
     */
    private function getUserStats() {
        try {
            $users = $this->getAllUsers();
            
            $stats = [
                'total' => count($users),
                'users' => 0,
                'admins' => 0,
                'jury' => 0,
                'active' => 0,
                'inactive' => 0
            ];
            
            foreach ($users as $user) {
                $role = $user['role'] ?? 'user';
                $status = $user['status'] ?? 'active';
                
                switch ($role) {
                    case 'user':
                        $stats['users']++;
                        break;
                    case 'admin':
                        $stats['admins']++;
                        break;
                    case 'jury':
                        $stats['jury']++;
                        break;
                }
                
                if ($status === 'active') {
                    $stats['active']++;
                } else {
                    $stats['inactive']++;
                }
            }
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des stats utilisateurs: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les annonces en attente
     * @return array Annonces en attente
     */
    private function getPendingListings() {
        try {
            $listings = $this->listingModel->getPending(10, 0) ?: [];
            // Enrichir chaque annonce avec ses images et documents
            foreach ($listings as &$listing) {
                $listing['images'] = $this->getListingImages($listing['id']);
                $listing['documents'] = $this->getListingDocuments($listing['id']);
            }
            
            return $listings;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des annonces en attente: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère les images d'une annonce
     * @param int $listingId ID de l'annonce
     * @return array Images de l'annonce
     */
    private function getListingImages($listingId) {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            $stmt = $pdo->prepare("
                SELECT filename, is_primary, sort_order 
                FROM listing_images 
                WHERE listing_id = ? 
                ORDER BY sort_order ASC
            ");
            $stmt->execute([$listingId]);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
            // Ajouter le chemin complet pour chaque image
            foreach ($images as &$image) {
                $image['file_path'] = 'uploads/listings/' . $image['filename'];
            }
            
            return $images;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des images: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère les documents d'une annonce
     * @param int $listingId ID de l'annonce
     * @return array Documents de l'annonce
     */
    private function getListingDocuments($listingId) {
        try {
            $db = Database::getInstance();
            $pdo = $db->getConnection();
            
            $stmt = $pdo->prepare("
                SELECT document_type, original_filename, file_path, status, created_at 
                FROM documents 
                WHERE listing_id = ? 
                ORDER BY created_at ASC
            ");
            $stmt->execute([$listingId]);
            $documents = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            
            // Ajouter le chemin complet pour chaque document
            foreach ($documents as &$document) {
                if (!empty($document['file_path'])) {
                    $document['file_path'] = 'uploads/documents/' . basename($document['file_path']);
                }
            }
            
            return $documents;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des documents: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient toutes les annonces
     * @return array Toutes les annonces
     */
    private function getAllListings() {
        try {
            return $this->listingModel->getAll() ?: [];
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des annonces: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les statistiques des annonces
     * @return array Statistiques des annonces
     */
    private function getListingStats() {
        try {
            $listings = $this->getAllListings();
            
            $stats = [
                'total' => count($listings),
                'active' => 0,
                'pending' => 0,
                'rejected' => 0,
                'completed' => 0
            ];
            
            foreach ($listings as $listing) {
                $status = $listing['status'] ?? 'pending';
                
                switch ($status) {
                    case 'active':
                        $stats['active']++;
                        break;
                    case 'pending':
                        $stats['pending']++;
                        break;
                    case 'rejected':
                        $stats['rejected']++;
                        break;
                    case 'completed':
                        $stats['completed']++;
                        break;
                }
            }
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des stats annonces: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les paramètres du système
     * @return array Paramètres du système
     */
    private function getSystemSettings() {
        // Pour la V1, retourner des paramètres par défaut
        // Dans une version future, ces paramètres pourraient être stockés en base
        return [
            'max_tickets_per_listing' => 100,
            'qcm_time_limit' => 300,
            'min_qcm_score' => 50,
            'auto_approve_listings' => 0,
            'max_listings_per_user' => 5,
            'min_listing_price' => 1000,
            'max_listing_price' => 1000000
        ];
    }
    
    /**
     * Met à jour les paramètres du système
     * @param array $settings Nouveaux paramètres
     * @return bool Succès de la mise à jour
     */
    private function updateSystemSettings($settings) {
        try {
            // Pour la V1, simuler la mise à jour
            // Dans une version future, ces paramètres seraient stockés en base
            error_log("Mise à jour des paramètres système: " . json_encode($settings));
            return true;
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour des paramètres: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Notifie le vendeur du rejet de son annonce
     * @param int $listingId ID de l'annonce
     * @param string $reason Raison du rejet
     */
    private function notifySellerOfRejection($listingId, $reason) {
        try {
            $listing = $this->listingModel->getById($listingId);
            
            if ($listing && isset($listing['user_id'])) {
                $seller = $this->userModel->getById($listing['user_id']);
                
                if ($seller) {
                    // Envoyer un email de notification
                    $emailHelper = new EmailHelper();
                    $emailHelper->sendListingRejectionEmail(
                        $seller['email'],
                        $seller['nom'],
                        $listing['titre'],
                        $reason
                    );
                }
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la notification du vendeur: " . $e->getMessage());
        }
    }
    
    /**
     * Obtient les informations système
     * @return array Informations système
     */
    private function getSystemInfo() {
        return [
            'version' => '1.0.0',
            'php_version' => PHP_VERSION,
            'server_time' => date('Y-m-d H:i:s'),
            'memory_usage' => memory_get_usage(true),
            'memory_limit' => ini_get('memory_limit')
        ];
    }
    
    /**
     * Obtient les données pour les rapports
     * @return array Données des rapports
     */
    private function getReportsData() {
        try {
            return [
                'user_stats' => $this->getUserStats(),
                'listing_stats' => $this->getListingStats(),
                'admin_stats' => $this->getAdminStats(),
                'recent_activities' => $this->getRecentActivities()
            ];
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des données de rapport: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Affiche la page de gestion des documents
     * @return string HTML de la page des documents
     */
    public function documents() {
        // Vérifier les permissions
        if (!$this->requireAdminRole()) {
            return '';
        }
        
        // Récupérer les documents
        $documents = $this->getDocuments();
        
        // Calculer le temps d'attente pour chaque document
        foreach ($documents as &$document) {
            $document['time_waiting'] = $this->calculateTimeWaiting($document['created_at'] ?? 'now');
        }
        
        $data = [
            'title' => 'Gestion des documents - Administration',
            'page' => 'admin-documents',
            'documents' => $documents,
            'stats' => ['total' => 0, 'pending' => 0, 'verified' => 0, 'rejected' => 0],
            'filters' => ['types' => [], 'statuses' => []],
            'formatFileSize' => [$this, 'formatFileSize']
        ];
        
        return $this->renderLayout('admin/documents', $data);
    }
    
    /**
     * Récupère la liste des documents
     * @return array Liste des documents
     */
    private function getDocuments() {
        // Données complètes pour la vue
        return [
            [
                'id' => 1,
                'listing_id' => 1,
                'type' => 'identity',
                'filename' => 'carte_identite.pdf',
                'original_filename' => 'carte_identite.pdf',
                'status' => 'pending',
                'uploaded_at' => '2025-09-09 10:30:00',
                'created_at' => '2025-09-09 10:30:00',
                'verified_at' => null,
                'file_size' => 1024000,
                'first_name' => 'Benoit',
                'last_name' => 'Poujade',
                'email' => 'benoit@example.com',
                'user_name' => 'Benoit Poujade'
            ]
        ];
    }
    
    /**
     * Récupère les statistiques des documents
     * @return array Statistiques des documents
     */
    private function getDocumentStats() {
        return [
            'total' => 2,
            'pending' => 1,
            'verified' => 1,
            'rejected' => 0
        ];
    }
    
    /**
     * Récupère les filtres disponibles
     * @return array Filtres des documents
     */
    private function getDocumentFilters() {
        return [
            'types' => ['identity', 'property', 'income'],
            'statuses' => ['pending', 'verified', 'rejected']
        ];
    }
    
    /**
     * Obtient l'icône pour un type de document
     * 
     * @param string $documentType Type de document
     * @return string Nom de l'icône
     */
    public function getDocumentIcon($documentType) {
        switch ($documentType) {
            case 'identity':
                return 'id-card';
            case 'property':
                return 'home';
            case 'income':
                return 'receipt';
            case 'energy':
                return 'leaf';
            default:
                return 'file-alt';
        }
    }
    
    /**
     * Obtient le libellé pour un type de document
     * 
     * @param string $documentType Type de document
     * @return string Libellé du type
     */
    public function getDocumentTypeLabel($documentType) {
        switch ($documentType) {
            case 'identity':
                return 'Pièce d\'identité';
            case 'property':
                return 'Acte de propriété';
            case 'income':
                return 'Justificatif de revenus';
            case 'energy':
                return 'DPE';
            default:
                return 'Document';
        }
    }
    
    /**
     * Obtient le libellé pour un statut
     * 
     * @param string $status Statut du document
     * @return string Libellé du statut
     */
    public function getStatusLabel($status) {
        switch ($status) {
            case 'pending':
                return 'En attente';
            case 'verified':
                return 'Vérifié';
            case 'rejected':
                return 'Rejeté';
            case 'deleted':
                return 'Supprimé';
            default:
                return 'Inconnu';
        }
    }
    
    
    /**
     * Formate la taille d'un fichier
     * @param int $size Taille en octets
     * @return string Taille formatée
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
    
    // ========================================
    // MÉTHODES MANQUANTES CRITIQUES
    // ========================================
    
    
    
    
    
} 
