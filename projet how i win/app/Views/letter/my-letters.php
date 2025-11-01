<?php
/**
 * VUE DES LETTRES DE L'UTILISATEUR - HOW I WIN MY HOME V1
 * 
 * Interface de gestion des lettres de motivation
 * avec tableau et filtres
 */

// Récupération des données depuis le contrôleur
$letters = $data['letters'] ?? [];
$totalLetters = $data['totalLetters'] ?? 0;
$draftLetters = $data['draftLetters'] ?? 0;
$submittedLetters = $data['submittedLetters'] ?? 0;
$evaluatedLetters = $data['evaluatedLetters'] ?? 0;
$winnerLetters = $data['winnerLetters'] ?? 0;
$filters = $data['filters'] ?? [];
?>

<div class="my-letters-page">
    <div class="my-letters-container">
        <!-- En-tête de gestion -->
        <div class="management-header">
            <div class="header-content">
                <h1 class="management-title">
                    <i class="fas fa-file-alt"></i>
                    Mes Lettres de Motivation
                </h1>
                <p class="management-subtitle">
                    Gérez toutes vos candidatures et suivez leur progression
                </p>
            </div>
            
            <div class="header-actions">
                <a href="/listings" class="btn btn-outline">
                    <i class="fas fa-search"></i> 
                    Voir les annonces
                </a>
            </div>
        </div>
        
        <!-- Statistiques globales -->
        <div class="statistics-section">
            <div class="stats-card">
                <h3 class="stats-title">
                    <i class="fas fa-chart-bar"></i>
                    Statistiques de vos candidatures
                </h3>
                
                <div class="stats-grid">
                    <div class="stat-item total">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $totalLetters ?></span>
                            <span class="stat-label">Total des lettres</span>
                        </div>
                    </div>
                    
                    <div class="stat-item drafts">
                        <div class="stat-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $draftLetters ?></span>
                            <span class="stat-label">Brouillons</span>
                        </div>
                    </div>
                    
                    <div class="stat-item submitted">
                        <div class="stat-icon">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $submittedLetters ?></span>
                            <span class="stat-label">Soumises</span>
                        </div>
                    </div>
                    
                    <div class="stat-item evaluated">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $evaluatedLetters ?></span>
                            <span class="stat-label">Évaluées</span>
                        </div>
                    </div>
                    
                    <div class="stat-item winners">
                        <div class="stat-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number"><?= $winnerLetters ?></span>
                            <span class="stat-label">Gagnantes</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <!-- Liste des lettres -->
        <div class="letters-section">
            <div class="letters-header">
                <h3 class="letters-title">
                    <i class="fas fa-list"></i>
                    Vos lettres de motivation
                </h3>
                
                <div class="letters-count">
                    <span class="count-text">
                        <?= $totalLetters ?> lettre<?= $totalLetters > 1 ? 's' : '' ?> trouvée<?= $totalLetters > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>
            
            <?php if (empty($letters)): ?>
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 class="empty-title">Aucune lettre trouvée</h4>
                    <p class="empty-description">
                        Vous n'avez pas encore créé de lettre de motivation.
                        Commencez par participer à un concours !
                    </p>
                    
                    <div class="empty-actions">
                        <a href="/listings" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Voir les annonces
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Tableau des lettres -->
                <div class="letters-table-container">
                    <table class="letters-table">
                        <thead>
                            <tr>
                                <th class="table-header">
                                    <i class="fas fa-file-alt"></i>
                                    Titre
                                </th>
                                <th class="table-header">
                                    <i class="fas fa-home"></i>
                                    Annonce
                                </th>
                                <th class="table-header">
                                    <i class="fas fa-calendar"></i>
                                    Date de création
                                </th>
                                <th class="table-header">
                                    <i class="fas fa-info-circle"></i>
                                    Statut
                                </th>
                                <th class="table-header">
                                    <i class="fas fa-star"></i>
                                    Note
                                </th>
                                <th class="table-header">
                                    <i class="fas fa-cogs"></i>
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($letters as $letter): ?>
                                <tr class="table-row letter-row" data-letter-id="<?= $letter['id'] ?>">
                                    <td class="table-cell title-cell">
                                        <div class="letter-title">
                                            <span class="title-text"><?= htmlspecialchars($letter['titre'] ?? 'Lettre de motivation') ?></span>
                                            <?php if (($letter['is_draft'] ?? false)): ?>
                                                <span class="draft-badge">Brouillon</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell listing-cell">
                                        <div class="listing-info">
                                            <a href="/listings/view?id=<?= $letter['listing_id'] ?>" class="listing-link">
                                                <span class="listing-title"><?= htmlspecialchars($letter['listing_titre'] ?? 'Annonce') ?></span>
                                                <span class="listing-location"><?= htmlspecialchars($letter['listing_city'] ?? $letter['listing_ville'] ?? 'Ville non précisée') ?></span>
                                            </a>
                                        </div>
                                    </td>
                                    
                                    <td class="table-cell date-cell">
                                        <span class="date-text">
                                            <?= date('d/m/Y', strtotime($letter['date_creation'])) ?>
                                        </span>
                                        <span class="time-text">
                                            <?= date('H:i', strtotime($letter['date_creation'])) ?>
                                        </span>
                                    </td>
                                    
                                    <td class="table-cell status-cell">
                                        <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        $statusIcon = '';
                                        
                                        switch ($letter['status']) {
                                            case 'brouillon':
                                                $statusClass = 'draft';
                                                $statusText = 'Brouillon';
                                                $statusIcon = 'fas fa-edit';
                                                break;
                                            case 'soumise':
                                                $statusClass = 'submitted';
                                                $statusText = 'Soumise';
                                                $statusIcon = 'fas fa-paper-plane';
                                                break;
                                            case 'evaluee':
                                                $statusClass = 'evaluated';
                                                $statusText = 'Évaluée';
                                                $statusIcon = 'fas fa-star';
                                                break;
                                            case 'gagnante':
                                                $statusClass = 'winner';
                                                $statusText = 'Gagnante';
                                                $statusIcon = 'fas fa-trophy';
                                                break;
                                            case 'rejetee':
                                                $statusClass = 'rejected';
                                                $statusText = 'Rejetée';
                                                $statusIcon = 'fas fa-times-circle';
                                                break;
                                            default:
                                                $statusClass = 'unknown';
                                                $statusText = 'En cours';
                                                $statusIcon = 'fas fa-clock';
                                        }
                                        ?>
                                        
                                        <span class="status-badge status-<?= $statusClass ?>">
                                            <i class="<?= $statusIcon ?>"></i>
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    
                                    <td class="table-cell score-cell">
                                        <?php if ($letter['status'] === 'evaluated' || $letter['status'] === 'winner'): ?>
                                            <div class="score-display">
                                                <span class="score-number"><?= $letter['note'] ?? 'N/A' ?></span>
                                                <span class="score-max">/ 100</span>
                                            </div>
                                        <?php else: ?>
                                            <span class="score-na">-</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="table-cell actions-cell">
                                        <div class="actions-buttons">
                                            <a href="/letter/view?id=<?= $letter['id'] ?>" 
                                               class="btn btn-small btn-outline" 
                                               title="Voir la lettre">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <?php if ($letter['status'] === 'draft'): ?>
                                                <a href="/letter/edit?id=<?= $letter['id'] ?>" 
                                                   class="btn btn-small btn-primary" 
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn btn-small btn-danger delete-letter-btn" 
                                                        data-letter-id="<?= $letter['id'] ?>"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                                <a href="?page=<?= $data['pagination']['currentPage'] - 1 ?>" 
                                   class="pagination-link">
                                    <i class="fas fa-chevron-left"></i>
                                    Précédente
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $data['pagination']['currentPage'] - 2); $i <= min($data['pagination']['totalPages'], $data['pagination']['currentPage'] + 2); $i++): ?>
                                <a href="?page=<?= $i ?>" 
                                   class="pagination-link <?= $i === $data['pagination']['currentPage'] ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                                <a href="?page=<?= $data['pagination']['currentPage'] + 1 ?>" 
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

<!-- Modal de confirmation de suppression -->
<div id="delete-letter-modal" class="modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3>Confirmer la suppression</h3>
            <button type="button" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir supprimer cette lettre de motivation ?</p>
            <p class="warning-text">
                <i class="fas fa-exclamation-triangle"></i>
                Cette action est irréversible et supprimera définitivement votre lettre.
            </p>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-outline cancel-delete-btn">
                Annuler
            </button>
            <button type="button" class="btn btn-danger confirm-delete-btn">
                <i class="fas fa-trash"></i>
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

<!-- Script de gestion des lettres -->
<!-- Le JavaScript est maintenant géré par le fichier letter-list-manager.js -->
