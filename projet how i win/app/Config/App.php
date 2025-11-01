<?php
/**
 * Classe App simplifiée - How I Win My Home
 * 
 * Gère l'initialisation et les fonctionnalités de base de l'application
 */

class App {
    
    private static $initialized = false;
    
    /**
     * Initialise l'application
     */
    public static function init() {
        if (self::$initialized) {
            return;
        }
        
        // Charger la configuration
        require_once __DIR__ . '/config.php';
        
        // Configurer les sessions
        self::configureSessions();
        
        // Configurer l'autoloader
        self::configureAutoloader();
        
        // Configurer la base de données
        self::configureDatabase();
        
        self::$initialized = true;
    }
    
    /**
     * Configure les sessions
     */
    private static function configureSessions() {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path' => '/',
                'domain' => '',
                'secure' => isProduction(),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
        
        // Sécuriser la session
        self::secureSession();
    }
    
    /**
     * Configure l'autoloader
     */
    private static function configureAutoloader() {
        require_once __DIR__ . '/Autoloader.php';
        Autoloader::init();
    }
    
    /**
     * Configure la base de données
     */
    private static function configureDatabase() {
        require_once __DIR__ . '/Database.php';
    }
    
    /**
     * Sécurise la session
     */
    private static function secureSession() {
        // Régénérer l'ID de session si c'est la première fois
        if (!isset($_SESSION['initialized'])) {
            if (!headers_sent()) {
                session_regenerate_id(true);
            }
            $_SESSION['initialized'] = true;
            $_SESSION['created_at'] = time();
        }
        
        // Vérifier l'expiration de la session
        if (isset($_SESSION['created_at']) && 
            (time() - $_SESSION['created_at']) > SESSION_LIFETIME) {
            session_destroy();
            header('Location: ' . url('auth/login?expired=1'));
            exit;
        }
        
        // Mettre à jour le timestamp de dernière activité
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Génère un token CSRF
     */
    public static function generateCSRFToken() {
        // Vérifier si un token existe déjà et n'est pas expiré
        if (isset($_SESSION['csrf_token']) && 
            isset($_SESSION['csrf_token_time']) &&
            (time() - $_SESSION['csrf_token_time']) < CSRF_LIFETIME) {
            return $_SESSION['csrf_token'];
        }
        
        // Générer un nouveau token sécurisé
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        $_SESSION['csrf_token_time'] = time();
        
        return $token;
    }
    
    /**
     * Vérifie un token CSRF
     */
    public static function verifyCSRFToken($token) {
        // Vérifier que le token existe en session
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
            return false;
        }
        
        // Vérifier que le token n'est pas expiré
        if ((time() - $_SESSION['csrf_token_time']) > CSRF_LIFETIME) {
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
            return false;
        }
        
        // Comparer les tokens avec une comparaison sécurisée
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Valide un token CSRF et redirige si invalide
     */
    public static function validateCSRFToken($token, $redirectUrl = '/auth/login') {
        if (!self::verifyCSRFToken($token)) {
            // Log de la tentative d'attaque CSRF
            error_log("Tentative d'attaque CSRF détectée - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            
            // Rediriger vers la page de connexion
            header("Location: " . url($redirectUrl . "?error=csrf"));
            exit;
        }
        
        return true;
    }
    
    /**
     * Redirige vers une URL avec un message flash
     */
    public static function redirect($url, $message = '', $type = 'info') {
        if ($message) {
            $_SESSION['flash_messages'][$type][] = $message;
        }
        
        header("Location: " . url($url));
        exit;
    }
    
    /**
     * Obtient l'URL de base
     */
    public static function getBaseUrl() {
        return BASE_URL;
    }
    
    /**
     * Obtient le nom de l'application
     */
    public static function getAppName() {
        return APP_NAME;
    }
    
    /**
     * Obtient la version de l'application
     */
    public static function getAppVersion() {
        return APP_VERSION;
    }
}