<?php
/**
 * VUE RAPPORTS ET ANALYSES - HOW I WIN MY HOME V1
 * 
 * Interface d'administration pour consulter les rapports
 */

// Récupération des données depuis le contrôleur
$reports = $data['reports'] ?? [];
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
                        <i class="fas fa-chart-line"></i>
                        Rapports et analyses
                    </h1>
                    <p class="page-description">
                        Consultez les rapports détaillés et les analyses de performance
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
        
        <!-- Statistiques globales -->
        <div class="statistics-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Vue d'ensemble
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
                        <div class="stat-number-enhanced"><?= number_format($reports['user_stats']['total'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Utilisateurs totaux</div>
                        <div class="stat-subtitle">Inscrits sur la plateforme</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-listings">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($reports['listing_stats']['total'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Annonces totales</div>
                        <div class="stat-subtitle">Propriétés disponibles</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-tickets">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format($reports['admin_stats']['total_tickets'] ?? 0) ?></div>
                        <div class="stat-label-enhanced">Tickets vendus</div>
                        <div class="stat-subtitle">Total des ventes</div>
                    </div>
                </div>
                
                <div class="stat-card-enhanced stat-card-revenue">
                    <div class="stat-card-header">
                        <div class="stat-icon-enhanced">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                    <div class="stat-card-body">
                        <div class="stat-number-enhanced"><?= number_format(($reports['admin_stats']['total_tickets'] ?? 0) * 10, 0, ',', ' ') ?> €</div>
                        <div class="stat-label-enhanced">Chiffre d'affaires</div>
                        <div class="stat-subtitle">Revenus estimés</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rapports détaillés -->
        <div class="reports-grid">
            <!-- Rapport utilisateurs -->
            <div class="report-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users"></i>
                        Rapport utilisateurs
                    </h3>
                </div>
                <div class="card-content">
                    <div class="report-stats">
                        <div class="report-stat">
                            <span class="stat-label">Utilisateurs actifs</span>
                            <span class="stat-value"><?= number_format($reports['user_stats']['active'] ?? 0) ?></span>
                        </div>
                        <div class="report-stat">
                            <span class="stat-label">Administrateurs</span>
                            <span class="stat-value"><?= number_format($reports['user_stats']['admins'] ?? 0) ?></span>
                        </div>
                        <div class="report-stat">
                            <span class="stat-label">Membres du jury</span>
                            <span class="stat-value"><?= number_format($reports['user_stats']['jury'] ?? 0) ?></span>
                        </div>
                        <div class="report-stat">
                            <span class="stat-label">Utilisateurs inactifs</span>
                            <span class="stat-value"><?= number_format($reports['user_stats']['inactive'] ?? 0) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rapport annonces -->
            <div class="report-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-home"></i>
                        Rapport annonces
                    </h3>
                </div>
                <div class="card-content">
                    <div class="report-stats">
                        <div class="report-stat">
                            <span class="stat-label">Annonces actives</span>
                            <span class="stat-value"><?= number_format($reports['listing_stats']['active'] ?? 0) ?></span>
                        </div>
                        <div class="report-stat">
                            <span class="stat-label">En attente</span>
                            <span class="stat-value"><?= number_format($reports['listing_stats']['pending'] ?? 0) ?></span>
                        </div>
                        <div class="report-stat">
                            <span class="stat-label">Rejetées</span>
                            <span class="stat-value"><?= number_format($reports['listing_stats']['rejected'] ?? 0) ?></span>
                        </div>
                        <div class="report-stat">
                            <span class="stat-label">Terminées</span>
                            <span class="stat-value"><?= number_format($reports['listing_stats']['completed'] ?? 0) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rapport activité récente -->
            <div class="report-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Activité récente
                    </h3>
                </div>
                <div class="card-content">
                    <div class="activity-list">
                        <?php if (!empty($reports['recent_activities'])): ?>
                            <?php foreach (array_slice($reports['recent_activities'], 0, 5) as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-<?= htmlspecialchars($activity['icon'] ?? 'info-circle') ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <p class="activity-text"><?= htmlspecialchars($activity['message'] ?? 'Activité') ?></p>
                                    <span class="activity-date"><?= date('d/m/Y H:i', strtotime($activity['date'] ?? 'now')) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-info-circle"></i>
                                <p>Aucune activité récente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Rapport performance -->
            <div class="report-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tachometer-alt"></i>
                        Performance système
                    </h3>
                </div>
                <div class="card-content">
                    <div class="performance-metrics">
                        <div class="metric">
                            <div class="metric-label">Utilisation mémoire</div>
                            <div class="metric-bar">
                                <div class="metric-fill metric-fill-45"></div>
                            </div>
                            <div class="metric-value">45%</div>
                        </div>
                        <div class="metric">
                            <div class="metric-label">Temps de réponse</div>
                            <div class="metric-bar">
                                <div class="metric-fill metric-fill-20"></div>
                            </div>
                            <div class="metric-value">120ms</div>
                        </div>
                        <div class="metric">
                            <div class="metric-label">Uptime</div>
                            <div class="metric-bar">
                                <div class="metric-fill metric-fill-99"></div>
                            </div>
                            <div class="metric-value">99.9%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions d'export -->
        <div class="content-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-download"></i>
                    Exporter les données
                </h2>
            </div>
            
            <div class="export-actions">
                <button class="btn btn-primary" id="export-users-btn" data-report-type="users">
                    <i class="fas fa-file-excel"></i>
                    Exporter les utilisateurs (CSV)
                </button>
                <button class="btn btn-primary" id="export-listings-btn" data-report-type="listings">
                    <i class="fas fa-file-excel"></i>
                    Exporter les annonces (CSV)
                </button>
                <button class="btn btn-primary" id="export-tickets-btn" data-report-type="tickets">
                    <i class="fas fa-file-excel"></i>
                    Exporter les tickets (CSV)
                </button>
                <button class="btn btn-secondary" id="generate-pdf-btn">
                    <i class="fas fa-file-pdf"></i>
                    Générer rapport PDF
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier admin-reports.js -->
