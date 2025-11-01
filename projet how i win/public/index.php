<?php
/**
 * Point d'entrée principal de l'application
 * HOW I WIN MY HOME V1 - Architecture MVC
 * 
 * Ce fichier remplace l'ancien système de routage manuel
 * par un système qui utilise l'autoloader et les routes définies
 */

// Inclure et initialiser l'application
require_once __DIR__ . '/../app/Config/App.php';
App::init();

// Initialiser le middleware de sécurité
try {
    error_log("Tentative d'initialisation du SecurityMiddleware...");
    $securityMiddleware = new \App\Middleware\SecurityMiddleware();
    $securityMiddleware->process();
    error_log("SecurityMiddleware initialisé avec succès");
} catch (Exception $e) {
    error_log("Erreur lors de l'initialisation du SecurityMiddleware: " . $e->getMessage());
}

// Charger la configuration des routes
$routes = require_once __DIR__ . '/../app/Config/Routes.php';

// Traiter la requête selon les routes définies
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
$path = trim($path, '/');

// Route par défaut
if (empty($path)) {
    $path = 'home';
}

// Trouver la route correspondante
$route = null;
$routeParams = [];

foreach ($routes as $routePattern => $routeConfig) {
    // Correspondance exacte
    if ($routePattern === $path) {
        $route = $routeConfig;
        break;
    }
    
    // Correspondance avec paramètres dynamiques
    if (strpos($routePattern, '{') !== false) {
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePattern);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $path, $matches)) {
            $route = $routeConfig;
            // Extraire les paramètres
            array_shift($matches); // Supprimer le match complet
            $routeParams = $matches;
            break;
        }
    }
}

// Gestion des routes non trouvées
if (!$route) {
    http_response_code(404);
    echo "<h1>404 - Page non trouvée</h1>";
    echo "<p>La page demandée n'existe pas.</p>";
    echo "<a href='/'>Retour à l'accueil</a>";
    exit;
}

// Exécuter le contrôleur
$controllerName = $route['controller'];
$actionName = $route['action'];

// Vérifier que le contrôleur existe
if (!class_exists($controllerName)) {
    http_response_code(500);
    echo "<h1>Erreur - Contrôleur non trouvé</h1>";
    echo "<p>Le contrôleur '$controllerName' n'existe pas.</p>";
    exit;
}


// Créer l'instance du contrôleur
$controller = new $controllerName();

// Vérifier que l'action existe
if (!method_exists($controller, $actionName)) {
    http_response_code(500);
    echo "<h1>Erreur - Action non trouvée</h1>";
    echo "<p>L'action '$actionName' n'existe pas dans le contrôleur '$controllerName'.</p>";
    exit;
}

// Exécuter l'action et afficher le résultat
try {
    // Passer les paramètres de route à l'action
    if (!empty($routeParams)) {
        $result = call_user_func_array([$controller, $actionName], $routeParams);
    } else {
        $result = $controller->$actionName();
    }
    
    if ($result !== null) {
        echo $result;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "<h1>Erreur interne du serveur</h1>";
    if (defined('APP_ENV') && APP_ENV === 'development') {
        echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>Fichier : " . htmlspecialchars($e->getFile()) . "</p>";
        echo "<p>Ligne : " . $e->getLine() . "</p>";
    } else {
        echo "<p>Une erreur s'est produite. Veuillez réessayer plus tard.</p>";
    }
} 