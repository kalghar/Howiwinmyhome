<?php

namespace App\Services;

/**
 * GESTIONNAIRE DE SÉCURITÉ SIMPLIFIÉ
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Ce service gère les fonctionnalités de sécurité essentielles
 * Parfait pour un examen : complet mais facile à expliquer
 *
 * @author How I Win My Home Team
 * @version 2.0.0 (Simplifié)
 * @since 2025-08-12
 */

class SecurityManager {
    
    /**
     * Clé de session pour les tokens CSRF
     */
    private const CSRF_SESSION_KEY = 'csrf_tokens';
    
    /**
     * Durée de vie des tokens CSRF (1 heure)
     */
    private const CSRF_TOKEN_LIFETIME = 3600;
    
    /**
     * Instance unique du SecurityManager
     */
    private static $instance = null;
    
    /**
     * Constructeur privé (Singleton)
     */
    private function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            $this->startSecureSession();
        }
    }
    
    /**
     * Obtient l'instance unique
     */
    public static function getInstance(): SecurityManager {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Démarre une session sécurisée
     */
    public function startSecureSession(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        
        // Configuration de base de la session
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 0); // DEV: 0, PROD: 1
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.use_strict_mode', 1);
        
        session_start();
        
        // Régénérer l'ID de session
        if (!isset($_SESSION['last_regeneration'])) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
    
    /**
     * Génère un token CSRF
     */
    public function generateCsrfToken(string $identifier): string {
        $token = bin2hex(random_bytes(32));
        $timestamp = time();
        
        $_SESSION[self::CSRF_SESSION_KEY][$identifier] = [
            'token' => $token,
            'timestamp' => $timestamp
        ];
        
        return $token;
    }
    
    /**
     * Vérifie un token CSRF
     */
    public function verifyCsrfToken(string $identifier, string $token): bool {
        if (!isset($_SESSION[self::CSRF_SESSION_KEY][$identifier])) {
            return false;
        }
        
        $storedData = $_SESSION[self::CSRF_SESSION_KEY][$identifier];
        
        // Vérifier la durée de vie
        if (time() - $storedData['timestamp'] > self::CSRF_TOKEN_LIFETIME) {
            unset($_SESSION[self::CSRF_SESSION_KEY][$identifier]);
            return false;
        }
        
        // Vérifier le token
        if (!hash_equals($storedData['token'], $token)) {
            return false;
        }
        
        // Consommer le token (usage unique)
        unset($_SESSION[self::CSRF_SESSION_KEY][$identifier]);
        
        return true;
    }
    
    /**
     * Génère un champ caché CSRF pour les formulaires
     */
    public function generateCsrfField(string $identifier): string {
        $token = $this->generateCsrfToken($identifier);
        return sprintf(
            '<input type="hidden" name="csrf_token" value="%s">',
            htmlspecialchars($token, ENT_QUOTES, 'UTF-8')
        );
    }
    
    /**
     * Nettoie une chaîne de caractères pour éviter le XSS
     */
    public function sanitizeInput(string $input, bool $allowHtml = false): string {
        if ($allowHtml) {
            // Permettre le HTML mais nettoyer les attributs dangereux
            $allowedTags = '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6>';
            $input = strip_tags($input, $allowedTags);
            
            // Nettoyer les attributs dangereux
            $input = preg_replace('/<[^>]*javascript:/i', '', $input);
            $input = preg_replace('/<[^>]*on\w+\s*=/i', '', $input);
        } else {
            // Pas de HTML du tout
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        
        return trim($input);
    }
    
    /**
     * Nettoie un tableau de données
     */
    public function sanitizeArray(array $data, bool $allowHtml = false): array {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = $this->sanitizeInput($value, $allowHtml);
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value, $allowHtml);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Envoie les headers de sécurité de base
     */
    public function sendSecurityHeaders(): void {
        $headers = [
            'X-Content-Type-Options: nosniff',
            'X-Frame-Options: DENY',
            'X-XSS-Protection: 1; mode=block',
            'Referrer-Policy: strict-origin-when-cross-origin'
        ];
        
        foreach ($headers as $header) {
            if (!headers_sent()) {
                header($header);
            }
        }
    }
    
    /**
     * Hash un mot de passe de manière sécurisée
     */
    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Vérifie un mot de passe
     */
    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }
    
    /**
     * Génère une chaîne aléatoire sécurisée
     */
    public function generateRandomString(int $length = 32): string {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Nettoie les données de session
     */
    public function cleanupSession(): void {
        // Nettoyer les tokens CSRF expirés
        if (isset($_SESSION[self::CSRF_SESSION_KEY])) {
            foreach ($_SESSION[self::CSRF_SESSION_KEY] as $identifier => $data) {
                if (time() - $data['timestamp'] > self::CSRF_TOKEN_LIFETIME) {
                    unset($_SESSION[self::CSRF_SESSION_KEY][$identifier]);
                }
            }
        }
    }
}