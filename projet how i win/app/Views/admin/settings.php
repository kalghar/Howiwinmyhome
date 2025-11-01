<?php
/**
 * VUE CONFIGURATION SYSTÈME - HOW I WIN MY HOME V1
 * 
 * Interface d'administration pour configurer le système
 */

// Récupération des données depuis le contrôleur
$settings = $data['settings'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;
?>

<div class="admin-page">
    <div class="admin-container">
        <!-- En-tête d'administration -->
        <div class="page-header">
            <!-- Titre et description -->
            <div class="header-content">
                <div class="title-section">
                    <h1 class="page-title">
                        <i class="fas fa-cog"></i>
                        Configuration système
                    </h1>
                    <p class="page-description">
                        Configurez les paramètres de la plateforme
                    </p>
                </div>
                
                <!-- Statut de session -->
                <div class="header-status">
                    <div class="status-indicator">
                        <i class="fas fa-shield-alt"></i>
                        <span>Mode administrateur actif</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulaire de configuration -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-sliders-h"></i>
                    Paramètres du système
                </h2>
            </div>
            
            <form method="POST" action="/admin/update-settings" class="settings-form">
                <!-- Protection CSRF -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($data['csrf_token'] ?? '') ?>">
                <div class="settings-grid">
                    <!-- Section Tickets -->
                    <div class="settings-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-ticket-alt"></i>
                                Configuration des tickets
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="max_tickets_per_listing" class="form-label">
                                    Nombre maximum de tickets par annonce
                                </label>
                                <input type="number" 
                                       id="max_tickets_per_listing" 
                                       name="max_tickets_per_listing" 
                                       value="<?= htmlspecialchars($settings['max_tickets_per_listing'] ?? 100) ?>"
                                       class="form-control"
                                       min="1" 
                                       max="1000">
                                <small class="form-text">Définit le nombre maximum de tickets qu'un utilisateur peut acheter pour une annonce</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section QCM -->
                    <div class="settings-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-question-circle"></i>
                                Configuration du QCM
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="qcm_time_limit" class="form-label">
                                    Durée limite du QCM (en secondes)
                                </label>
                                <input type="number" 
                                       id="qcm_time_limit" 
                                       name="qcm_time_limit" 
                                       value="<?= htmlspecialchars($settings['qcm_time_limit'] ?? 300) ?>"
                                       class="form-control"
                                       min="60" 
                                       max="3600">
                                <small class="form-text">Temps maximum accordé pour compléter le QCM</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="min_qcm_score" class="form-label">
                                    Score minimum requis (en %)
                                </label>
                                <input type="number" 
                                       id="min_qcm_score" 
                                       name="min_qcm_score" 
                                       value="<?= htmlspecialchars($settings['min_qcm_score'] ?? 50) ?>"
                                       class="form-control"
                                       min="0" 
                                       max="100">
                                <small class="form-text">Score minimum requis pour passer à l'étape suivante</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Annonces -->
                    <div class="settings-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-home"></i>
                                Configuration des annonces
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <label for="max_listings_per_user" class="form-label">
                                    Nombre maximum d'annonces par utilisateur
                                </label>
                                <input type="number" 
                                       id="max_listings_per_user" 
                                       name="max_listings_per_user" 
                                       value="<?= htmlspecialchars($settings['max_listings_per_user'] ?? 5) ?>"
                                       class="form-control"
                                       min="1" 
                                       max="50">
                                <small class="form-text">Limite le nombre d'annonces qu'un utilisateur peut créer</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="min_listing_price" class="form-label">
                                    Prix minimum d'une annonce (€)
                                </label>
                                <input type="number" 
                                       id="min_listing_price" 
                                       name="min_listing_price" 
                                       value="<?= htmlspecialchars($settings['min_listing_price'] ?? 1000) ?>"
                                       class="form-control"
                                       min="100" 
                                       max="100000">
                                <small class="form-text">Prix minimum autorisé pour une annonce</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="max_listing_price" class="form-label">
                                    Prix maximum d'une annonce (€)
                                </label>
                                <input type="number" 
                                       id="max_listing_price" 
                                       name="max_listing_price" 
                                       value="<?= htmlspecialchars($settings['max_listing_price'] ?? 1000000) ?>"
                                       class="form-control"
                                       min="1000" 
                                       max="10000000">
                                <small class="form-text">Prix maximum autorisé pour une annonce</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section Modération -->
                    <div class="settings-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt"></i>
                                Configuration de la modération
                            </h3>
                        </div>
                        <div class="card-content">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           id="auto_approve_listings" 
                                           name="auto_approve_listings" 
                                           value="1"
                                           <?= ($settings['auto_approve_listings'] ?? 0) ? 'checked' : '' ?>
                                           class="form-check-input">
                                    <label for="auto_approve_listings" class="form-check-label">
                                        Approbation automatique des annonces
                                    </label>
                                </div>
                                <small class="form-text">Si activé, les annonces sont automatiquement approuvées sans modération manuelle</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Sauvegarder les paramètres
                    </button>
                    <button type="button" class="btn btn-outline" id="reset-settings-btn">
                        <i class="fas fa-undo"></i>
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Informations système -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Informations système
                </h2>
            </div>
            
            <div class="system-info-grid">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Version de l'application</div>
                        <div class="info-value">1.0.0</div>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Version PHP</div>
                        <div class="info-value"><?= PHP_VERSION ?></div>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-memory"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Mémoire utilisée</div>
                        <div class="info-value"><?= round(memory_get_usage(true) / 1024 / 1024, 2) ?> MB</div>
                    </div>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <div class="info-label">Heure du serveur</div>
                        <div class="info-value"><?= date('d/m/Y H:i:s') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


