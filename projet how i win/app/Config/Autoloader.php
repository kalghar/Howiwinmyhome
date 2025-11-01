<?php
/**
 * Autoloader personnalisé pour l'application How I Win My Home
 * 
 * Ce fichier implémente un système d'autoloading automatique des classes
 * selon la structure de dossiers de l'application MVC. Il utilise la
 * fonction spl_autoload_register() de PHP pour charger les classes
 * automatiquement quand elles sont utilisées.
 * 
 * Structure de mapping :
 * - Controllers : app/Controllers/
 * - Models : app/Models/
 * - Views : app/Views/
 * - Config : app/Config/
 * - Helpers : app/Helpers/
 * - Services : app/Services/
 * 
 * @author How I Win My Home Team
 * @version 2.0.0
 * @since 2025-08-12
 */

class Autoloader {
    
    // ========================================
    // PROPRIÉTÉS DE LA CLASSE
    // ========================================
    
    /**
     * Dossier racine de l'application
     * 
     * @var string
     */
    private static $basePath;
    
    /**
     * Mapping des namespaces vers les dossiers
     * 
     * @var array
     */
    private static $namespaceMap = [
        'App\\Controllers\\' => 'app/Controllers/',
        'App\\Models\\' => 'app/Models/',
        'App\\Views\\' => 'app/Views/',
        'App\\Config\\' => 'app/Config/',
        'App\\Helpers\\' => 'app/Helpers/',
        'App\\Services\\' => 'app/Services/',
        'App\\Middleware\\' => 'app/Middleware/',
        'App\\Validators\\' => 'app/Validators/',
        'App\\Exceptions\\' => 'app/Exceptions/'
    ];
    
    /**
     * Classes déjà chargées (cache)
     * 
     * @var array
     */
    private static $loadedClasses = [];
    
    /**
     * Fichiers de classes non trouvés (pour le debug)
     * 
     * @var array
     */
    private static $notFoundClasses = [];
    
    // ========================================
    // MÉTHODES D'INITIALISATION
    // ========================================
    
