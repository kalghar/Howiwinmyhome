<?php

/**
 * PARTIAL HEADER - EN-TÊTE DE L'APPLICATION
 * HOW I WIN MY HOME V1
 * 
 * Ce partial gère l'en-tête principal de l'application
 * avec le logo, la navigation principale et les actions utilisateur
 */

// Récupération des données utilisateur
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$userRole = $_SESSION['user_role'] ?? null;
$userNom = $_SESSION['user_nom'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
?>

<header class="main-header-mascabanids" role="banner">
    <div class="header-container-mascabanids">
        <!-- Logo et nom du site avec design organique -->
        <div class="header-brand-mascabanids">
            <a href="<?= $userRole === 'admin' ? '/admin' : '/' ?>" class="logo-link-mascabanids" aria-label="<?= $userRole === 'admin' ? 'Administration - How I Win My Home' : 'Accueil - How I Win My Home' ?>">
                <div class="logo-icon-mascabanids">
                    <i class="fas fa-<?= $userRole === 'admin' ? 'cog' : 'home' ?>"></i>
                    <div class="logo-decoration-mascabanids">
                        <div class="decoration-dot"></div>
                        <div class="decoration-dot"></div>
                        <div class="decoration-dot"></div>
                    </div>
                </div>
                <div class="logo-text-mascabanids">
                    <span class="logo-title-mascabanids">How I Win My Home</span>
                    <span class="logo-subtitle-mascabanids"><?= $userRole === 'admin' ? 'Administration' : 'Concours Immobiliers' ?></span>
                </div>
            </a>
        </div>

        <!-- Navigation principale avec design Mas Cabanids -->
        <nav class="header-nav-mascabanids" role="navigation" aria-label="Navigation principale" tabindex="0">
            <!-- Navigation unifiée pour tous -->
            <ul class="nav-list-mascabanids">
                <li class="nav-item-mascabanids">
                    <a href="/" class="nav-link-mascabanids <?= ($data['page'] ?? '') === 'home' ? 'active' : '' ?>">
                        <i class="fas fa-home nav-icon-mascabanids"></i>
                        <span class="nav-text-mascabanids">Accueil</span>
                        <div class="nav-decoration-mascabanids"></div>
                    </a>
                </li>
                <li class="nav-item-mascabanids">
                    <a href="/listings" class="nav-link-mascabanids <?= ($data['page'] ?? '') === 'listings' ? 'active' : '' ?>">
                        <i class="fas fa-search nav-icon-mascabanids"></i>
                        <span class="nav-text-mascabanids">Annonces</span>
                        <div class="nav-decoration-mascabanids"></div>
                    </a>
                </li>
                <?php if ($isLoggedIn && $userRole === 'user'): ?>
                    <li class="nav-item-mascabanids">
                        <a href="/listings/create" class="nav-link-mascabanids <?= ($data['page'] ?? '') === 'listings-create' ? 'active' : '' ?>">
                            <i class="fas fa-plus nav-icon-mascabanids"></i>
                            <span class="nav-text-mascabanids">Créer une annonce</span>
                            <div class="nav-decoration-mascabanids"></div>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($userRole === 'admin'): ?>
                    <li class="nav-item-mascabanids">
                        <a href="/admin" class="nav-link-mascabanids <?= ($data['page'] ?? '') === 'admin' ? 'active' : '' ?>">
                            <i class="fas fa-tachometer-alt nav-icon-mascabanids"></i>
                            <span class="nav-text-mascabanids">Administration</span>
                            <div class="nav-decoration-mascabanids"></div>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item-mascabanids">
                    <a href="/how-it-works" class="nav-link-mascabanids <?= ($data['page'] ?? '') === 'how-it-works' ? 'active' : '' ?>">
                        <i class="fas fa-question-circle nav-icon-mascabanids"></i>
                        <span class="nav-text-mascabanids">Comment ça marche</span>
                        <div class="nav-decoration-mascabanids"></div>
                    </a>
                </li>
                <li class="nav-item-mascabanids">
                    <a href="/contact" class="nav-link-mascabanids <?= ($data['page'] ?? '') === 'contact' ? 'active' : '' ?>">
                        <i class="fas fa-envelope nav-icon-mascabanids"></i>
                        <span class="nav-text-mascabanids">Contact</span>
                        <div class="nav-decoration-mascabanids"></div>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Actions utilisateur avec design Mas Cabanids -->
        <div class="header-actions-mascabanids">
            <?php if ($isLoggedIn): ?>
                <!-- Utilisateur connecté -->
                <div class="user-menu-mascabanids">
                    <button class="user-menu-toggle-mascabanids" aria-expanded="false" aria-haspopup="true">
                        <div class="user-avatar-mascabanids">
                            <i class="fas fa-user"></i>
                            <div class="user-status-indicator-mascabanids"></div>
                        </div>
                        <div class="user-info-mascabanids">
                            <span class="user-name-mascabanids"><?= htmlspecialchars($userNom) ?></span>
                            <span class="user-role-mascabanids"><?= $userRole === 'admin' ? 'Administrateur' : 'Utilisateur' ?></span>
                        </div>
                        <i class="fas fa-chevron-down user-arrow-mascabanids"></i>
                    </button>

                    <div class="user-dropdown-mascabanids" role="menu">
                        <div class="user-info-mascabanids">
                            <div class="user-email-mascabanids"><?= htmlspecialchars($userEmail) ?></div>
                            <div class="user-status-mascabanids">
                                <span class="status-dot-mascabanids"></span>
                                <span class="status-text-mascabanids">En ligne</span>
                            </div>
                        </div>

                        <ul class="dropdown-menu-mascabanids">
                            <?php if ($userRole === 'admin'): ?>
                                <!-- Menu simplifié pour les admins -->
                                <li class="dropdown-item-mascabanids">
                                    <a href="/admin" class="dropdown-link-mascabanids">
                                        <i class="fas fa-tachometer-alt dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Tableau de bord</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/admin/users" class="dropdown-link-mascabanids">
                                        <i class="fas fa-users dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Gérer les utilisateurs</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/admin/listings" class="dropdown-link-mascabanids">
                                        <i class="fas fa-home dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Modérer les annonces</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/admin/documents" class="dropdown-link-mascabanids">
                                        <i class="fas fa-file-shield dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Vérifier les documents</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-divider-mascabanids"></li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/admin/settings" class="dropdown-link-mascabanids">
                                        <i class="fas fa-cog dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Configuration</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-divider-mascabanids"></li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/logout" class="dropdown-link-mascabanids logout-link-mascabanids">
                                        <i class="fas fa-sign-out-alt dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Déconnexion</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                            <?php else: ?>
                                <!-- Menu normal pour les utilisateurs -->
                                <li class="dropdown-item-mascabanids">
                                    <a href="/dashboard" class="dropdown-link-mascabanids">
                                        <i class="fas fa-tachometer-alt dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Tableau de bord</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>

                                <!-- Mes participations -->
                                <li class="dropdown-item-mascabanids">
                                    <a href="/ticket/my-tickets" class="dropdown-link-mascabanids">
                                        <i class="fas fa-ticket-alt dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Mes tickets</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>

                                <li class="dropdown-divider-mascabanids"></li>

                                <!-- Mes annonces -->
                                <li class="dropdown-item-mascabanids">
                                    <a href="/listings/my-listings" class="dropdown-link-mascabanids">
                                        <i class="fas fa-home dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Mes annonces</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/listings/create" class="dropdown-link-mascabanids">
                                        <i class="fas fa-plus dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Créer une annonce</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>

                                <li class="dropdown-divider-mascabanids"></li>

                                <?php if ($userRole === 'jury'): ?>
                                    <li class="dropdown-item-mascabanids">
                                        <a href="/jury" class="dropdown-link-mascabanids">
                                            <i class="fas fa-gavel dropdown-icon-mascabanids"></i>
                                            <span class="dropdown-text-mascabanids">Espace jury</span>
                                            <div class="dropdown-decoration-mascabanids"></div>
                                        </a>
                                    </li>
                                    <li class="dropdown-divider-mascabanids"></li>
                                <?php endif; ?>

                                <!-- Profil et déconnexion -->
                                <li class="dropdown-item-mascabanids">
                                    <a href="/dashboard/profile" class="dropdown-link-mascabanids">
                                        <i class="fas fa-user-edit dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Mon profil</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                                <li class="dropdown-item-mascabanids">
                                    <a href="/logout" class="dropdown-link-mascabanids logout-link-mascabanids">
                                        <i class="fas fa-sign-out-alt dropdown-icon-mascabanids"></i>
                                        <span class="dropdown-text-mascabanids">Déconnexion</span>
                                        <div class="dropdown-decoration-mascabanids"></div>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php else: ?>
                <!-- Utilisateur non connecté -->
                <div class="auth-actions-mascabanids">
                    <button type="button" class="btn-auth-mascabanids btn-login-mascabanids" data-auth-action="login">
                        <i class="fas fa-sign-in-alt btn-icon-mascabanids"></i>
                        <span class="btn-text-mascabanids">Connexion</span>
                        <div class="btn-shine-mascabanids"></div>
                    </button>
                    <button type="button" class="btn-auth-mascabanids btn-register-mascabanids" data-auth-action="register">
                        <i class="fas fa-user-plus btn-icon-mascabanids"></i>
                        <span class="btn-text-mascabanids">Inscription</span>
                        <div class="btn-shine-mascabanids"></div>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bouton menu mobile avec design Mas Cabanids -->
        <button class="mobile-menu-toggle-mascabanids" aria-label="Ouvrir le menu mobile" aria-expanded="false">
            <div class="hamburger-container-mascabanids">
                <span class="hamburger-line-mascabanids"></span>
                <span class="hamburger-line-mascabanids"></span>
                <span class="hamburger-line-mascabanids"></span>
            </div>
            <div class="menu-indicator-mascabanids">
                <span class="menu-dot-mascabanids"></span>
                <span class="menu-dot-mascabanids"></span>
                <span class="menu-dot-mascabanids"></span>
            </div>
        </button>
    </div>
</header>

<!-- Script pour le menu utilisateur -->
<!-- Le JavaScript est maintenant géré par le fichier header-manager.js -->