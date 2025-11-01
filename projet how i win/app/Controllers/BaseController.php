<?php

/**
 * CLASSE DE BASE POUR TOUS LES CONTRÔLEURS
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Cette classe abstraite fournit les fonctionnalités communes
 * à tous les contrôleurs de l'application :
 * - Système de rendu de vues unifié via LayoutManager
 * - Gestion des réponses HTTP directes
 * - Validation des données via ValidationManager
 * - Gestion des assets via LayoutManager
 *
 * FONCTIONNALITÉS PRINCIPALES :
 * - renderView() : Rendu simple d'une vue
 * - renderLayout() : Rendu avec layout complet (header + vue + footer)
 * - redirect() : Redirection avec messages flash
 * - jsonResponse() : Réponse JSON pour les API
 * - validateRequest() : Validation des données de requête
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-08-15
 * ========================================
 */

// Inclure les services nécessaires
require_once __DIR__ . '/../Services/LayoutManager.php';
require_once __DIR__ . '/../Services/ValidationManager.php';
require_once __DIR__ . '/../Services/SecurityManager.php';

abstract class BaseController
{

    // ========================================
    // PROPRIÉTÉS PROTÉGÉES
    // ========================================

    /**
     * Données à passer aux vues
     * 
     * @var array
     */
    protected $data = [];

    /**
     * Titre de la page courante
     * 
     * @var string
     */
    protected $pageTitle = '';

    /**
     * Description de la page pour le SEO
     * 
     * @var string
     */
    protected $pageDescription = '';

    /**
     * Nom de la page pour le chargement des assets
     * 
     * @var string
     */
    protected $pageName = '';

    // ========================================
    // SERVICES
    // ========================================

    /**
     * Gestionnaire de layout
     * 
     * @var LayoutManager
     */
    protected $layoutManager;

    /**
     * Gestionnaire de validation
     * 
     * @var ValidationManager
     */
    protected $validationManager;

    /**
     * Gestionnaire de sécurité
     * 
     * @var SecurityManager
     */
    protected $securityManager;

    // ========================================
    // CONSTRUCTEUR
    // ========================================

    public function __construct()
    {
        // Initialisation des services de base (V1)
        $this->layoutManager = new LayoutManager();
        $this->validationManager = new \App\Services\ValidationManager();

        // Service de sécurité
        $this->securityManager = \App\Services\SecurityManager::getInstance();

        // Initialisation des données de base
        $this->initializeBaseData();

        // Envoi des headers de sécurité (seulement si pas de contenu déjà envoyé)
        if (!headers_sent()) {
            $this->securityManager->sendSecurityHeaders();
        }
    }

    // ========================================
    // INITIALISATION
    // ========================================

    /**
     * Initialise les données de base communes à toutes les pages
     * 
     * @return void
     */
    protected function initializeBaseData(): void
    {
        // Debug pour vérifier l'initialisation de l'application
        try {
            $baseUrl = App::getBaseUrl();
        } catch (Exception $e) {
            error_log("ERREUR: Application non initialisée dans BaseController: " . $e->getMessage());
            // Utiliser une URL par défaut en cas d'erreur
            $baseUrl = 'http://localhost:8080';
        }

        $this->data = array_merge($this->data, [
            'pageTitle' => $this->pageTitle ?: 'How I WIN MY HOME',
            'pageDescription' => $this->pageDescription ?: 'Plateforme de concours immobilier',
            'currentYear' => date('Y'),
            'baseUrl' => $baseUrl,
            'pageName' => $this->pageName ?: $this->getPageNameFromClass(),
            'page' => $this->pageName ?: $this->getPageNameFromClass()
        ]);
    }

    /**
     * Obtient le nom de la page à partir du nom de la classe
     * 
     * @return string Le nom de la page
     */
    private function getPageNameFromClass(): string
    {
        $className = static::class;
        $controllerName = str_replace('Controller', '', $className);
        $controllerName = str_replace('app\\Controllers\\', '', $controllerName);

        return strtolower($controllerName);
    }

    // ========================================
    // MÉTHODES DE RENDU PRINCIPALES
    // ========================================

    /**
     * Rendu d'une vue avec les données fournies
     * 
     * @param string $viewPath Le chemin vers la vue (ex: 'listings/index')
     * @param array $data Les données à passer à la vue
     * @return string Le contenu HTML généré
     */
    protected function renderView(string $viewPath, array $data = []): string
    {
        return $this->layoutManager->renderView($viewPath, array_merge($this->data, $data));
    }