    /**
     * Initialise l'autoloader avec le chemin de base
     * 
     * @param string $basePath Chemin racine de l'application
     * @return void
     */
    public static function init($basePath = null) {
        // Définir le chemin de base
        if ($basePath === null) {
            // Détecter automatiquement le chemin de base
            $basePath = dirname(__DIR__, 2); // Remonter de 2 niveaux depuis app/Config/
        }
        
        self::$basePath = rtrim($basePath, '/\\');
        
        // Enregistrer l'autoloader
        spl_autoload_register([self::class, 'loadClass']);
        
        // Log de l'initialisation (en mode debug uniquement)
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Autoloader initialisé avec le chemin de base: " . self::$basePath);
        }
    }
    
    // ========================================
    // MÉTHODE PRINCIPALE D'AUTOLOADING
    // ========================================
    
    /**
     * Charge automatiquement une classe
     * 
     * Cette méthode est appelée automatiquement par PHP quand une classe
     * n'est pas trouvée. Elle recherche le fichier correspondant et
     * l'inclut si trouvé.
     * 
     * @param string $className Nom complet de la classe (avec namespace)
     * @return bool True si la classe a été chargée avec succès
     */
    public static function loadClass($className) {
        // Vérifier si la classe est déjà chargée
        if (in_array($className, self::$loadedClasses)) {
            return true;
        }
        
        // Vérifier si la classe a déjà été tentée et non trouvée
        if (in_array($className, self::$notFoundClasses)) {
            return false;
        }
        
        // Essayer de charger la classe avec le mapping des namespaces
        if (self::loadClassWithNamespace($className)) {
            return true;
        }
        
        // Essayer de charger la classe avec la convention de nommage
        if (self::loadClassWithConvention($className)) {
            return true;
        }
        
        // Essayer de charger la classe simple (sans suffixe) dans Models
        if (self::loadSimpleClass($className)) {
            return true;
        }
        
        // Essayer de charger la classe depuis le dossier racine
        if (self::loadClassFromRoot($className)) {
            return true;
        }
        
        // Classe non trouvée, l'ajouter à la liste des classes non trouvées
        self::$notFoundClasses[] = $className;
        
        // Log de la classe non trouvée (en mode debug uniquement)
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Classe non trouvée par l'autoloader: $className");
        }
        
        return false;
    }
    
    // ========================================
    // MÉTHODES DE CHARGEMENT SPÉCIALISÉES
    // ========================================
    
    /**
     * Charge une classe en utilisant le mapping des namespaces
     * 
     * @param string $className Nom complet de la classe
     * @return bool True si la classe a été chargée
     */
    private static function loadClassWithNamespace($className) {
        foreach (self::$namespaceMap as $namespace => $directory) {
            if (strpos($className, $namespace) === 0) {
                // Extraire le nom de la classe sans le namespace
                $relativeClassName = substr($className, strlen($namespace));
                
                // Construire le chemin du fichier
                $filePath = self::$basePath . DIRECTORY_SEPARATOR . 
                           $directory . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClassName) . '.php';
                
                // Vérifier si le fichier existe et l'inclure
                if (file_exists($filePath)) {
                    return self::includeClassFile($filePath, $className);
                }
            }
        }
        
        return false;
    }
    
    /**
     * Charge une classe en utilisant la convention de nommage
     * 
     * @param string $className Nom de la classe
     * @return bool True si la classe a été chargée
     */
    private static function loadClassWithConvention($className) {
        // Mapping des conventions de nommage
        $conventions = [
            'Controller' => 'app/Controllers/',
            'Model' => 'app/Models/',
            'View' => 'app/Views/',
            'Helper' => 'app/Helpers/',
            'Service' => 'app/Services/',
            'Middleware' => 'app/Middleware/',
            'Validator' => 'app/Validators/',
            'Exception' => 'app/Exceptions/'
        ];
        
        foreach ($conventions as $suffix => $directory) {
            if (strpos($className, $suffix) !== false) {
                $filePath = self::$basePath . DIRECTORY_SEPARATOR . 
                           $directory . $className . '.php';
                
                if (file_exists($filePath)) {
                    return self::includeClassFile($filePath, $className);
                }
            }
        }
        
        return false;
    }
    
    /**
     * Charge une classe simple (sans suffixe) dans Models
     * 
     * @param string $className Nom de la classe
     * @return bool True si la classe a été chargée
     */
    private static function loadSimpleClass($className) {
        // Essayer dans Models d'abord
        $filePath = self::$basePath . DIRECTORY_SEPARATOR . 'app/Models/' . $className . '.php';
        
        if (file_exists($filePath)) {
            return self::includeClassFile($filePath, $className);
        }
        
        // Essayer dans Controllers
        $filePath = self::$basePath . DIRECTORY_SEPARATOR . 'app/Controllers/' . $className . '.php';
        
        if (file_exists($filePath)) {
            return self::includeClassFile($filePath, $className);
        }
        
        // Essayer dans Services
        $filePath = self::$basePath . DIRECTORY_SEPARATOR . 'app/Services/' . $className . '.php';
        
        if (file_exists($filePath)) {
            return self::includeClassFile($filePath, $className);
        }
        
        return false;
    }
    
    /**
     * Charge une classe depuis le dossier racine
     * 
     * @param string $className Nom de la classe
     * @return bool True si la classe a été chargée
     */
    private static function loadClassFromRoot($className) {
        // Essayer de charger depuis le dossier racine
        $filePath = self::$basePath . DIRECTORY_SEPARATOR . $className . '.php';
        
        if (file_exists($filePath)) {
            return self::includeClassFile($filePath, $className);
        }
        
        // Essayer avec des underscores au lieu de backslashes
        $underscorePath = self::$basePath . DIRECTORY_SEPARATOR . 
                         str_replace('\\', '_', $className) . '.php';
        
        if (file_exists($underscorePath)) {
            return self::includeClassFile($underscorePath, $className);
        }
        
        return false;
    }
    
    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================
    
    /**
     * Inclut un fichier de classe et vérifie qu'elle est bien définie
     * 
     * @param string $filePath Chemin vers le fichier
     * @param string $className Nom de la classe attendue
     * @return bool True si la classe a été chargée avec succès
     */
    private static function includeClassFile($filePath, $className) {
        try {
            // Inclure le fichier
            require_once $filePath;
            
            // Vérifier que la classe existe maintenant (avec et sans namespace)
            $fullClassName = "App\\Controllers\\" . $className;
            if (class_exists($className, false) || class_exists($fullClassName, false) || 
                interface_exists($className, false) || interface_exists($fullClassName, false) || 
                trait_exists($className, false) || trait_exists($fullClassName, false)) {
                // Ajouter la classe à la liste des classes chargées
                self::$loadedClasses[] = $className;
                
                // Log de succès (en mode debug uniquement)
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    error_log("Classe chargée avec succès: $className depuis $filePath");
                }
                
                return true;
            } else {
                // Le fichier a été inclus mais la classe n'existe pas
                error_log("Fichier inclus mais classe non trouvée: $className dans $filePath");
                return false;
            }
            
        } catch (Exception $e) {
            // Erreur lors de l'inclusion du fichier
            error_log("Erreur lors du chargement de la classe $className: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Ajoute un mapping de namespace personnalisé
     * 
     * @param string $namespace Namespace à mapper
     * @param string $directory Dossier correspondant
     * @return void
     */
    public static function addNamespaceMapping($namespace, $directory) {
        self::$namespaceMap[$namespace] = rtrim($directory, '/\\');
    }
    
    /**
     * Obtient le chemin de base de l'application
     * 
     * @return string Chemin de base
     */
    public static function getBasePath() {
        return self::$basePath;
    }
    
    /**
     * Obtient la liste des classes chargées
     * 
     * @return array Classes chargées
     */
    public static function getLoadedClasses() {
        return self::$loadedClasses;
    }
    
    /**
     * Obtient la liste des classes non trouvées
     * 
     * @return array Classes non trouvées
     */
    public static function getNotFoundClasses() {
        return self::$notFoundClasses;
    }
    
    /**
     * Obtient le mapping des namespaces
     * 
     * @return array Mapping des namespaces
     */
    public static function getNamespaceMap() {
        return self::$namespaceMap;
    }
    
    /**
     * Vérifie si une classe est chargée
     * 
     * @param string $className Nom de la classe
     * @return bool True si la classe est chargée
     */
    public static function isClassLoaded($className) {
        return in_array($className, self::$loadedClasses);
    }
    
    /**
     * Nettoie le cache des classes chargées
     * 
     * @return void
     */
    public static function clearCache() {
        self::$loadedClasses = [];
        self::$notFoundClasses = [];
    }
    
    /**
     * Obtient des statistiques sur l'autoloader
     * 
     * @return array Statistiques
     */
    public static function getStats() {
        return [
            'base_path' => self::$basePath,
            'loaded_classes' => count(self::$loadedClasses),
            'not_found_classes' => count(self::$notFoundClasses),
            'namespace_mappings' => count(self::$namespaceMap),
            'total_attempts' => count(self::$loadedClasses) + count(self::$notFoundClasses)
        ];
    }
    
    // ========================================
    // MÉTHODES DE DEBUG ET DIAGNOSTIC
    // ========================================
    
    /**
     * Affiche des informations de debug sur l'autoloader
     * 
     * @return void
     */
    public static function debug() {
        if (!defined('APP_DEBUG') || !APP_DEBUG) {
            return;
        }
        
        echo "<div style='background: #f0f0f0; padding: 10px; margin: 10px; border: 1px solid #ccc;'>";
        echo "<h3>Debug Autoloader</h3>";
        echo "<p><strong>Chemin de base:</strong> " . self::$basePath . "</p>";
        echo "<p><strong>Classes chargées:</strong> " . count(self::$loadedClasses) . "</p>";
        echo "<p><strong>Classes non trouvées:</strong> " . count(self::$notFoundClasses) . "</p>";
        echo "<p><strong>Mappings de namespace:</strong> " . count(self::$namespaceMap) . "</p>";
        
        if (!empty(self::$loadedClasses)) {
            echo "<p><strong>Classes chargées:</strong></p><ul>";
            foreach (self::$loadedClasses as $class) {
                echo "<li>$class</li>";
            }
            echo "</ul>";
        }
        
        if (!empty(self::$notFoundClasses)) {
            echo "<p><strong>Classes non trouvées:</strong></p><ul>";
            foreach (self::$notFoundClasses as $class) {
                echo "<li>$class</li>";
            }
            echo "</ul>";
        }
        
        echo "</div>";
    }
}

// ========================================
// INITIALISATION AUTOMATIQUE
// ========================================

// Initialiser l'autoloader automatiquement
Autoloader::init(); 