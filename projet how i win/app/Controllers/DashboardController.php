<?php
/**
 * Contrôleur du tableau de bord - HOW I WIN MY HOME V1
 * 
 * Gère l'affichage du tableau de bord utilisateur
 * Hérite de BaseController pour utiliser les méthodes communes
 */

// Includes supprimés - gérés par l'autoloader

class DashboardController extends BaseController {
    
    // Propriétés pour optimiser les instanciations répétitives
    private $userModel;
    private $gameModel;
    private $listingModel;
    
    /**
     * Constructeur - initialise les modèles une seule fois
     */
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->gameModel = new Game();
        $this->listingModel = new Listing();
    }
    
    /**
     * Affiche le tableau de bord principal
     * @return string HTML du tableau de bord
     */
    public function index() {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return '';
        }
        
        $userId = $this->getCurrentUserId();
        $userRole = $this->getCurrentUserRole();
        
        // Récupérer les données du tableau de bord
        $data = $this->getDashboardData($userId, $userRole);
        
        return $this->renderLayout('dashboard/index', $data);
    }
    
    /**
     * Récupère les données du tableau de bord
     * @param int $userId ID de l'utilisateur
     * @param string $userRole Rôle de l'utilisateur
     * @return array Données du tableau de bord
     */
    private function getDashboardData($userId, $userRole) {
        $user = $this->getCurrentUserInfo($userId);
        $data = [
            'title' => 'Tableau de bord - How I Win My Home',
            'page' => 'dashboard',
            'user' => $user,
            'userBalance' => $user['balance'] ?? 0,
            'stats' => $this->getUserStats($userId, $userRole),
            'isLoggedIn' => $this->isUserLoggedIn(),
            'userRole' => $userRole
        ];
        
        // Ajouter des données spécifiques selon le rôle
        switch ($userRole) {
            case 'user':
                // Tous les utilisateurs peuvent acheter des tickets ET créer des annonces
                $data['tickets'] = $this->getUserTickets($userId);
                $data['qcmResults'] = $this->getUserQcmResults($userId);
                $data['letters'] = $this->getUserLetters($userId);
                $data['listings'] = $this->getUserListings($userId);
                $data['ticketsSold'] = $this->getTicketsSold($userId);
                break;
                
            case 'admin':
                $data['adminStats'] = $this->getAdminStats();
                break;
                
            case 'jury':
                $data['pendingEvaluations'] = $this->getPendingEvaluations();
                break;
        }
        
        return $data;
    }
    
    /**
     * Obtient les informations de l'utilisateur connecté
     * @param int $userId ID de l'utilisateur
     * @return array Informations de l'utilisateur
     */
    private function getCurrentUserInfo($userId) {
        try {
            return $this->userModel->getById($userId);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des infos utilisateur: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les statistiques de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @param string $userRole Rôle de l'utilisateur
     * @return array Statistiques de l'utilisateur
     */
    private function getUserStats($userId, $userRole) {
        $stats = [
            'total_participations' => 0,
            'total_tickets' => 0,
            'total_listings' => 0,
            'total_letters' => 0
        ];
        
        try {
            switch ($userRole) {
                case 'user':
                    // Tous les utilisateurs peuvent acheter des tickets ET créer des annonces
                    
                    // Statistiques d'achat de tickets
                    $stats['total_tickets'] = count($this->gameModel->getTicketsByUserId($userId) ?: []);
                    $stats['total_participations'] = count($this->gameModel->getQcmResultsByUserId($userId) ?: []);
                    $stats['total_letters'] = count($this->gameModel->getLettersByUserId($userId) ?: []);
                    
                    // Statistiques de vente d'annonces
                    $stats['total_listings'] = count($this->listingModel->getBySeller($userId) ?: []);
                    // Compter les tickets vendus pour toutes les annonces de l'utilisateur
                    $listings = $this->listingModel->getBySeller($userId) ?: [];
                    foreach ($listings as $listing) {
                        $stats['total_tickets'] += $this->listingModel->getTicketsSold($listing['id']) ?: 0;
                    }
                    break;
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
        }
        
        return $stats;
    }
    
    /**
     * Obtient les tickets de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Tickets de l'utilisateur
     */
    private function getUserTickets($userId) {
        try {
            return $this->gameModel->getTicketsByUserId($userId) ?: []; // Limiter à 5 tickets récents
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des tickets: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les résultats QCM de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Résultats QCM de l'utilisateur
     */
    private function getUserQcmResults($userId) {
        try {
            return $this->gameModel->getQcmResultsByUserId($userId) ?: []; // Limiter à 5 résultats récents
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des résultats QCM: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les lettres de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Lettres de l'utilisateur
     */
    private function getUserLetters($userId) {
        try {
            return $this->gameModel->getLettersByUserId($userId) ?: []; // Limiter à 5 lettres récentes
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des lettres: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les annonces de l'utilisateur vendeur
     * @param int $userId ID de l'utilisateur
     * @return array Annonces de l'utilisateur
     */
    private function getUserListings($userId) {
        try {
            return $this->listingModel->getBySeller($userId) ?: []; // Récupérer toutes les annonces de l'utilisateur
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des annonces: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient le nombre de tickets vendus par l'utilisateur vendeur
     * @param int $userId ID de l'utilisateur
     * @return array Statistiques des tickets vendus
     */
    private function getTicketsSold($userId) {
        try {
            $listings = $this->listingModel->getBySeller($userId) ?: [];
            $ticketsSold = [];
            
            foreach ($listings as $listing) {
                $count = $this->listingModel->getTicketsSold($listing['id']) ?: 0;
                $ticketsSold[] = [
                    'listing_id' => $listing['id'],
                    'listing_titre' => $listing['title'],
                    'tickets_vendus' => $count,
                    'tickets_needed' => $listing['tickets_needed'] ?? 0
                ];
            }
            
            return $ticketsSold;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des tickets vendus: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les statistiques administrateur
     * @return array Statistiques administrateur
     */
    private function getAdminStats() {
        try {
            $stats = [
                'total_users' => 0,
                'total_listings' => 0,
                'pending_listings' => 0,
                'total_tickets' => 0,
                'total_letters' => 0
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
            $stats['total_tickets'] = $this->gameModel->getTotalTickets() ?: 0;
            
            // Compter les lettres
            $stats['total_letters'] = $this->gameModel->getTotalLetters() ?: 0;
            
            return $stats;
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des stats admin: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtient les évaluations en attente pour le jury
     * @return array Évaluations en attente
     */
    private function getPendingEvaluations() {
        try {
            return $this->gameModel->getUnevaluatedLetters(10) ?: []; // Limiter à 10 lettres
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des évaluations: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Affiche le profil de l'utilisateur
     * @return string HTML du profil
     */
    public function profile() {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return '';
        }
        
        $userId = $this->getCurrentUserId();
        
        // Traiter la mise à jour du profil
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->updateProfile($userId);
        }
        
        // Afficher le profil
        $data = [
            'title' => 'Mon profil - How I Win My Home',
            'page' => 'profile',
            'user' => $this->getCurrentUserInfo($userId),
            'profileData' => [
                'tickets' => $this->getUserTickets($userId),
                'listings' => $this->getUserListings($userId),
                'letters' => $this->getUserLetters($userId),
                'qcmResults' => $this->getUserQcmResults($userId)
            ]
        ];
        
        return $this->renderLayout('user/profile', $data);
    }
    
    /**
     * Met à jour le profil de l'utilisateur
     * @param int $userId ID de l'utilisateur
     * @return string Redirection ou message d'erreur
     */
    private function updateProfile($userId) {
        try {
            // Validation des données avec InputValidator
            $rules = [
                'nom' => ['required', 'min:2', 'max:50'],
                'email' => ['required', 'email']
            ];
            
            [$validatedData, $validationErrors] = \App\Validators\InputValidator::validatePost($rules);
            
            if (!empty($validationErrors)) {
                return $this->redirect('/dashboard/profile');
            }
            
            $updateData = [
                'nom' => \App\Validators\InputValidator::sanitizeString($validatedData['nom']),
                'email' => \App\Validators\InputValidator::sanitizeEmail($validatedData['email'])
            ];
            
            if ($this->userModel->update($userId, $updateData)) {
                // CORRECTION : Message flash supprimé
            } else {
                // CORRECTION : Message flash supprimé
            }
            
            return $this->redirect('/dashboard/profile');
            
        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour du profil: " . $e->getMessage());
                // CORRECTION : Message flash supprimé
            return $this->redirect('/dashboard/profile');
        }
    }
    
    /**
     * Valide les données du profil
     * @param array $data Données du formulaire
     * @return array Résultat de la validation
     */
    private function validateProfileData($data) {
        $errors = [];
        
        // Validation du nom
        if (empty($data['nom'])) {
            $errors['nom'] = 'Le nom est obligatoire';
        } elseif (strlen($data['nom']) < 2) {
            $errors['nom'] = 'Le nom doit contenir au moins 2 caractères';
        } elseif (strlen($data['nom']) > 50) {
            $errors['nom'] = 'Le nom ne peut pas dépasser 50 caractères';
        }
        
        // Validation de l'email
        if (empty($data['email'])) {
            $errors['email'] = 'L\'email est obligatoire';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format d\'email invalide';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'message' => empty($errors) ? '' : 'Veuillez corriger les erreurs'
        ];
    }
    
    /**
     * Affiche la page de changement de mot de passe
     * @return string HTML de la page de changement de mot de passe
     */
    public function changePassword() {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return '';
        }
        
        $data = [
            'title' => 'Changer mon mot de passe - How I Win My Home',
            'page' => 'change-password'
        ];
        
        return $this->renderLayout('dashboard/change-password', $data);
    }
    
    /**
     * Traite le changement de mot de passe
     * @return string Redirection ou message d'erreur
     */
    public function processPasswordChange() {
        // Vérifier que l'utilisateur est connecté
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return '';
        }
        
        try {
            // Validation des données avec InputValidator
            $rules = [
                'current_password' => ['required'],
                'new_password' => ['required', 'min:8'],
                'confirm_password' => ['required']
            ];
            
            [$validatedData, $validationErrors] = \App\Validators\InputValidator::validatePost($rules);
            
            // Vérification de la correspondance des mots de passe
            if ($validatedData['new_password'] !== $validatedData['confirm_password']) {
                $validationErrors[] = 'Les mots de passe ne correspondent pas';
            }
            
            if (!empty($validationErrors)) {
                return $this->redirect('/dashboard/change-password');
            }
            
            $userId = $this->getCurrentUserId();
            
            // Vérifier l'ancien mot de passe
            if (!$this->userModel->verifyPassword($userId, $validatedData['current_password'])) {
                return $this->redirect('/dashboard/change-password');
            }
            
            // Mettre à jour le mot de passe
            if ($this->userModel->updatePassword($userId, $validatedData['new_password'])) {
                // CORRECTION : Message flash supprimé
            } else {
                // CORRECTION : Message flash supprimé
            }
            
            return $this->redirect('/dashboard/change-password');
            
        } catch (Exception $e) {
            error_log("Erreur lors du changement de mot de passe: " . $e->getMessage());
                // CORRECTION : Message flash supprimé
            return $this->redirect('/dashboard/change-password');
        }
    }
    
    /**
     * Valide les données de changement de mot de passe
     * @param array $data Données du formulaire
     * @return array Résultat de la validation
     */
    private function validatePasswordChangeData($data) {
        $errors = [];
        
        // Validation de l'ancien mot de passe
        if (empty($data['current_password'])) {
            $errors['current_password'] = 'Le mot de passe actuel est obligatoire';
        }
        
        // Validation du nouveau mot de passe
        if (empty($data['new_password'])) {
            $errors['new_password'] = 'Le nouveau mot de passe est obligatoire';
        } elseif (strlen($data['new_password']) < 8) {
            $errors['new_password'] = 'Le nouveau mot de passe doit contenir au moins 8 caractères';
        }
        
        // Validation de la confirmation
        if (empty($data['confirm_password'])) {
            $errors['confirm_password'] = 'La confirmation du mot de passe est obligatoire';
        } elseif ($data['new_password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Les mots de passe ne correspondent pas';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'message' => empty($errors) ? '' : 'Veuillez corriger les erreurs'
        ];
    }
} 
