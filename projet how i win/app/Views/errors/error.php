<?php
/**
 * VUE D'ERREUR GÉNÉRIQUE
 * HOW I WIN MY HOME
 * ========================================
 *
 * Cette vue affiche les messages d'erreur de manière
 * cohérente et professionnelle dans l'application.
 *
 * FONCTIONNALITÉS PRINCIPALES :
 * - Affichage unifié des erreurs de l'application
 * - Messages d'erreur personnalisables par les contrôleurs
 * - Actions de récupération (retour accueil, retour arrière)
 * - Aide contextuelle selon le type d'erreur
 * - Interface utilisateur cohérente avec le design
 *
 * TYPES D'ERREURS SUPPORTÉS :
 * - 404 : Page non trouvée
 * - 403 : Accès refusé
 * - 500 : Erreur serveur interne
 * - Erreurs de validation
 * - Erreurs d'authentification
 * - Erreurs personnalisées
 *
 * DONNÉES DYNAMIQUES :
 * - Titre de l'erreur personnalisable
 * - Message d'erreur détaillé
 * - Code d'erreur pour l'aide contextuelle
 * - Actions de récupération appropriées
 *
 * ÉLÉMENTS D'INTERFACE :
 * - Icône d'erreur avec triangle d'avertissement
 * - Titre et message d'erreur clairs
 * - Boutons d'action pour la récupération
 * - Aide contextuelle selon le type d'erreur
 * - Design cohérent avec le reste de l'application
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 2.0.0
 * DATE : 2025-08-12
 * ========================================
 */
?>

<!-- ========================================
SECTION PRINCIPALE D'ERREUR
========================================

Container principal avec contenu d'erreur
et actions de récupération pour l'utilisateur
-->

<section class="error-section">
    
    <!-- ========================================
    CONTAINER PRINCIPAL
    ========================================
    
    Container avec largeur maximale et centrage
    pour organiser le contenu de la page d'erreur
    -->
    
    <div class="container">
        
        <!-- ========================================
        CONTENU DE LA PAGE D'ERREUR
        ========================================
        
        Section avec icône, titre, message
        et actions de récupération
        -->
        
        <div class="error-content">
            
            <!-- ========================================
            ICÔNE D'ERREUR
            ========================================
            
            Icône d'avertissement pour indiquer
            visuellement qu'une erreur s'est produite
            -->
            
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            
            <!-- ========================================
            TITRE DE L'ERREUR
            ========================================
            
            Titre principal de l'erreur avec
            valeur par défaut si non spécifiée
            -->
            
            <h1 class="error-title">
                <?= htmlspecialchars($errorTitle ?? 'Une erreur est survenue') ?>
            </h1>
            
            <!-- ========================================
            MESSAGE D'ERREUR
            ========================================
            
            Description détaillée de l'erreur
            avec valeur par défaut si non spécifiée
            -->
            
            <p class="error-message">
                <?= htmlspecialchars($errorMessage ?? 'Une erreur inattendue s\'est produite. Veuillez réessayer.') ?>
            </p>
            
            <!-- ========================================
            ACTIONS DE RÉCUPÉRATION
            ========================================
            
            Boutons d'action pour permettre à
            l'utilisateur de récupérer de l'erreur
            -->
            
            <div class="error-actions">
                
                <!-- ========================================
                BOUTON - RETOUR À L'ACCUEIL
                ========================================
                
                Bouton principal pour retourner
                à la page d'accueil de l'application
                -->
                
                <a href="<?= App::getBaseUrl() ?>/" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    Retour à l'accueil
                </a>
                
                <!-- ========================================
                BOUTON - RETOUR EN ARRIÈRE
                ========================================
                
                Bouton secondaire pour retourner
                à la page précédente dans l'historique
                -->
                
                <a href="javascript:history.back()" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Retour en arrière
                </a>
            </div>
            
            <!-- ========================================
            AIDE CONTEXTUELLE - ERREUR 404
            ========================================
            
            Suggestions spécifiques pour les erreurs
            de page non trouvée
            -->
            
            <?php if (isset($errorCode) && $errorCode === 404): ?>
                <div class="error-help">
                    <h3>Suggestions :</h3>
                    <ul>
                        <li>Vérifiez que l'URL est correcte</li>
                        <li>Utilisez la navigation principale du site</li>
                        <li>Contactez-nous si le problème persiste</li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- ========================================
            AIDE CONTEXTUELLE - ERREUR 403
            ========================================
            
            Suggestions spécifiques pour les erreurs
            d'accès refusé
            -->
            
            <?php if (isset($errorCode) && $errorCode === 403): ?>
                <div class="error-help">
                    <h3>Accès refusé :</h3>
                    <ul>
                        <li>Vous n'avez pas les permissions nécessaires</li>
                        <li>Connectez-vous avec un compte approprié</li>
                        <li>Contactez l'administrateur si nécessaire</li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- ========================================
            AIDE CONTEXTUELLE - ERREUR 500
            ========================================
            
            Suggestions spécifiques pour les erreurs
            serveur internes
            -->
            
            <?php if (isset($errorCode) && $errorCode === 500): ?>
                <div class="error-help">
                    <h3>Erreur serveur :</h3>
                    <ul>
                        <li>Une erreur technique s'est produite</li>
                        <li>Veuillez réessayer dans quelques minutes</li>
                        <li>Contactez-nous si le problème persiste</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section> 