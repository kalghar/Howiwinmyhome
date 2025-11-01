<?php
/**
 * Contrôleur d'authentification - HOW I WIN MY HOME V1
 * 
 * Gère l'inscription, la connexion et la déconnexion des utilisateurs
 * Hérite de BaseController pour utiliser les méthodes communes
 */

// Include supprimé - géré par l'autoloader

class AuthController extends BaseController {
    
    // ========================================
    // PROPRIÉTÉS POUR OPTIMISATION
    // ========================================
    
    /**
     * Instance du modèle User (réutilisable)
     * @var User
     */
    private $userModel;
    
    // ========================================
    // CONSTRUCTEUR
    // ========================================
    
    public function __construct() {
        parent::__construct();
        
        // Initialiser le modèle une seule fois
        $this->userModel = new User();
    }
    
    // ========================================
    // MÉTHODES PUBLIQUES
    // ========================================
    
    /**
     * Affiche le formulaire de connexion
     * @return string HTML du formulaire de connexion
     */
    public function login() {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->isUserLoggedIn()) {
            $this->redirect('/dashboard');
            return '';
        }
        
        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processLogin();
        }
        
        // Afficher le formulaire de connexion
        $data = [
            'title' => 'Connexion - How I Win My Home',
            'page' => 'login',
            'isLoggedIn' => $this->isUserLoggedIn(),
            'userRole' => $this->getCurrentUserRole()
        ];
        
        // Rediriger vers la page d'accueil car la connexion se fait via modale
        header('Location: /?modal=login');
        exit;
    }
    
    /**
     * Affiche le formulaire d'inscription
     * @return string HTML du formulaire d'inscription
     */
    public function register() {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->isUserLoggedIn()) {
            $this->redirect('/dashboard');
            return '';
        }
        
        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processRegister();
        }
        
        // Afficher le formulaire d'inscription
        $data = [
            'title' => 'Inscription - How I Win My Home',
            'page' => 'register',
            'isLoggedIn' => $this->isUserLoggedIn(),
            'userRole' => $this->getCurrentUserRole()
        ];
        
        // Rediriger vers la page d'accueil car l'inscription se fait via modale
        header('Location: /?modal=register');
        exit;
    }
    
    /**
     * Traite la connexion d'un utilisateur
     * @return string Redirection ou message d'erreur
     */
    public function processLogin() {
        try {
            // Validation CSRF gérée par SecurityMiddleware
            
            // Validation des données avec ValidationManager
            $data = [
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];
            
            $rules = [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ];
            
            $validationManager = new \App\Services\ValidationManager();
            $isValid = $validationManager->validate($data, $rules);
            $validationErrors = $validationManager->getErrors();
            
            error_log("Validation - isValid: " . ($isValid ? 'OUI' : 'NON') . ", Errors: " . json_encode($validationErrors));
            
            if (!$isValid || !empty($validationErrors)) {
                // Vérifier si c'est une requête AJAX
                if ($this->isAjaxRequest()) {
                    return $this->jsonResponse([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $validationErrors
                    ]);
                }
                
                // Rediriger vers la page d'accueil car la connexion se fait via modale
                header('Location: /');
                exit;
            }
            
            $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
            $password = $data['password']; // Ne pas sanitiser le mot de passe
            
            error_log("Données reçues - Email: " . $email . ", Password length: " . strlen($password));
            
            // Authentification
            error_log("Tentative de connexion pour: " . $email);
            $user = $this->userModel->authenticate($email, $password);
            error_log("Résultat authentification: " . ($user ? 'SUCCÈS' : 'ÉCHEC'));
            
            if ($user) {
                // Créer la session
                $this->createUserSession($user);
                
                // Vérifier si c'est une requête AJAX
                if ($this->isAjaxRequest()) {
                    return $this->jsonResponse([
                        'success' => true,
                        'message' => 'Connexion réussie ! Bienvenue ' . htmlspecialchars($user['first_name']),
                        'redirect' => '/'
                    ]);
                }
                
                // Pour les requêtes normales (non-AJAX)
                $this->redirect('/');
                return '';
            } else {
                // TOUJOURS retourner du JSON pour les modales (pas de toast)
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Email ou mot de passe incorrect',
                    'errors' => ['email' => 'Email ou mot de passe incorrect']
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Erreur lors de la connexion: " . $e->getMessage());
            
            // TOUJOURS retourner du JSON pour les modales (pas de toast)
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erreur de connexion. Vérifiez vos identifiants et réessayez.',
                'errors' => ['general' => 'Erreur de connexion']
            ]);
        }
    }
    
    /**
     * Traite l'inscription d'un nouvel utilisateur
     * @return string Redirection ou message d'erreur
     */
    public function processRegister() {
        try {
            // Validation CSRF gérée par SecurityMiddleware
            
            // Validation des données avec ValidationManager
            $data = [
                'firstname' => $_POST['firstname'] ?? '',
                'lastname' => $_POST['lastname'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'password_confirm' => $_POST['password_confirm'] ?? ''
            ];
            
            $rules = [
                'firstname' => 'required|min:2|max:50',
                'lastname' => 'required|min:2|max:50',
                'email' => 'required|email',
                'password' => 'required|min:8|regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).*$/',
                'password_confirm' => 'required'
            ];
            
            $validationManager = new \App\Services\ValidationManager();
            
            // Définir les messages d'erreur personnalisés
            $customMessages = [
                'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial (!@#$%^&*...)'
            ];
            $validationManager->setCustomMessages($customMessages);
            
            $isValid = $validationManager->validate($data, $rules);
            $validationErrors = $validationManager->getErrors();
            
            // Vérification de la correspondance des mots de passe
            if ($data['password'] !== $data['password_confirm']) {
                $validationErrors['password_confirm'] = ['Les mots de passe ne correspondent pas'];
            }
            
            if (!$isValid || !empty($validationErrors)) {
                // TOUJOURS retourner du JSON pour les modales (pas de toast)
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validationErrors
                ]);
            }
            
            $firstname = trim(strip_tags($data['firstname']));
            $lastname = trim(strip_tags($data['lastname']));
            $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
            $password = $data['password']; // Ne pas sanitiser le mot de passe
            $role = 'user'; // Tous les utilisateurs ont le rôle 'user' par défaut
            
            // Vérifier si l'email existe déjà
            if ($this->userModel->emailExists($email)) {
                // Log pour debug
                error_log("Tentative d'inscription avec email existant: " . $email);
                
                // TOUJOURS retourner du JSON pour les modales (pas de toast)
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Cette adresse email est déjà utilisée. Connectez-vous ou utilisez une autre adresse',
                    'errors' => ['email' => 'Cette adresse email est déjà utilisée']
                ]);
            }
            
            // Créer l'utilisateur
            $userId = $this->userModel->create($firstname, $lastname, $email, $password, $role);
            
            if ($userId) {
                // Connecter automatiquement l'utilisateur après l'inscription
                $user = $this->userModel->getById($userId);
                if ($user) {
                    $this->createUserSession($user);
                    
                    // TOUJOURS retourner du JSON pour les modales (pas de toast)
                                    return $this->jsonResponse([
                    'success' => true,
                    'message' => 'Inscription réussie ! Bienvenue ' . htmlspecialchars($user['first_name']),
                    'redirect' => '/'
                ]);
                }
            } else {
                // TOUJOURS retourner du JSON pour les modales (pas de toast)
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Impossible de créer votre compte. Veuillez réessayer ou contacter le support',
                    'errors' => ['general' => 'Erreur lors de la création du compte']
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Erreur lors de l'inscription: " . $e->getMessage());
            
            // TOUJOURS retourner du JSON pour les modales (pas de toast)
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription. Vérifiez vos informations et réessayez.',
                'errors' => ['general' => 'Erreur d\'inscription']
            ]);
        }
    }
    
    /**
     * Déconnecte l'utilisateur
     * @return string Redirection vers l'accueil
     */
    public function logout() {
        // Détruire la session
        $this->destroyUserSession();
        
        $this->redirect('/');
        return '';
    }
    
    /**
     * Valide les données de connexion en utilisant ValidationManager
     * @param array $data Données du formulaire
     * @return array Résultat de la validation
     */
    private function validateLoginData($data) {
        // Règles de validation pour la connexion
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
        
        // Messages personnalisés
        $messages = [
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'Format d\'email invalide',
            'password.required' => 'Le mot de passe est obligatoire'
        ];
        
        // Utiliser ValidationManager
        $this->validationManager = new \App\Services\ValidationManager($data, $rules);
        $this->validationManager->setCustomMessages($messages);
        
        $isValid = $this->validationManager->validate();
        
        return [
            'valid' => $isValid,
            'errors' => $this->validationManager->getErrors(),
            'message' => $isValid ? '' : 'Veuillez corriger les erreurs'
        ];
    }
    
    /**
     * Valide les données d'inscription en utilisant ValidationManager
     * @param array $data Données du formulaire
     * @return array Résultat de la validation
     */
    private function validateRegisterData($data) {
        // Règles de validation pour l'inscription
        $rules = [
            'firstname' => ['required', 'min:2', 'max:50'],
            'lastname' => ['required', 'min:2', 'max:50'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).*$/'],
            'password_confirm' => ['required', 'same:password']
        ];
        
        // Messages personnalisés
        $messages = [
            'firstname.required' => 'Le prénom est obligatoire',
            'firstname.min' => 'Le prénom doit contenir au moins 2 caractères',
            'firstname.max' => 'Le prénom ne peut pas dépasser 50 caractères',
            'lastname.required' => 'Le nom est obligatoire',
            'lastname.min' => 'Le nom doit contenir au moins 2 caractères',
            'lastname.max' => 'Le nom ne peut pas dépasser 50 caractères',
            'email.required' => 'L\'email est obligatoire',
            'email.email' => 'Format d\'email invalide',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial',
            'password_confirm.required' => 'La confirmation du mot de passe est obligatoire',
            'password_confirm.same' => 'Les mots de passe ne correspondent pas'
        ];
        
        // Utiliser ValidationManager
        $this->validationManager = new \App\Services\ValidationManager($data, $rules);
        $this->validationManager->setCustomMessages($messages);
        
        $isValid = $this->validationManager->validate();
        
        return [
            'valid' => $isValid,
            'errors' => $this->validationManager->getErrors(),
            'message' => $isValid ? '' : 'Veuillez corriger les erreurs'
        ];
    }
    
    /**
     * Crée la session utilisateur avec sécurité renforcée
     * @param array $user Données de l'utilisateur
     */
    private function createUserSession($user) {
        // Utiliser SecurityManager pour la sécurité de session
        $this->securityManager->startSecureSession();
        
        // Stocker les informations utilisateur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['last_name'];
        $_SESSION['user_prenom'] = $user['first_name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Régénérer l'ID de session pour la sécurité (géré par SecurityManager)
        session_regenerate_id(true);
    }
    
    /**
     * Détruit la session utilisateur avec sécurité renforcée
     */
    private function destroyUserSession() {
        // Utiliser SecurityManager pour nettoyer la session
        $this->securityManager->cleanupSession();
        
        // Vider toutes les variables de session
        $_SESSION = array();
        
        // Détruire le cookie de session
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Détruire la session
        session_destroy();
    }
    
    // Méthode isUserLoggedIn() supprimée - utilise celle de BaseController
    
    /**
     * Affiche le formulaire de mot de passe oublié
     * @return string HTML du formulaire
     */
    public function forgotPassword() {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->isUserLoggedIn()) {
            $this->redirect('/dashboard');
            return '';
        }
        
        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processForgotPassword();
        }
        
        // Afficher le formulaire
        $data = [
            'title' => 'Mot de passe oublié - How I Win My Home',
            'page' => 'forgot-password',
            'isLoggedIn' => $this->isUserLoggedIn(),
            'userRole' => $this->getCurrentUserRole(),
            'errors' => $_SESSION['forgot_password_errors'] ?? [],
            'message' => $_SESSION['forgot_password_message'] ?? null,
            'error' => $_SESSION['forgot_password_error'] ?? null
        ];
        
        // Nettoyer les messages de session après les avoir récupérés
        unset($_SESSION['forgot_password_errors'], $_SESSION['forgot_password_message'], $_SESSION['forgot_password_error']);
        
        return $this->renderLayout('auth/forgot-password', $data);
    }
    
    /**
     * Traite la demande de mot de passe oublié
     * @return string Message de confirmation
     */
    private function processForgotPassword() {
        try {
            // Validation CSRF gérée par SecurityMiddleware
            
            // Validation des données avec ValidationManager
            $data = [
                'email' => $_POST['email'] ?? ''
            ];
            
            $rules = [
                'email' => 'required|email'
            ];
            
            $validationManager = new \App\Services\ValidationManager();
            $isValid = $validationManager->validate($data, $rules);
            $validationErrors = $validationManager->getErrors();
            
            if (!$isValid || !empty($validationErrors)) {
                // Rediriger avec les erreurs de validation
                $_SESSION['forgot_password_errors'] = $validationErrors;
                return $this->redirect('/forgot-password');
            }
            
            $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
            
            // Vérifier que l'email existe
            $user = $this->userModel->getByEmail($email);
            
            if (!$user) {
                // Pour la sécurité, ne pas révéler si l'email existe ou non
                // En V1, on affiche juste un message générique
                $_SESSION['forgot_password_message'] = 'Si cette adresse email existe dans notre système, vous recevrez un email avec les instructions de réinitialisation.';
                return $this->redirect('/forgot-password');
            }
            
            // VERSION 1.0 - Simulation du processus
            // En production, on générerait un token et on enverrait un email
            // Pour la V1, on simule juste le processus
            
            $_SESSION['forgot_password_message'] = 'Si cette adresse email existe dans notre système, vous recevrez un email avec les instructions de réinitialisation.';
            return $this->redirect('/forgot-password');
            
        } catch (Exception $e) {
            error_log("Erreur lors de la demande de mot de passe oublié: " . $e->getMessage());
            $_SESSION['forgot_password_error'] = 'Une erreur est survenue. Veuillez réessayer.';
            return $this->redirect('/forgot-password');
        }
    }
    
    /**
     * Génère un message d'erreur personnalisé pour la connexion
     * 
     * @param array $errors Les erreurs de validation
     * @return string Message d'erreur personnalisé
     */
    private function getLoginErrorMessage($errors) {
        // Utiliser le premier message d'erreur disponible
        if (!empty($errors)) {
            return reset($errors);
        }
        
        return 'Veuillez vérifier vos informations de connexion';
    }
    
    /**
     * Génère un message d'erreur personnalisé pour l'inscription
     * 
     * @param array $errors Les erreurs de validation
     * @return string Message d'erreur personnalisé
     */
    private function getRegisterErrorMessage($errors) {
        // Utiliser le premier message d'erreur disponible
        if (!empty($errors)) {
            return reset($errors);
        }
        
        return 'Veuillez vérifier les informations saisies';
    }
    
    // Méthode validatePasswordStrength() supprimée - gérée par ValidationManager
} 
