<?php
/**
 * VUE DES RÉSULTATS DU JURY - HOW I WIN MY HOME V1
 * 
 * Affichage des résultats détaillés
 * des évaluations du jury
 */

// Récupération des données depuis le contrôleur
$results = $data['results'] ?? [];
$listing = $data['listing'] ?? null;
$listings = $data['listings'] ?? [];
$statistics = $data['statistics'] ?? [];
$filters = $data['filters'] ?? [];
?>

<div class="jury-results-page">
    <div class="jury-results-container">
        <!-- En-tête des résultats -->
        <div class="results-header">
            <div class="header-content">
                <h1 class="results-title">
                    <i class="fas fa-chart-bar"></i>
                    Résultats Détaillés des Évaluations
                </h1>
                <p class="results-subtitle">
                    <?php if ($listing): ?>
                        Annonce : <strong><?= htmlspecialchars($listing['titre']) ?></strong>
                    <?php else: ?>
                        Toutes les annonces
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="header-actions">
                <button type="button" class="btn btn-outline" id="export-results-btn">
                    <i class="fas fa-download"></i> 
                    Exporter
                </button>
                
                <button type="button" class="btn btn-outline" id="print-results-btn">
                    <i class="fas fa-print"></i> 
                    Imprimer
                </button>
            </div>
        </div>
        
        <!-- Filtres et sélection -->
        <div class="filters-section">
            <div class="filters-card">
                <h3 class="filters-title">
                    <i class="fas fa-filter"></i>
                    Filtres et sélection
                </h3>
                
                <form class="filters-form" method="GET" action="/jury/results">
                    <div class="filters-row">
                        <div class="filter-group">
                            <label for="listing_filter" class="filter-label">Annonce :</label>
                            <select name="listing_filter" id="listing_filter" class="filter-select">
                                <option value="">Toutes les annonces</option>
                                <?php foreach ($listings as $list): ?>
                                    <option value="<?= $list['id'] ?>" 
                                            <?= (isset($filters['listing']) && $filters['listing'] == $list['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($list['titre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="date_filter" class="filter-label">Période :</label>
                            <select name="date_filter" id="date_filter" class="filter-select">
                                <option value="">Toutes les périodes</option>
                                <option value="week" <?= ($filters['date'] ?? '') === 'week' ? 'selected' : '' ?>>Cette semaine</option>
                                <option value="month" <?= ($filters['date'] ?? '') === 'month' ? 'selected' : '' ?>>Ce mois</option>
                                <option value="quarter" <?= ($filters['date'] ?? '') === 'quarter' ? 'selected' : '' ?>>Ce trimestre</option>
                                <option value="year" <?= ($filters['date'] ?? '') === 'year' ? 'selected' : '' ?>>Cette année</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="status_filter" class="filter-label">Statut :</label>
                            <select name="status_filter" id="status_filter" class="filter-select">
                                <option value="">Tous les statuts</option>
                                <option value="evaluated" <?= ($filters['status'] ?? '') === 'evaluated' ? 'selected' : '' ?>>Évaluées</option>
                                <option value="winner" <?= ($filters['status'] ?? '') === 'winner' ? 'selected' : '' ?>>Gagnantes</option>
                                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>En attente</option>
                            </select>
                        </div>
                        
                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Filtrer
                            </button>
                            
                            <a href="/jury/results" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Statistiques globales -->
        <div class="global-statistics">
            <div class="stats-card">
                <h3 class="stats-title">
                    <i class="fas fa-chart-line"></i>
                    Statistiques globales
                </h3>
                
                <div class="stats-grid">
                    <div class="stat-item total">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $statistics['total'] ?? 0 ?></span>
                            <span class="stat-label">Total des lettres</span>
                        </div>
                    </div>
                    
                    <div class="stat-item evaluated">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $statistics['evaluated'] ?? 0 ?></span>
                            <span class="stat-label">Lettres évaluées</span>
                        </div>
                    </div>
                    
                    <div class="stat-item pending">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $statistics['pending'] ?? 0 ?></span>
                            <span class="stat-label">En attente</span>
                        </div>
                    </div>
                    
                    <div class="stat-item winners">
                        <div class="stat-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $statistics['winners'] ?? 0 ?></span>
                            <span class="stat-label">Gagnants</span>
                        </div>
                    </div>
                    
                    <div class="stat-item average">
                        <div class="stat-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= number_format($statistics['averageScore'] ?? 0, 1) ?></span>
                            <span class="stat-label">Note moyenne</span>
                        </div>
                    </div>
                    
                    <div class="stat-item participation">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $statistics['participants'] ?? 0 ?></span>
                            <span class="stat-label">Participants</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graphiques et visualisations -->
        <div class="charts-section">
            <div class="charts-grid">
                <!-- Graphique des notes -->
                <div class="chart-card">
                    <h4 class="chart-title">
                        <i class="fas fa-chart-bar"></i>
                        Distribution des notes
                    </h4>
                    <div class="chart-container">
                        <canvas id="scoresChart" width="400" height="300"></canvas>
                    </div>
                </div>
                
                <!-- Graphique des statuts -->
                <div class="chart-card">
                    <h4 class="chart-title">
                        <i class="fas fa-pie-chart"></i>
                        Répartition par statut
                    </h4>
                    <div class="chart-container">
                        <canvas id="statusChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tableau des résultats -->
        <div class="results-table-section">
            <div class="table-card">
                <h3 class="table-title">
                    <i class="fas fa-table"></i>
                    Détail des évaluations
                </h3>
                
                <?php if (empty($results)): ?>
                    <!-- État vide -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4 class="empty-title">Aucun résultat trouvé</h4>
                        <p class="empty-description">
                            <?php if (!empty($filters)): ?>
                                Aucun résultat ne correspond à vos critères de recherche.
                                <a href="/jury/results" class="empty-link">Voir tous les résultats</a>
                            <?php else: ?>
                                Aucune évaluation n'a encore été effectuée.
                            <?php endif; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <!-- Tableau des résultats -->
                    <div class="table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th class="table-header">
                                        <i class="fas fa-user"></i>
                                        Candidat
                                    </th>
                                    <th class="table-header">
                                        <i class="fas fa-home"></i>
                                        Annonce
                                    </th>
                                    <th class="table-header">
                                        <i class="fas fa-star"></i>
                                        Note
                                    </th>
                                    <th class="table-header">
                                        <i class="fas fa-info-circle"></i>
                                        Statut
                                    </th>
                                    <th class="table-header">
                                        <i class="fas fa-calendar"></i>
                                        Date d'évaluation
                                    </th>
                                    <th class="table-header">
                                        <i class="fas fa-user-tie"></i>
                                        Jury
                                    </th>
                                    <th class="table-header">
                                        <i class="fas fa-cogs"></i>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($results as $result): ?>
                                    <tr class="table-row result-row" data-result-id="<?= $result['id'] ?>">
                                        <td class="table-cell candidate-cell">
                                            <div class="candidate-info">
                                                <span class="candidate-name">
                                                    <?= htmlspecialchars($result['candidate_name']) ?>
                                                </span>
                                                <span class="candidate-email">
                                                    <?= htmlspecialchars($result['candidate_email']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell listing-cell">
                                            <div class="listing-info">
                                                <span class="listing-title">
                                                    <?= htmlspecialchars($result['listing_title']) ?>
                                                </span>
                                                <span class="listing-location">
                                                    <?= htmlspecialchars($result['listing_city']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell score-cell">
                                            <div class="score-display">
                                                <span class="score-number"><?= $result['score'] ?? 'N/A' ?></span>
                                                <span class="score-max">/ 100</span>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell status-cell">
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            $statusIcon = '';
                                            
                                            switch ($result['status']) {
                                                case 'evaluated':
                                                    $statusClass = 'evaluated';
                                                    $statusText = 'Évaluée';
                                                    $statusIcon = 'fas fa-star';
                                                    break;
                                                case 'winner':
                                                    $statusClass = 'winner';
                                                    $statusText = 'Gagnante';
                                                    $statusIcon = 'fas fa-trophy';
                                                    break;
                                                case 'pending':
                                                    $statusClass = 'pending';
                                                    $statusText = 'En attente';
                                                    $statusIcon = 'fas fa-clock';
                                                    break;
                                                default:
                                                    $statusClass = 'unknown';
                                                    $statusText = 'Inconnu';
                                                    $statusIcon = 'fas fa-question-circle';
                                            }
                                            ?>
                                            
                                            <span class="status-badge status-<?= $statusClass ?>">
                                                <i class="<?= $statusIcon ?>"></i>
                                                <?= $statusText ?>
                                            </span>
                                        </td>
                                        
                                        <td class="table-cell date-cell">
                                            <span class="date-text">
                                                <?= date('d/m/Y', strtotime($result['evaluation_date'])) ?>
                                            </span>
                                            <span class="time-text">
                                                <?= date('H:i', strtotime($result['evaluation_date'])) ?>
                                            </span>
                                        </td>
                                        
                                        <td class="table-cell jury-cell">
                                            <span class="jury-name">
                                                <?= htmlspecialchars($result['jury_name']) ?>
                                            </span>
                                        </td>
                                        
                                        <td class="table-cell actions-cell">
                                            <div class="actions-buttons">
                                                <a href="/jury/evaluate?id=<?= $result['letter_id'] ?>" 
                                                   class="btn btn-small btn-outline" 
                                                   title="Voir l'évaluation">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if ($result['status'] === 'pending'): ?>
                                                    <a href="/jury/evaluate?id=<?= $result['letter_id'] ?>" 
                                                       class="btn btn-small btn-primary" 
                                                       title="Évaluer">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($data['pagination']) && $data['pagination']['totalPages'] > 1): ?>
                        <div class="pagination-section">
                            <div class="pagination-info">
                                <span class="pagination-text">
                                    Page <?= $data['pagination']['currentPage'] ?> sur <?= $data['pagination']['totalPages'] ?>
                                </span>
                            </div>
                            
                            <div class="pagination-controls">
                                <?php if ($data['pagination']['currentPage'] > 1): ?>
                                    <a href="?page=<?= $data['pagination']['currentPage'] - 1 ?>&<?= http_build_query(array_diff_key($filters, ['page' => ''])) ?>" 
                                       class="pagination-link">
                                        <i class="fas fa-chevron-left"></i>
                                        Précédente
                                    </a>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $data['pagination']['currentPage'] - 2); $i <= min($data['pagination']['totalPages'], $data['pagination']['currentPage'] + 2); $i++): ?>
                                    <a href="?page=<?= $i ?>&<?= http_build_query(array_diff_key($filters, ['page' => ''])) ?>" 
                                       class="pagination-link <?= $i === $data['pagination']['currentPage'] ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                                    <a href="?page=<?= $data['pagination']['currentPage'] + 1 ?>&<?= http_build_query(array_diff_key($filters, ['page' => ''])) ?>" 
                                       class="pagination-link">
                                        Suivante
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier jury-results.js -->