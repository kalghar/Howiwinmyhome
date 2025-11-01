<?php

/**
 * FICHIER DE CONFIGURATION EXEMPLE - HOW I WIN MY HOME
 * 
 * INSTRUCTIONS :
 * 1. Copier ce fichier en config.php
 * 2. Remplir les valeurs avec vos propres credentials
 * 3. Ne JAMAIS committer le fichier config.php
 */

// ========================================
// CONFIGURATION GÉNÉRALE
// ========================================

// Informations de l'application
define('APP_NAME', 'How I Win My Home');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'development'); // development, staging, production

// URLs et chemins
define('BASE_URL', 'http://localhost');
define('APP_PATH', __DIR__ . '/..');
define('PUBLIC_PATH', APP_PATH . '/../public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// ========================================
// CONFIGURATION DE LA BASE DE DONNÉES
// ========================================

define('DB_HOST', 'your_db_host');
define('DB_NAME', 'your_db_name');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_secure_password');
define('DB_CHARSET', 'utf8mb4');

// ========================================
// CONFIGURATION DES SESSIONS
// ========================================

define('SESSION_NAME', 'HOWIWIN_SESSION');
define('SESSION_LIFETIME', 3600); // 1 heure
define('CSRF_LIFETIME', 1800); // 30 minutes

// ========================================
// CONFIGURATION DE SÉCURITÉ
// ========================================

define('PASSWORD_MIN_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// ========================================
// CONFIGURATION DES UPLOADS
// ========================================

define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif']);
define('ALLOWED_DOCUMENT_TYPES', ['pdf', 'doc', 'docx']);

// ========================================
// CONFIGURATION DU JEU
// ========================================

define('QCM_TIME_LIMIT', 300); // 5 minutes
define('QCM_QUESTIONS_COUNT', 10);
define('QCM_PASSING_SCORE', 70); // 70%
define('LETTER_MIN_LENGTH', 50);
define('LETTER_MAX_LENGTH', 2000);

// ========================================
// CONFIGURATION DES EMAILS
// ========================================

define('SMTP_HOST', 'your_smtp_host');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your_smtp_user');
define('SMTP_PASS', 'your_smtp_password');
define('FROM_EMAIL', 'noreply@howiwinmyhome.com');
define('FROM_NAME', 'How I Win My Home');

// ========================================
// CONFIGURATION SELON L'ENVIRONNEMENT
// ========================================

if (APP_ENV === 'production') {
    // Production
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    // HTTPS obligatoire en production
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit;
    }
} elseif (APP_ENV === 'staging') {
    // Staging
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);
} else {
    // Development
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);
}

// ========================================
// CONFIGURATION GÉNÉRALE PHP
// ========================================

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Encodage
ini_set('default_charset', 'UTF-8');

// Limites
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 30);

// ========================================
// FONCTIONS UTILITAIRES
// ========================================

/**
 * Obtient une configuration
 */
function config($key, $default = null)
{
    $config = [
        'app.name' => APP_NAME,
        'app.version' => APP_VERSION,
        'app.env' => APP_ENV,
        'app.base_url' => BASE_URL,
        'db.host' => DB_HOST,
        'db.name' => DB_NAME,
        'db.user' => DB_USER,
        'db.pass' => DB_PASS,
        'session.name' => SESSION_NAME,
        'session.lifetime' => SESSION_LIFETIME,
        'csrf.lifetime' => CSRF_LIFETIME,
        'upload.max_size' => MAX_FILE_SIZE,
        'qcm.time_limit' => QCM_TIME_LIMIT,
        'qcm.questions_count' => QCM_QUESTIONS_COUNT,
        'qcm.passing_score' => QCM_PASSING_SCORE,
        'letter.min_length' => LETTER_MIN_LENGTH,
        'letter.max_length' => LETTER_MAX_LENGTH,
    ];

    return $config[$key] ?? $default;
}

/**
 * Vérifie si on est en développement
 */
function isDevelopment()
{
    return APP_ENV === 'development';
}

/**
 * Vérifie si on est en production
 */
function isProduction()
{
    return APP_ENV === 'production';
}

/**
 * Génère une URL complète
 */
function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Génère le chemin vers un asset
 */
function asset($path)
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}
