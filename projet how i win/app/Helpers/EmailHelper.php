<?php

/**
 * HELPER GESTION DES EMAILS SIMPLIFIÉ
 * HOW I WIN MY HOME - ARCHITECTURE MVC
 * ========================================
 *
 * Fonctions utilitaires simples pour l'envoi d'emails
 * Parfait pour un examen : complet mais facile à expliquer
 *
 * @author How I Win My Home Team
 * @version 2.0.0 (Simplifié)
 * @since 2025-08-12
 */

class EmailHelper
{
    /**
     * En-têtes par défaut
     */
    private static $defaultHeaders = [
        'From' => 'noreply@howiwinmyhome.com',
        'Content-Type' => 'text/html; charset=UTF-8'
    ];
    
    /**
     * Envoie un email simple
     */
    public static function sendEmail($to, $subject, $message, $headers = [])
    {
        // Fusionner les en-têtes
        $finalHeaders = array_merge(self::$defaultHeaders, $headers);
        
        // Construire la chaîne d'en-têtes
        $headerString = '';
        foreach ($finalHeaders as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }
        
        // Envoyer l'email
        return mail($to, $subject, $message, $headerString);
    }
    
    /**
     * Envoie un email de confirmation d'inscription
     */
    public static function sendRegistrationConfirmation($userEmail, $userName)
    {
        $subject = 'Bienvenue sur How I Win My Home !';
        
        $message = "
            <h2>Bienvenue sur How I Win My Home !</h2>
            <p>Bonjour " . htmlspecialchars($userName) . ",</p>
            <p>Votre compte a été créé avec succès.</p>
            <p>Vous pouvez maintenant :</p>
            <ul>
                <li>Parcourir les annonces</li>
                <li>Acheter des tickets</li>
                <li>Participer aux QCM</li>
            </ul>
            <p>Bonne chance !</p>
            <p>L'équipe How I Win My Home</p>
        ";
        
        return self::sendEmail($userEmail, $subject, $message);
    }
    
    /**
     * Envoie un email de confirmation d'achat
     */
    public static function sendTicketConfirmation($userEmail, $userName, $ticketData)
    {
        $subject = 'Confirmation d\'achat de ticket';
        
        $message = "
            <h2>Confirmation d'achat de ticket</h2>
            <p>Bonjour " . htmlspecialchars($userName) . ",</p>
            <p>Votre achat de ticket a été confirmé !</p>
            <p><strong>Numéro de ticket :</strong> " . htmlspecialchars($ticketData['numero_ticket']) . "</p>
            <p><strong>Bien immobilier :</strong> " . htmlspecialchars($ticketData['listing_title']) . "</p>
            <p>Bonne chance !</p>
            <p>L'équipe How I Win My Home</p>
        ";
        
        return self::sendEmail($userEmail, $subject, $message);
    }
    
    /**
     * Envoie un email de notification de victoire
     */
    public static function sendWinnerNotification($userEmail, $userName, $listingData)
    {
        $subject = '🎉 Félicitations ! Vous avez gagné !';
        
        $message = "
            <h2>🎉 FÉLICITATIONS ! 🎉</h2>
            <p>Bonjour " . htmlspecialchars($userName) . ",</p>
            <p>Vous avez remporté le concours pour :</p>
            <p><strong>" . htmlspecialchars($listingData['title']) . "</strong></p>
            <p>Un membre de notre équipe vous contactera dans les 48h.</p>
            <p>Encore une fois, félicitations !</p>
            <p>L'équipe How I Win My Home</p>
        ";
        
        return self::sendEmail($userEmail, $subject, $message);
    }
    
    /**
     * Envoie un email de récupération de mot de passe
     */
    public static function sendPasswordReset($userEmail, $userName, $resetToken)
    {
        $subject = 'Réinitialisation de votre mot de passe';
        
        $resetLink = "https://howiwinmyhome.com/reset-password?token=" . $resetToken;
        
        $message = "
            <h2>Réinitialisation de mot de passe</h2>
            <p>Bonjour " . htmlspecialchars($userName) . ",</p>
            <p>Vous avez demandé la réinitialisation de votre mot de passe.</p>
            <p>Cliquez sur le lien ci-dessous :</p>
            <p><a href='" . htmlspecialchars($resetLink) . "'>Réinitialiser mon mot de passe</a></p>
            <p>Ce lien expire dans 24 heures.</p>
            <p>L'équipe How I Win My Home</p>
        ";
        
        return self::sendEmail($userEmail, $subject, $message);
    }
    
    /**
     * Valide une adresse email
     */
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Nettoie une adresse email
     */
    public static function sanitizeEmail($email)
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }
}