<?php
/**
 * VUE DE SÉLECTION DU GAGNANT - HOW I WIN MY HOME V1
 * 
 * Interface de sélection du gagnant final
 * après évaluation de toutes les lettres
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
$candidates = $data['candidates'] ?? [];
$totalTickets = $data['totalTickets'] ?? 0;
$totalLetters = $data['totalLetters'] ?? 0;
$evaluatedLetters = $data['evaluatedLetters'] ?? 0;
$winner = $data['winner'] ?? null;
?>

<div class="select-winner-page">
    <div class="select-winner-container">
        <!-- En-tête de sélection -->
        <div class="selection-header">
            <div class="header-content">
                <h1 class="selection-title">
                    <i class="fas fa-trophy"></i>
                    Sélection du Gagnant Final
                </h1>
                <p class="selection-subtitle">
                    Annonce : <strong><?= htmlspecialchars($listing['titre']) ?></strong>
                </p>
            </div>
            
            <div class="header-status">
                <div class="status-indicator">
                    <i class="fas fa-trophy"></i>
                    <span>
                        <?php if ($winner): ?>
                            Gagnant sélectionné
                        <?php else: ?>
                            Prêt à sélectionner
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Informations sur l'annonce -->
        <div class="listing-info">
            <div class="listing-card">
                <h3 class="section-title">
                    <i class="fas fa-home"></i>
                    Détails de l'annonce
                </h3>
                
                <div class="listing-content">
                    <div class="listing-image">
                        <?php if (!empty($listing['image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($listing['image']) ?>" 
                                 alt="<?= htmlspecialchars($listing['titre']) ?>"
                                 class="info-img">
                        <?php else: ?>
                            <div class="no-image">
                                <i class="fas fa-home"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="listing-details">
                        <h4 class="listing-title"><?= htmlspecialchars($listing['titre']) ?></h4>
                        <p class="listing-description">
                            <?= htmlspecialchars($listing['description']) ?>
                        </p>
                        
                        <div class="listing-meta">
                            <span class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <?= htmlspecialchars($listing['adresse'] ?? 'Adresse non spécifiée') ?>
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-euro-sign"></i>
                                <?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-ticket-alt"></i>
                                <?= $totalTickets ?> tickets vendus
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                Date limite : <?= date('d/m/Y', strtotime($listing['end_date'] ?? 'now')) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Résumé des évaluations -->
        <div class="evaluation-summary">
            <div class="summary-card">
                <h3 class="section-title">
                    <i class="fas fa-chart-bar"></i>
                    Résumé des évaluations
                </h3>
                
                <div class="summary-grid">
                    <div class="summary-item total-letters">
                        <div class="summary-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-number"><?= $totalLetters ?></span>
                            <span class="summary-label">Total des lettres</span>
                        </div>
                    </div>
                    
                    <div class="summary-item evaluated-letters">
                        <div class="summary-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-number"><?= $evaluatedLetters ?></span>
                            <span class="summary-label">Lettres évaluées</span>
                        </div>
                    </div>
                    
                    <div class="summary-item total-tickets">
                        <div class="summary-icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-number"><?= $totalTickets ?></span>
                            <span class="summary-label">Tickets vendus</span>
                        </div>
                    </div>
                    
                    <div class="summary-item participation-rate">
                        <div class="summary-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="summary-content">
                            <span class="summary-number">
                                <?= $totalTickets > 0 ? round(($evaluatedLetters / $totalTickets) * 100, 1) : 0 ?>%
                            </span>
                            <span class="summary-label">Taux de participation</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Classement des candidats -->
        <div class="candidates-ranking">
            <div class="ranking-card">
                <h3 class="section-title">
                    <i class="fas fa-medal"></i>
                    Classement des candidats
                </h3>
                
                <?php if (empty($candidates)): ?>
                    <!-- État vide -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h4 class="empty-title">Aucun candidat évalué</h4>
                        <p class="empty-description">
                            Aucune lettre n'a encore été évaluée pour cette annonce.
                            Commencez par évaluer les lettres soumises.
                        </p>
                        
                        <div class="empty-actions">
                            <a href="/jury/evaluate?listing_id=<?= $listing['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-star"></i>
                                Commencer les évaluations
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Tableau des candidats -->
                    <div class="ranking-table-container">
                        <table class="ranking-table">
                            <thead>
                                <tr>
                                    <th class="table-header rank-header">
                                        <i class="fas fa-medal"></i>
                                        Rang
                                    </th>
                                    <th class="table-header candidate-header">
                                        <i class="fas fa-user"></i>
                                        Candidat
                                    </th>
                                    <th class="table-header score-header">
                                        <i class="fas fa-star"></i>
                                        Note
                                    </th>
                                    <th class="table-header criteria-header">
                                        <i class="fas fa-list"></i>
                                        Critères
                                    </th>
                                    <th class="table-header date-header">
                                        <i class="fas fa-calendar"></i>
                                        Date d'évaluation
                                    </th>
                                    <th class="table-header jury-header">
                                        <i class="fas fa-user-tie"></i>
                                        Jury
                                    </th>
                                    <th class="table-header actions-header">
                                        <i class="fas fa-cogs"></i>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidates as $index => $candidate): ?>
                                    <tr class="table-row candidate-row <?= $candidate['is_winner'] ? 'winner-row' : '' ?>" 
                                        data-candidate-id="<?= $candidate['id'] ?>">
                                        <td class="table-cell rank-cell">
                                            <div class="rank-display">
                                                <?php if ($index === 0): ?>
                                                    <span class="rank-number rank-1">1</span>
                                                    <span class="rank-medal">🥇</span>
                                                <?php elseif ($index === 1): ?>
                                                    <span class="rank-number rank-2">2</span>
                                                    <span class="rank-medal">🥈</span>
                                                <?php elseif ($index === 2): ?>
                                                    <span class="rank-number rank-3">3</span>
                                                    <span class="rank-medal">🥉</span>
                                                <?php else: ?>
                                                    <span class="rank-number"><?= $index + 1 ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell candidate-cell">
                                            <div class="candidate-info">
                                                <span class="candidate-name">
                                                    <?= htmlspecialchars($candidate['name']) ?>
                                                </span>
                                                <span class="candidate-email">
                                                    <?= htmlspecialchars($candidate['email']) ?>
                                                </span>
                                                <?php if ($candidate['is_winner']): ?>
                                                    <span class="winner-badge">
                                                        <i class="fas fa-trophy"></i>
                                                        Gagnant
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell score-cell">
                                            <div class="score-display">
                                                <span class="score-number"><?= $candidate['score'] ?? 0 ?></span>
                                                <span class="score-max">/ 100</span>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell criteria-cell">
                                            <div class="criteria-summary">
                                                <?php if (!empty($candidate['criteria'])): ?>
                                                    <div class="criteria-list">
                                                        <?php foreach (array_slice($candidate['criteria'], 0, 3) as $criterion): ?>
                                                            <span class="criterion-item">
                                                                <?= htmlspecialchars($criterion['name']) ?>: <?= $criterion['score'] ?>/20
                                                            </span>
                                                        <?php endforeach; ?>
                                                        <?php if (count($candidate['criteria']) > 3): ?>
                                                            <span class="criteria-more">
                                                                +<?= count($candidate['criteria']) - 3 ?> autres
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="criteria-na">N/A</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="table-cell date-cell">
                                            <span class="date-text">
                                                <?= date('d/m/Y', strtotime($candidate['evaluation_date'])) ?>
                                            </span>
                                            <span class="time-text">
                                                <?= date('H:i', strtotime($candidate['evaluation_date'])) ?>
                                            </span>
                                        </td>
                                        
                                        <td class="table-cell jury-cell">
                                            <span class="jury-name">
                                                <?= htmlspecialchars($candidate['jury_name']) ?>
                                            </span>
                                        </td>
                                        
                                        <td class="table-cell actions-cell">
                                            <div class="actions-buttons">
                                                <a href="/jury/evaluate?id=<?= $candidate['letter_id'] ?>" 
                                                   class="btn btn-small btn-outline" 
                                                   title="Voir l'évaluation">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <?php if (!$candidate['is_winner'] && $evaluatedLetters === count($candidates)): ?>
                                                    <button type="button" 
                                                            class="btn btn-small btn-success select-winner-btn" 
                                                            data-candidate-id="<?= $candidate['id'] ?>"
                                                            data-candidate-name="<?= htmlspecialchars($candidate['name']) ?>"
                                                            title="Sélectionner comme gagnant">
                                                        <i class="fas fa-trophy"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Actions de sélection -->
                    <?php if ($evaluatedLetters === count($candidates) && !$winner): ?>
                        <div class="selection-actions">
                            <div class="actions-card">
                                <h4 class="actions-title">
                                    <i class="fas fa-trophy"></i>
                                    Sélection du gagnant
                                </h4>
                                <p class="actions-description">
                                    Toutes les lettres ont été évaluées. Vous pouvez maintenant sélectionner le gagnant final.
                                </p>
                                
                                <div class="actions-buttons">
                                    <button type="button" class="btn btn-primary" id="auto-select-winner-btn">
                                        <i class="fas fa-magic"></i>
                                        Sélection automatique (1er du classement)
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline" id="manual-select-winner-btn">
                                        <i class="fas fa-hand-pointer"></i>
                                        Sélection manuelle
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Gagnant sélectionné -->
        <?php if ($winner): ?>
            <div class="winner-section">
                <div class="winner-card">
                    <h3 class="section-title">
                        <i class="fas fa-crown"></i>
                        Gagnant sélectionné
                    </h3>
                    
                    <div class="winner-content">
                        <div class="winner-info">
                            <div class="winner-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="winner-details">
                                <h4 class="winner-name"><?= htmlspecialchars($winner['name']) ?></h4>
                                <p class="winner-email"><?= htmlspecialchars($winner['email']) ?></p>
                                <div class="winner-score">
                                    <span class="score-label">Note finale :</span>
                                    <span class="score-value"><?= $winner['score'] ?? 0 ?>/100</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="winner-actions">
                            <button type="button" class="btn btn-success" id="notify-winner-btn">
                                <i class="fas fa-bell"></i>
                                Notifier le gagnant
                            </button>
                            
                            <button type="button" class="btn btn-outline" id="change-winner-btn">
                                <i class="fas fa-edit"></i>
                                Changer le gagnant
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de confirmation de sélection -->
<div id="select-winner-modal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3>Confirmer la sélection</h3>
            <button type="button" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <p>Êtes-vous sûr de vouloir sélectionner <strong id="candidate-name-modal"></strong> comme gagnant ?</p>
            <div class="warning-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="warning-content">
                    <strong>Attention :</strong> Cette action est définitive et déclenchera automatiquement :
                    <ul>
                        <li>La notification au gagnant</li>
                        <li>La fermeture du concours</li>
                        <li>Le transfert du bien immobilier</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-outline cancel-select-btn">
                Annuler
            </button>
            <button type="button" class="btn btn-success confirm-select-btn">
                <i class="fas fa-trophy"></i>
                Confirmer la sélection
            </button>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier jury-select-winner.js -->
