<?php

/**
 * HELPER GESTION DES FICHIERS SIMPLIFIÉ
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Fonctions utilitaires simples pour la gestion des fichiers
 * Parfait pour un examen : complet mais facile à expliquer
 *
 * @author How I Win My Home Team
 * @version 2.0.0 (Simplifié)
 * @since 2025-08-12
 */

class FileHelper
{
    /**
     * Types de fichiers autorisés
     */
    private static $allowedTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif'],
        'document' => ['pdf', 'doc', 'docx']
    ];
    
    /**
     * Tailles maximales (en octets)
     */
    private static $maxSizes = [
        'image' => 5 * 1024 * 1024, // 5MB
        'document' => 10 * 1024 * 1024 // 10MB
    ];
    
    /**
     * Valide un fichier uploadé
     */
    public static function validateUpload($file, $type = 'image')
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['valid' => false, 'error' => 'Aucun fichier uploadé'];
        }
        
        // Vérification de la taille
        if ($file['size'] > self::$maxSizes[$type]) {
            return [
                'valid' => false, 
                'error' => 'Fichier trop volumineux. Maximum : ' . self::formatSize(self::$maxSizes[$type])
            ];
        }
        
        // Vérification du type
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowedTypes[$type])) {
            return [
                'valid' => false, 
                'error' => 'Type de fichier non autorisé. Types acceptés : ' . implode(', ', self::$allowedTypes[$type])
            ];
        }
        
        return ['valid' => true, 'extension' => $extension];
    }
    
    /**
     * Génère un nom de fichier sécurisé
     */
    public static function generateSecureFilename($originalName, $extension)
    {
        $timestamp = time();
        $random = mt_rand(1000, 9999);
        $sanitizedName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($originalName, PATHINFO_FILENAME));
        
        return $timestamp . '_' . $random . '_' . $sanitizedName . '.' . $extension;
    }
    
    /**
     * Déplace un fichier uploadé
     */
    public static function moveUploadedFile($file, $destination)
    {
        $uploadDir = dirname($destination);
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Déplacer le fichier
        return move_uploaded_file($file['tmp_name'], $destination);
    }
    
    /**
     * Supprime un fichier
     */
    public static function deleteFile($filepath)
    {
        if (file_exists($filepath) && is_file($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
    
    /**
     * Formate une taille en octets
     */
    public static function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Upload une image avec validation
     */
    public static function uploadImage($file, $subfolder = 'uploads')
    {
        // Valider le fichier
        $validation = self::validateUpload($file, 'image');
        if (!$validation['valid']) {
            throw new Exception($validation['error']);
        }
        
        // Générer un nom sécurisé
        $extension = $validation['extension'];
        $secureName = self::generateSecureFilename($file['name'], $extension);
        
        // Créer le chemin de destination
        $uploadDir = __DIR__ . '/../../public/uploads/' . $subfolder . '/';
        $destination = $uploadDir . $secureName;
        
        // Déplacer le fichier
        if (self::moveUploadedFile($file, $destination)) {
            return 'uploads/' . $subfolder . '/' . $secureName;
        }
        
        throw new Exception('Erreur lors de l\'upload du fichier');
    }
}