<?php

/**
 * HELPER GESTION DES DATES SIMPLIFIÉ
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Fonctions utilitaires simples pour la gestion des dates
 * Parfait pour un examen : complet mais facile à expliquer
 *
 * @author How I Win My Home Team
 * @version 2.0.0 (Simplifié)
 * @since 2025-08-12
 */

class DateHelper
{
    /**
     * Fuseau horaire par défaut
     */
    private static $defaultTimezone = 'Europe/Paris';
    
    /**
     * Format de date par défaut
     */
    private static $defaultDateFormat = 'd/m/Y H:i';
    
    /**
     * Initialise le fuseau horaire
     */
    public static function init()
    {
        date_default_timezone_set(self::$defaultTimezone);
    }
    
    /**
     * Formate une date
     */
    public static function formatDate($date, $format = null)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        if (!$date instanceof DateTime) {
            return '';
        }
        
        $format = $format ?: self::$defaultDateFormat;
        return $date->format($format);
    }
    
    /**
     * Formate une date courte
     */
    public static function formatShortDate($date)
    {
        return self::formatDate($date, 'd/m/Y');
    }
    
    /**
     * Formate une heure
     */
    public static function formatTime($date)
    {
        return self::formatDate($date, 'H:i');
    }
    
    /**
     * Formate une date relative (il y a X temps)
     */
    public static function formatRelativeDate($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        if (!$date instanceof DateTime) {
            return '';
        }
        
        $now = new DateTime();
        $diff = $now->diff($date);
        
        if ($diff->invert) {
            // Date dans le passé
            if ($diff->y > 0) {
                return 'il y a ' . $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
            } elseif ($diff->m > 0) {
                return 'il y a ' . $diff->m . ' mois';
            } elseif ($diff->d > 0) {
                return 'il y a ' . $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
            } elseif ($diff->h > 0) {
                return 'il y a ' . $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
            } elseif ($diff->i > 0) {
                return 'il y a ' . $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
            } else {
                return 'à l\'instant';
            }
        } else {
            // Date dans le futur
            if ($diff->y > 0) {
                return 'dans ' . $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
            } elseif ($diff->m > 0) {
                return 'dans ' . $diff->m . ' mois';
            } elseif ($diff->d > 0) {
                return 'dans ' . $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');
            } elseif ($diff->h > 0) {
                return 'dans ' . $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
            } elseif ($diff->i > 0) {
                return 'dans ' . $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
            } else {
                return 'maintenant';
            }
        }
    }
    
    /**
     * Vérifie si une date est valide
     */
    public static function isValidDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Vérifie si une date est dans le passé
     */
    public static function isPast($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        if (!$date instanceof DateTime) {
            return false;
        }
        
        $now = new DateTime();
        return $date < $now;
    }
    
    /**
     * Vérifie si une date est dans le futur
     */
    public static function isFuture($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        if (!$date instanceof DateTime) {
            return false;
        }
        
        $now = new DateTime();
        return $date > $now;
    }
    
    /**
     * Vérifie si une date est aujourd'hui
     */
    public static function isToday($date)
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        if (!$date instanceof DateTime) {
            return false;
        }
        
        $now = new DateTime();
        return $date->format('Y-m-d') === $now->format('Y-m-d');
    }
    
    /**
     * Ajoute du temps à une date
     */
    public static function addTime($date, $amount, $unit = 'days')
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }
        
        if (!$date instanceof DateTime) {
            return false;
        }
        
        $newDate = clone $date;
        
        switch ($unit) {
            case 'days':
                $newDate->add(new DateInterval('P' . $amount . 'D'));
                break;
            case 'hours':
                $newDate->add(new DateInterval('PT' . $amount . 'H'));
                break;
            case 'minutes':
                $newDate->add(new DateInterval('PT' . $amount . 'M'));
                break;
        }
        
        return $newDate;
    }
    
    /**
     * Obtient l'âge à partir d'une date de naissance
     */
    public static function getAge($birthDate)
    {
        if (is_string($birthDate)) {
            $birthDate = new DateTime($birthDate);
        }
        
        if (!$birthDate instanceof DateTime) {
            return 0;
        }
        
        $now = new DateTime();
        $diff = $now->diff($birthDate);
        return $diff->y;
    }
}