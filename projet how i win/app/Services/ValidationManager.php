<?php

namespace App\Services;

/**
 * GESTIONNAIRE DE VALIDATION SIMPLIFIÉ
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Ce service gère la validation des données de manière simple et directe.
 * Parfait pour un examen : complet mais facile à expliquer.
 *
 * FONCTIONNALITÉS :
 * - Validation des données de formulaire
 * - Messages d'erreur clairs
 * - Nettoyage des données
 *
 * @author How I Win My Home Team
 * @version 2.0.0 (Simplifié)
 * @since 2025-08-12
 */

class ValidationManager {
    
    // ========================================
    // PROPRIÉTÉS
    // ========================================
    
    private $errors = [];
    private $customMessages = [];
    
    // ========================================
    // MÉTHODES DE VALIDATION PRINCIPALES
    // ========================================
    
    /**
     * Valide les données d'un formulaire
     * 
     * @param array $data Les données à valider
     * @param array $rules Les règles de validation
     * @return bool True si la validation réussit
     */
    public function validate($data, $rules) {
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $this->validateField($data, $field, $fieldRules);
        }
        
        return empty($this->errors);
    }
    
    /**
     * Valide un champ spécifique
     * 
     * @param array $data Les données
     * @param string $field Le nom du champ
     * @param array $rules Les règles pour ce champ
     */
    public function validateField($data, $field, $rules) {
        // Vérifier que $data est un tableau
        if (!is_array($data)) {
            $this->errors[$field] = "Données invalides";
            return;
        }
        
        $value = $data[$field] ?? null;
        
        // Convertir la chaîne de règles en tableau si nécessaire
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }
        
        foreach ($rules as $rule) {
            if (!$this->applyRule($field, $value, $rule)) {
                break; // Arrêter après la première erreur
            }
        }
    }
    
    /**
     * Applique une règle de validation
     * 
     * @param string $field Le nom du champ
     * @param mixed $value La valeur du champ
     * @param string $rule La règle à appliquer
     * @return bool True si la règle passe
     */
    private function applyRule($field, $value, $rule) {
        // Gérer les règles avec paramètres (ex: min:8, max:255, regex:...)
        if (strpos($rule, ':') !== false) {
            $parts = explode(':', $rule, 2);
            $ruleName = $parts[0];
            $ruleValue = $parts[1];
            
            switch ($ruleName) {
                case 'min':
                    return $this->validateMinLength($field, $value, (int)$ruleValue);
                case 'max':
                    return $this->validateMaxLength($field, $value, (int)$ruleValue);
                case 'regex':
                    return $this->validateRegex($field, $value, $ruleValue);
                default:
                    return true;
            }
        }
        
        // Gérer les règles simples
        switch ($rule) {
            case 'required':
                return $this->validateRequired($field, $value);
            case 'email':
                return $this->validateEmail($field, $value);
            case 'numeric':
                return $this->validateNumeric($field, $value);
            case 'integer':
                return $this->validateInteger($field, $value);
            default:
                return true; // Règle inconnue, on passe
        }
    }
    
    // ========================================
    // RÈGLES DE VALIDATION SIMPLES
    // ========================================
    
    /**
     * Valide que le champ est requis
     */
    private function validateRequired($field, $value) {
        if (empty($value) && $value !== '0') {
            $this->addError($field, "Le champ " . ucfirst($field) . " est requis.");
            return false;
        }
        return true;
    }
    
    /**
     * Valide que le champ est un email valide
     */
    private function validateEmail($field, $value) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "Le champ " . ucfirst($field) . " doit être un email valide.");
            return false;
        }
        return true;
    }
    
    /**
     * Valide la longueur minimale
     */
    private function validateMinLength($field, $value, $minLength) {
        if (!empty($value) && strlen($value) < $minLength) {
            $this->addError($field, "Le champ " . ucfirst($field) . " doit contenir au moins {$minLength} caractères.");
            return false;
        }
        return true;
    }
    
    /**
     * Valide la longueur maximale
     */
    private function validateMaxLength($field, $value, $maxLength) {
        if (!empty($value) && strlen($value) > $maxLength) {
            $this->addError($field, "Le champ " . ucfirst($field) . " ne peut pas dépasser {$maxLength} caractères.");
            return false;
        }
        return true;
    }
    
    /**
     * Valide que le champ est numérique
     */
    private function validateNumeric($field, $value) {
        if (!empty($value) && !is_numeric($value)) {
            $this->addError($field, "Le champ " . ucfirst($field) . " doit être numérique.");
            return false;
        }
        return true;
    }
    
    /**
     * Valide que le champ est un entier
     */
    private function validateInteger($field, $value) {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
            $this->addError($field, "Le champ " . ucfirst($field) . " doit être un entier.");
            return false;
        }
        return true;
    }
    
    /**
     * Valide que le champ respecte une expression régulière
     */
    private function validateRegex($field, $value, $pattern) {
        if (!empty($value) && !preg_match($pattern, $value)) {
            $message = $this->getCustomMessage($field, 'regex') ?? 
                      "Le champ " . ucfirst($field) . " doit contenir au moins une majuscule, un chiffre et un caractère spécial.";
            $this->addError($field, $message);
            return false;
        }
        return true;
    }
    
    // ========================================
    // MÉTHODES UTILITAIRES
    // ========================================
    
    /**
     * Ajoute une erreur de validation
     */
    private function addError($field, $message) {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
    
    /**
     * Nettoie les données (supprime les balises HTML)
     */
    public function sanitize($data) {
        $sanitized = [];
        
        foreach ($data as $field => $value) {
            if (is_string($value)) {
                $sanitized[$field] = trim(strip_tags($value));
            } else {
                $sanitized[$field] = $value;
            }
        }
        
        return $sanitized;
    }
    
    // ========================================
    // MÉTHODES PUBLIQUES
    // ========================================
    
    /**
     * Récupère toutes les erreurs
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Récupère les erreurs d'un champ
     */
    public function getFieldErrors($field) {
        return $this->errors[$field] ?? [];
    }
    
    /**
     * Vérifie s'il y a des erreurs
     */
    public function hasErrors() {
        return !empty($this->errors);
    }
    
    /**
     * Vérifie s'il y a des erreurs pour un champ
     */
    public function hasFieldErrors($field) {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }
    
    /**
     * Définit des messages d'erreur personnalisés
     */
    public function setCustomMessages($messages) {
        $this->customMessages = $messages;
    }
    
    /**
     * Récupère un message personnalisé pour un champ et une règle
     */
    private function getCustomMessage($field, $rule) {
        $key = $field . '.' . $rule;
        return $this->customMessages[$key] ?? null;
    }
}