    /**
     * Rendu d'un layout complet avec header, vue et footer
     * 
     * @param string $viewPath Le chemin vers la vue principale
     * @param array $data Les données à passer à la vue
     * @return string Le contenu HTML complet généré
     */
    protected function renderLayout(string $viewPath, array $data = []): string
    {
        // Fusionner les données
        $layoutData = array_merge($this->data, $data);

        // Rendre le layout complet via LayoutManager (qui gère les assets)
        return $this->layoutManager->renderLayout($viewPath, $layoutData);
    }

    /**
     * Rendu d'un partial (composant réutilisable)
     * 
     * @param string $partialName Le nom du partial (ex: 'navigation')
     * @param array $data Les données à passer au partial
     * @return string Le contenu HTML du partial
     */
    protected function renderPartial(string $partialName, array $data = []): string
    {
        return $this->layoutManager->renderPartial($partialName, array_merge($this->data, $data));
    }

    // ========================================
    // MÉTHODES DE RÉPONSE HTTP
    // ========================================

    /**
     * Vérifie si la requête est une requête AJAX
     * 
     * @return bool
     */
    protected function isAjaxRequest(): bool
    {
        // Vérifier le header X-Requested-With
        if (
            !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
        ) {
            return false;
        }

        // Vérifier que ce n'est pas un bot/script
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (empty($userAgent) || str_contains($userAgent, 'curl') || str_contains($userAgent, 'wget')) {
            return false;
        }

        return true;
    }

