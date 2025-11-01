<?php
/**
 * VUE GESTION DES UTILISATEURS - HOW I WIN MY HOME V1
 * 
 * Interface d'administration pour gérer les utilisateurs
 */

// Récupération des données depuis le contrôleur
$users = $data['users'] ?? [];
$userStats = $data['userStats'] ?? [];
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
                        <i class="fas fa-users"></i>
                        Gestion des utilisateurs
                    </h1>
                    <p class="page-description">
                        Gérez les comptes utilisateurs de la plateforme
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
        
        <!-- Statistiques utilisateurs -->
        <div class="statistics-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques utilisateurs
                </h2>
            </div>
            
            <div class="stats-grid-enhanced">
                <div class="stat-card-enhanced stat-card-users">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($userStats['total'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Total utilisateurs</div>
                        <div class="stat-subtitle">Inscrits sur la plateforme</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-active">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($userStats['active'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Utilisateurs actifs</div>
                        <div class="stat-subtitle">Comptes en activité</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-admins">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($userStats['admins'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Administrateurs</div>
                        <div class="stat-subtitle">Accès complet</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-jury">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-gavel"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($userStats['jury'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Membres du jury</div>
                        <div class="stat-subtitle">Évaluateurs</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liste des utilisateurs -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-list"></i>
                    Liste des utilisateurs
                </h2>
            </div>
            
            <div class="users-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id'] ?? 'N/A') ?></td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-name">
                                            <?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($user['email'] ?? 'N/A') ?></td>
                                <td>
                                    <span class="role-badge role-<?= htmlspecialchars($user['role'] ?? 'user') ?>">
                                        <?= ucfirst(htmlspecialchars($user['role'] ?? 'user')) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= htmlspecialchars($user['status'] ?? 'active') ?>">
                                        <?= ucfirst(htmlspecialchars($user['status'] ?? 'active')) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-small btn-outline" data-action="view" data-user-id="<?= htmlspecialchars($user['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fas fa-eye"></i>
                                            Voir
                                        </button>
                                        <button class="btn btn-small btn-warning" data-action="edit" data-user-id="<?= htmlspecialchars($user['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fas fa-edit"></i>
                                            Modifier
                                        </button>
                                        <button class="btn btn-small btn-danger" data-action="delete" data-user-id="<?= htmlspecialchars($user['id'] ?? '0', ENT_QUOTES, 'UTF-8') ?>">
                                            <i class="fas fa-trash"></i>
                                            Supprimer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <p>Aucun utilisateur trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


