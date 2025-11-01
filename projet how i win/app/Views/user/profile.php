<?php

/**
 * VUE DU PROFIL UTILISATEUR - HOW I WIN MY HOME V1
 * 
 * Interface de gestion du profil utilisateur
 * avec possibilité de suppression du compte
 */

// Récupération des données depuis le contrôleur
$user = $data['user'] ?? [];
$profileData = $data['profileData'] ?? [];
?>

<div class="profile-page">
    <div class="profile-container">
        <!-- En-tête héroïque Mas Cabanids -->
        <div class="profile-hero">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Mon <span class="highlight">profil</span>
                    </h1>
                    <p class="hero-subtitle">
                        Gérez vos informations personnelles et paramètres de compte
                    </p>
                    <div class="hero-meta">
                        <span class="user-info">
                            <?= htmlspecialchars($user['first_name'] ?? 'Utilisateur') ?> <?= htmlspecialchars($user['last_name'] ?? '') ?>
                        </span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="/dashboard" class="btn btn-outline btn-large">
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenu principal avec grille Mas Cabanids -->
        <div class="profile-content-mascabanids">
            <!-- Grille principale 2 colonnes -->
            <div class="profile-grid-mascabanids">

                <!-- Colonne gauche - Informations personnelles -->
                <div class="profile-main-section">
                    <!-- Carte informations personnelles -->
                    <div class="profile-card-organic">
                        <div class="card-header-mascabanids">
                            <div class="header-content">
                                <h2 class="card-title-mascabanids">
                                    Informations personnelles
                                </h2>
                                <p class="card-subtitle-mascabanids">
                                    Vos données de profil
                                </p>
                            </div>
                            <button class="btn-edit-mascabanids js-open-edit-modal">
                                Modifier
                            </button>
                        </div>

                        <div class="card-body-mascabanids">
                            <div class="info-grid-mascabanids">
                                <div class="info-item-mascabanids">
                                    <div class="info-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="info-content-mascabanids">
                                        <label class="info-label-mascabanids">Nom complet</label>
                                        <div class="info-value-mascabanids">
                                            <?= htmlspecialchars($user['first_name'] ?? '') ?> <?= htmlspecialchars($user['last_name'] ?? '') ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item-mascabanids">
                                    <div class="info-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="info-content-mascabanids">
                                        <label class="info-label-mascabanids">Email</label>
                                        <div class="info-value-mascabanids">
                                            <?= htmlspecialchars($user['email'] ?? '') ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item-mascabanids">
                                    <div class="info-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="info-content-mascabanids">
                                        <label class="info-label-mascabanids">Membre depuis</label>
                                        <div class="info-value-mascabanids">
                                            <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-item-mascabanids">
                                    <div class="info-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="info-content-mascabanids">
                                        <label class="info-label-mascabanids">Dernière connexion</label>
                                        <div class="info-value-mascabanids">
                                            <?= date('d/m/Y à H:i', strtotime($user['last_login'] ?? 'now')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte activités rapides -->
                    <div class="profile-card-organic">
                        <div class="card-header-mascabanids">
                            <div class="header-content">
                                <h2 class="card-title-mascabanids">
                                    Mes activités
                                </h2>
                                <p class="card-subtitle-mascabanids">
                                    Accès rapide à vos fonctionnalités
                                </p>
                            </div>
                        </div>

                        <div class="card-body-mascabanids">
                            <div class="action-grid-mascabanids">
                                <a href="/game/my-tickets" class="action-card-mascabanids">
                                    <div class="action-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                    </div>
                                    <div class="action-content-mascabanids">
                                        <h3 class="action-title-mascabanids">Mes tickets</h3>
                                        <p class="action-description-mascabanids">Gérez vos participations aux concours</p>
                                    </div>
                                    <div class="action-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>

                                <a href="/listings/my-listings" class="action-card-mascabanids">
                                    <div class="action-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-home"></i>
                                        </div>
                                    </div>
                                    <div class="action-content-mascabanids">
                                        <h3 class="action-title-mascabanids">Mes annonces</h3>
                                        <p class="action-description-mascabanids">Gérez vos biens immobiliers</p>
                                    </div>
                                    <div class="action-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>

                                <a href="/listings/create" class="action-card-mascabanids">
                                    <div class="action-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                    </div>
                                    <div class="action-content-mascabanids">
                                        <h3 class="action-title-mascabanids">Créer une annonce</h3>
                                        <p class="action-description-mascabanids">Publiez un nouveau bien immobilier</p>
                                    </div>
                                    <div class="action-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite - Statistiques et paramètres -->
                <div class="profile-sidebar-section">
                    <!-- Carte statistiques -->
                    <div class="profile-card-organic">
                        <div class="card-header-mascabanids">
                            <div class="header-content">
                                <h2 class="card-title-mascabanids">
                                    Statistiques du compte
                                </h2>
                                <p class="card-subtitle-mascabanids">
                                    Vos données d'activité
                                </p>
                            </div>
                        </div>

                        <div class="card-body-mascabanids">
                            <div class="stats-grid-mascabanids">
                                <div class="stat-card-mascabanids">
                                    <div class="stat-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content-mascabanids">
                                        <span class="stat-number-mascabanids"><?= count($profileData['tickets'] ?? []) ?></span>
                                        <span class="stat-label-mascabanids">Tickets achetés</span>
                                    </div>
                                </div>

                                <div class="stat-card-mascabanids">
                                    <div class="stat-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-home"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content-mascabanids">
                                        <span class="stat-number-mascabanids"><?= count($profileData['listings'] ?? []) ?></span>
                                        <span class="stat-label-mascabanids">Annonces créées</span>
                                    </div>
                                </div>

                                <a href="/game/my-letters" class="stat-card-mascabanids stat-card-clickable">
                                    <div class="stat-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content-mascabanids">
                                        <span class="stat-number-mascabanids"><?= count($profileData['letters'] ?? []) ?></span>
                                        <span class="stat-label-mascabanids">Lettres envoyées</span>
                                    </div>
                                </a>

                                <div class="stat-card-mascabanids">
                                    <div class="stat-icon-mascabanids">
                                        <div class="icon-circle">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                    </div>
                                    <div class="stat-content-mascabanids">
                                        <span class="stat-number-mascabanids"><?= count($profileData['qcmResults'] ?? []) ?></span>
                                        <span class="stat-label-mascabanids">QCM complétés</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carte paramètres de sécurité -->
                    <div class="profile-card-organic">
                        <div class="card-header-mascabanids">
                            <div class="header-content">
                                <h2 class="card-title-mascabanids">
                                    Sécurité du compte
                                </h2>
                                <p class="card-subtitle-mascabanids">
                                    Gestion de votre sécurité
                                </p>
                            </div>
                        </div>

                        <div class="card-body-mascabanids">
                            <div class="security-actions-mascabanids">
                                <button class="security-btn-mascabanids js-open-change-password-modal">
                                    <div class="btn-icon-mascabanids">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <div class="btn-content-mascabanids">
                                        <span class="btn-title-mascabanids">Changer le mot de passe</span>
                                        <span class="btn-description-mascabanids">Modifiez votre mot de passe</span>
                                    </div>
                                </button>

                                <button class="security-btn-mascabanids js-download-data">
                                    <div class="btn-icon-mascabanids">
                                        <i class="fas fa-download"></i>
                                    </div>
                                    <div class="btn-content-mascabanids">
                                        <span class="btn-title-mascabanids">Télécharger mes données</span>
                                        <span class="btn-description-mascabanids">Export de vos données personnelles</span>
                                    </div>
                                </button>

                                <button class="security-btn-mascabanids security-btn-danger js-open-delete-modal">
                                    <div class="btn-icon-mascabanids">
                                        <i class="fas fa-trash-alt"></i>
                                    </div>
                                    <div class="btn-content-mascabanids">
                                        <span class="btn-title-mascabanids">Supprimer mon compte</span>
                                        <span class="btn-description-mascabanids">Action irréversible</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de suppression de compte -->
<div id="deleteAccountModal" class="modal">
    <div class="modal-content danger-modal">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-exclamation-triangle"></i>
                Confirmer la suppression du compte
            </h3>
            <button class="modal-close js-close-delete-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="warning-content">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="warning-text">
                    <h4>Supprimer définitivement mon compte</h4>
                    <p>
                        Cette action est irréversible. Toutes vos données seront supprimées de manière permanente :
                    </p>
                    <ul class="danger-list">
                        <li>Vos informations personnelles</li>
                        <li>Vos annonces et tickets</li>
                        <li>Vos lettres de motivation</li>
                        <li>Vos résultats de QCM</li>
                        <li>Votre historique d'activité</li>
                    </ul>
                    <p class="danger-note">
                        <strong>Attention :</strong> Cette action ne peut pas être annulée.
                    </p>
                </div>
            </div>

            <div class="confirmation-form">
                <label for="confirmText" class="form-label">
                    Pour confirmer, tapez <strong>SUPPRIMER</strong> dans le champ ci-dessous :
                </label>
                <input
                    type="text"
                    id="confirmText"
                    class="form-input"
                    placeholder="Tapez SUPPRIMER"
                    autocomplete="off">

                <div class="checkbox-confirmation">
                    <label class="checkbox-label">
                        <input type="checkbox" id="confirmCheckbox">
                        <span class="checkmark"></span>
                        Je comprends que cette action est irréversible et que toutes mes données seront supprimées
                    </label>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-outline js-close-delete-modal">
                Annuler
            </button>
            <button
                class="btn btn-danger js-confirm-delete-account"
                id="confirmDeleteBtn"
                disabled>
                <i class="fas fa-trash-alt"></i>
                Supprimer définitivement mon compte
            </button>
        </div>
    </div>
</div>

<!-- Modal d'édition du profil -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-user-edit"></i>
                Modifier mes informations
            </h3>
            <button class="modal-close js-close-edit-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="editProfileForm" class="modal-body">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="form-group">
                <label for="editFirstName" class="form-label">Prénom *</label>
                <input
                    type="text"
                    id="editFirstName"
                    name="first_name"
                    class="form-input"
                    value="<?= htmlspecialchars($user['first_name'] ?? '') ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="editLastName" class="form-label">Nom *</label>
                <input
                    type="text"
                    id="editLastName"
                    name="last_name"
                    class="form-input"
                    value="<?= htmlspecialchars($user['last_name'] ?? '') ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="editEmail" class="form-label">Email *</label>
                <input
                    type="email"
                    id="editEmail"
                    name="email"
                    class="form-input"
                    value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                    required>
            </div>


            <div class="form-errors" id="editFormErrors" class="form-errors-hidden">
                <!-- Les erreurs s'afficheront ici -->
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline js-close-edit-modal">
                Annuler
            </button>
            <button
                type="button"
                class="btn btn-primary js-save-profile"
                id="saveProfileBtn">
                <i class="fas fa-save"></i>
                Enregistrer les modifications
            </button>
        </div>
    </div>
</div>

<!-- Modal de changement de mot de passe -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-key"></i>
                Changer mon mot de passe
            </h3>
            <button class="modal-close js-close-change-password-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="changePasswordForm" class="modal-body">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="form-group">
                <label for="currentPassword" class="form-label">Mot de passe actuel *</label>
                <input
                    type="password"
                    id="currentPassword"
                    name="current_password"
                    class="form-input"
                    required
                    autocomplete="current-password">
            </div>

            <div class="form-group">
                <label for="newPassword" class="form-label">Nouveau mot de passe *</label>
                <input
                    type="password"
                    id="newPassword"
                    name="new_password"
                    class="form-input"
                    required
                    autocomplete="new-password"
                    minlength="8">
                <small class="form-help">
                    Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 chiffre et 1 caractère spécial
                </small>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="form-label">Confirmer le nouveau mot de passe *</label>
                <input
                    type="password"
                    id="confirmPassword"
                    name="confirm_password"
                    class="form-input"
                    required
                    autocomplete="new-password">
            </div>

            <div class="form-errors" id="passwordFormErrors" class="form-errors-hidden">
                <!-- Les erreurs s'afficheront ici -->
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline js-close-change-password-modal">
                Annuler
            </button>
            <button
                type="button"
                class="btn btn-primary js-save-password"
                id="savePasswordBtn">
                <i class="fas fa-save"></i>
                Changer le mot de passe
            </button>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier profile.js -->

<!-- Script de gestion des événements sécurisé -->
<script src="/assets/js/profile-events.js"></script>