    /**
     * Valide que les données POST sont valides
     * 
     * @param array $requiredFields Champs obligatoires
     * @return bool true si les données sont valides
     */
    protected function validatePostData(array $requiredFields = []): bool
    {
        // Vérifier que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }

        // Vérifier les champs obligatoires
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valide un token CSRF simple
     * 
     * @param string $token Le token à valider
     * @return bool true si le token est valide, false sinon
     */
    protected function validateCsrfToken(string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['csrf_token']) &&
            hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Génère un token CSRF simple pour les formulaires
     * 
     * @return string Le token CSRF généré
     */
    protected function generateSimpleCsrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Génère un champ CSRF caché pour les formulaires
     * 
     * @return string Le HTML du champ caché avec le token
     */
    protected function csrfField(): string
    {
        $token = $this->generateSimpleCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Envoie une réponse JSON
     * 
     * @param mixed $data Les données à envoyer
     * @param int $statusCode Le code de statut HTTP
     * @param array $headers Headers additionnels
     * @return void
     */
    protected function jsonResponse($data, $statusCode = 200, $headers = []): void
    {
        // Envoyer les headers
        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            foreach ($headers as $name => $value) {
                header("$name: $value");
            }
        }

        // Envoyer la réponse JSON
        echo json_encode($data);
        exit;
    }

    /**
     * Redirige vers une URL avec un message flash
     * 
     * @param string $url L'URL de redirection
     * @param string $message Le message à afficher
     * @param string $type Le type de message
     * @param int $statusCode Le code de statut HTTP
     * @return void
     */
    protected function redirect($url, $message = '', $type = 'info', $statusCode = 302): void
    {
        // Plus de messages flash - suppression complète

        // Redirection
        if (!headers_sent()) {
            header("Location: $url", true, $statusCode);
            exit;
        }
    }

    /**
     * Redirige vers la page précédente
     * 
     * @param string $message Le message à afficher
     * @param string $type Le type de message
     * @param string $fallbackUrl URL de fallback
     * @return void
     */
    protected function redirectBack($message = '', $type = 'info', $fallbackUrl = '/'): void
    {
        // Plus de messages flash - suppression complète

        // Redirection vers la page précédente ou fallback
        $referer = $_SERVER['HTTP_REFERER'] ?? $fallbackUrl;
        if (!headers_sent()) {
            header("Location: $referer");
            exit;
        }
    }

    /**
     * Envoie une réponse d'erreur
     * 
     * @param int $statusCode Le code de statut HTTP
     * @param string $message Le message d'erreur
     * @param array $details Détails supplémentaires
     * @return void
     */
    protected function errorResponse($statusCode, $message = '', $details = []): void
    {
        // Définir le code de statut HTTP
        if (!headers_sent()) {
            http_response_code($statusCode);
        }

        // Afficher l'erreur
        echo "<h1>Erreur $statusCode</h1>";
        if (!empty($message)) {
            echo "<p>" . htmlspecialchars($message) . "</p>";
        }
        if (!empty($details)) {
            echo "<pre>" . htmlspecialchars(json_encode($details, JSON_PRETTY_PRINT)) . "</pre>";
        }
        exit;
    }

    /**
     * Envoie une réponse de succès
     * 
     * @param mixed $data Les données de la réponse
     * @param string $message Le message de succès
     * @param int $statusCode Le code de statut HTTP
     * @return void
     */
    protected function successResponse($data = null, $message = 'Opération réussie', $statusCode = 200): void
    {
        // Définir le code de statut HTTP
        if (!headers_sent()) {
            http_response_code($statusCode);
        }

        // Afficher le succès
        echo "<h1>Succès</h1>";
        if (!empty($message)) {
            echo "<p>" . htmlspecialchars($message) . "</p>";
        }
        if ($data !== null) {
            echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
        }
        exit;
    }

    // ========================================
    // MÉTHODES DE VALIDATION
    // ========================================

    /**
     * Valide les données de requête selon des règles
     * 
     * @param array $data Les données à valider
     * @param array $rules Les règles de validation
     * @param array $customMessages Messages personnalisés
     * @return bool True si la validation réussit
     */
    protected function validateRequest(array $data, array $rules, array $customMessages = []): bool
    {
        $this->validationManager = new \App\Services\ValidationManager();

        return $this->validationManager->validate($data, $rules);
    }

    /**
     * Récupère les erreurs de validation
     * 
     * @return array Les erreurs de validation
     */
    protected function getValidationErrors(): array
    {
        return $this->validationManager ? $this->validationManager->getErrors() : [];
    }

    /**
     * Vérifie s'il y a des erreurs de validation
     * 
     * @return bool True s'il y a des erreurs
     */
    protected function hasValidationErrors(): bool
    {
        return $this->validationManager ? $this->validationManager->hasErrors() : false;
    }

    /**
     * Retourne une réponse JSON standardisée
     * 
     * @param bool $success Statut de la réponse
     * @param string $message Message de la réponse
     * @param array $errors Erreurs éventuelles
     * @param array $data Données supplémentaires
     * @return void
     */
    protected function returnJsonResponse(bool $success, string $message, array $errors = [], array $data = []): void
    {
        http_response_code($success ? 200 : 400);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
            'data' => $data
        ]);
        exit;
    }

    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================


    // Méthodes AssetManager supprimées - gérées par LayoutManager

    // ========================================
    // MÉTHODES DE SÉCURITÉ
    // ========================================

    /**
     * Nettoie et sécurise les données d'entrée
     * 
     * @param array $data Les données à nettoyer
     * @param bool $allowHtml Permettre le HTML
     * @return array Les données nettoyées
     */
    protected function sanitizeInput(array $data, bool $allowHtml = false): array
    {
        return $this->securityManager->sanitizeArray($data, $allowHtml);
    }

    // Méthodes validateEmail() et validateUrl() supprimées - utilise ValidationManager

    /**
     * Génère un token CSRF
     * 
     * @param string $identifier Identifiant unique
     * @return string Le token CSRF
     */
    protected function generateCsrfToken(string $identifier): string
    {
        return $this->securityManager->generateCsrfToken($identifier);
    }

    /**
     * Vérifie un token CSRF
     * 
     * @param string $identifier Identifiant du token
     * @param string $token Le token à vérifier
     * @return bool True si le token est valide
     */
    protected function verifyCsrfToken(string $identifier, string $token): bool
    {
        return $this->securityManager->verifyCsrfToken($identifier, $token);
    }

    /**
     * Génère un champ caché CSRF
     * 
     * @param string $identifier Identifiant du token
     * @return string Le HTML du champ caché
     */
    protected function generateCsrfField(string $identifier): string
    {
        return $this->securityManager->generateCsrfField($identifier);
    }

    // ========================================
    // MÉTHODES DE GESTION D'ERREUR
    // ========================================

    /**
     * Gère les erreurs de manière centralisée
     * 
     * @param Exception $exception L'exception à gérer
     * @param string $viewPath Le chemin de la vue d'erreur
     * @return string Le contenu HTML de la page d'erreur
     */
    protected function handleError(Exception $exception, $viewPath = 'errors/error'): string
    {
        $errorData = [
            'error' => $exception->getMessage(),
            'code' => $exception->getCode() ?: 500,
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];

        // Log de l'erreur
        error_log("Erreur dans " . static::class . ": " . $exception->getMessage());

        // Rendu de la page d'erreur
        return $this->renderView($viewPath, $errorData);
    }

    // ========================================
    // MÉTHODES DE GESTION DES UTILISATEURS
    // ========================================

    /**
     * Vérifie si un utilisateur est connecté
     * 
     * @return bool True si l'utilisateur est connecté
     */
    protected function isUserLoggedIn(): bool
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Obtient le rôle de l'utilisateur connecté
     * 
     * @return string|null Le rôle de l'utilisateur ou null si non connecté
     */
    protected function getCurrentUserRole(): ?string
    {
        if (!$this->isUserLoggedIn()) {
            return null;
        }

        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Obtient l'ID de l'utilisateur connecté
     * 
     * @return int|null L'ID de l'utilisateur ou null si non connecté
     */
    protected function getCurrentUserId(): ?int
    {
        if (!$this->isUserLoggedIn()) {
            return null;
        }

        return (int) $_SESSION['user_id'];
    }

    // ========================================
    // MÉTHODES DE VÉRIFICATION DES RÔLES
    // ========================================

    /**
     * Vérifie si l'utilisateur connecté a un rôle spécifique
     * 
     * @param string $role Le rôle à vérifier
     * @return bool True si l'utilisateur a le rôle
     */
    protected function hasRole(string $role): bool
    {
        if (!$this->isUserLoggedIn()) {
            return false;
        }

        $userRole = $this->getCurrentUserRole();
        return $userRole === $role;
    }

    /**
     * Vérifie que l'utilisateur a le rôle jury, sinon redirige
     * 
     * @return bool True si l'utilisateur a le rôle jury
     */
    protected function requireJuryRole(): bool
    {
        if (!$this->hasRole('jury')) {
            $this->redirect('/dashboard');
            return false;
        }

        return true;
    }

    /**
     * Vérifie que l'utilisateur a le rôle admin, sinon redirige
     * 
     * @return bool True si l'utilisateur a le rôle admin
     */
    protected function requireAdminRole(): bool
    {
        if (!$this->hasRole('admin')) {
            $this->redirect('/dashboard');
            return false;
        }

        return true;
    }

    // ========================================
    // MÉTHODES DE GESTION DES MESSAGES FLASH
    // ========================================

    // ========================================
    // MÉTHODES FLASH SUPPRIMÉES
    // ========================================

    // CORRECTION : Messages flash supprimés pour simplifier l'application

    // ========================================
    // GESTION D'ERREURS AMÉLIORÉE
    // ========================================

    /**
     * Log une erreur avec contexte
     * @param string $message Message d'erreur
     * @param array $context Contexte additionnel
     * @param string $level Niveau de log (error, warning, info)
     */
    protected function logError(string $message, array $context = [], string $level = 'error'): void
    {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'user_id' => $this->getCurrentUserId(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'context' => $context
        ];

        error_log("[$level] " . json_encode($logData));
    }

    /**
     * Gère une exception avec logging et redirection
     * @param Exception $e Exception à gérer
     * @param string $redirectUrl URL de redirection
     * @param string $userMessage Message à afficher à l'utilisateur
     */
    protected function handleException(Exception $e, string $redirectUrl = '/', string $userMessage = 'Une erreur s\'est produite'): void
    {
        $this->logError($e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        // En mode DEV, afficher l'erreur
        if (defined('APP_ENV') && APP_ENV === 'development') {
            throw $e;
        }

        // En mode PROD, rediriger avec message générique
        $this->redirect($redirectUrl);
    }

    // ========================================
    // MÉTHODES COMMUNES POUR ÉLIMINER LA DUPLICATION
    // ========================================

    /**
     * Initialise les modèles communs (à surcharger dans les contrôleurs enfants)
     * @param array $models Liste des modèles à initialiser
     */
    protected function initializeModels(array $models = []): void
    {
        // Cette méthode peut être surchargée par les contrôleurs enfants
        // pour initialiser leurs modèles spécifiques
    }



    /**
     * Vérifie que l'utilisateur est connecté avec redirection automatique
     * @return bool True si l'utilisateur est connecté
     */
    protected function requireLogin(): bool
    {
        if (!$this->isUserLoggedIn()) {
            $this->redirect('/auth/login');
            return false;
        }

        return true;
    }

    /**
     * Valide une requête POST avec redirection automatique
     * @param string $redirectUrl URL de redirection en cas d'erreur
     * @return bool True si la requête est valide
     */
    protected function requirePostRequest(string $redirectUrl = '/'): bool
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($redirectUrl);
            return false;
        }

        return true;
    }
}
