<?php
/**
 * Vue de gestion des tickets utilisateur - How I Win My Home
 * 
 * Interface pour voir et gérer tous les tickets de l'utilisateur
 * 
 * @author How I Win My Home Team
 * @version 2.0.0
 * @since 2025-01-27
 */

// Récupérer les données
$activeTickets = $data['active_tickets'] ?? [];
$pendingQCM = $data['pending_qcm'] ?? [];
$pending_letter = $data['pending_letter'] ?? [];
$history = $data['history'] ?? [];
?>

<div class="my-tickets-page">
    <div class="my-tickets-container">
        <!-- En-tête -->
        <div class="my-tickets-header">
            <div>
                <h1 class="my-tickets-title">🎫 Mes Tickets</h1>
                <p class="my-tickets-subtitle">Gérez vos participations aux concours</p>
            </div>
            <a href="/listings" class="my-tickets-action">
                <i class="fas fa-home"></i> Voir les annonces
            </a>
        </div>
        
        <!-- Tickets en attente d'action -->
        <?php if (!empty($pendingQCM) || !empty($pending_letter)): ?>
            <div class="my-tickets-alert">
                <h5><i class="fas fa-exclamation-triangle"></i> Action requise</h5>
                <p>Vous avez des tickets qui nécessitent une action de votre part.</p>
                        </div>
        <?php endif; ?>

        <!-- Onglets -->
        <div class="my-tickets-tabs">
            <div class="my-tickets-tab-list" id="ticketTabs" role="tablist">
                <button class="my-tickets-tab-button active" id="active-tab" data-tab="active" type="button" role="tab">
                    <i class="fas fa-ticket-alt"></i> Actifs 
                    <span class="my-tickets-badge"><?= count($activeTickets) ?></span>
                </button>
                <button class="my-tickets-tab-button" id="qcm-tab" data-tab="qcm" type="button" role="tab">
                    <i class="fas fa-question-circle"></i> QCM 
                    <span class="my-tickets-badge"><?= count($pending_qcm) ?></span>
                </button>
                <button class="my-tickets-tab-button" id="letter-tab" data-tab="letter" type="button" role="tab">
                    <i class="fas fa-envelope"></i> Lettres 
                    <span class="my-tickets-badge"><?= count($pending_letter) ?></span>
                </button>
                <button class="my-tickets-tab-button" id="history-tab" data-tab="history" type="button" role="tab">
                    <i class="fas fa-history"></i> Historique 
                    <span class="my-tickets-badge"><?= count($history) ?></span>
                </button>
                    </div>
                    
        <!-- Contenu des onglets -->
        <div class="my-tickets-tab-content" id="ticketTabsContent">
            <!-- Tickets actifs -->
            <div class="my-tickets-tab-pane active" id="active" role="tabpanel">
                <?php if (empty($activeTickets)): ?>
                    <div class="my-tickets-empty-state">
                        <i class="fas fa-ticket-alt my-tickets-empty-icon"></i>
                        <h5 class="my-tickets-empty-title">Aucun ticket actif</h5>
                        <p class="my-tickets-empty-text">Vous n'avez pas de tickets actifs pour le moment.</p>
                        <a href="/listings" class="my-tickets-empty-action">
                            <i class="fas fa-home"></i> Voir les annonces
                        </a>
                        </div>
                <?php else: ?>
                    <div class="my-tickets-grid">
                        <?php foreach ($activeTickets as $ticket): ?>
                            <div class="my-tickets-card">
                                <div class="my-tickets-card-header">
                                    <h6 class="my-tickets-card-title"><?= htmlspecialchars($ticket['title']) ?></h6>
                                    <span class="my-tickets-card-badge active">Actif</span>
                        </div>
                                <div class="my-tickets-card-body">
                                    <div class="my-tickets-card-info">
                                        <span class="my-tickets-card-label">Numéro :</span>
                                        <span class="my-tickets-card-value"><?= htmlspecialchars($ticket['numero_ticket']) ?></span>
                    </div>
                    
                                    <div class="my-tickets-card-info">
                                        <span class="my-tickets-card-label">Prix :</span>
                                        <span class="my-tickets-card-price"><?= number_format($ticket['ticket_price'], 2, ',', ' ') ?> €</span>
                    </div>
                    
                                    <div class="my-tickets-card-info">
                                        <span class="my-tickets-card-label">Acheté le :</span>
                                        <span class="my-tickets-card-value"><?= date('d/m/Y à H:i', strtotime($ticket['date_achat'])) ?></span>
                    </div>
                    
                                    <div class="my-tickets-card-info">
                                        <span class="my-tickets-card-label">Concours :</span>
                                        <span class="my-tickets-card-value">
                                            Du <?= date('d/m/Y', strtotime($ticket['start_date'])) ?><br>
                                            Au <?= date('d/m/Y', strtotime($ticket['end_date'])) ?>
                            </span>
        </div>
        
                                    <div class="my-tickets-card-actions">
                                        <a href="/listings/<?= $ticket['listing_id'] ?>" class="my-tickets-card-button secondary">
                                            <i class="fas fa-eye"></i> Voir l'annonce
                                        </a>
                                        <?php 
                                        $qcmStatus = $ticket['qcm_status'] ?? 'pending';
                                        $letterStatus = $ticket['letter_status'] ?? 'pending';
                                        ?>
                                        <?php if ($qcmStatus === 'pending'): ?>
                                            <a href="/qcm?listing_id=<?= $ticket['listing_id'] ?>&ticket_id=<?= $ticket['id'] ?>" class="my-tickets-card-button primary">
                                                <i class="fas fa-play"></i> Commencer le QCM
                                            </a>
                                        <?php elseif ($qcmStatus === 'incomplete'): ?>
                                            <a href="/qcm?listing_id=<?= $ticket['listing_id'] ?>&ticket_id=<?= $ticket['id'] ?>" class="my-tickets-card-button warning">
                                                <i class="fas fa-redo"></i> Continuer le QCM
                                            </a>
                                        <?php elseif ($qcmStatus === 'completed' && $letterStatus === 'pending'): ?>
                                            <a href="/letter/create?listing_id=<?= $ticket['listing_id'] ?>&ticket_id=<?= $ticket['id'] ?>" class="my-tickets-card-button success">
                                                <i class="fas fa-pen"></i> Rédiger la lettre
                                            </a>
                                        <?php elseif ($qcmStatus === 'completed' && $letterStatus === 'incomplete'): ?>
                                            <a href="/letter/create?listing_id=<?= $ticket['listing_id'] ?>&ticket_id=<?= $ticket['id'] ?>" class="my-tickets-card-button info">
                                                <i class="fas fa-edit"></i> Continuer la lettre
                                            </a>
                                        <?php elseif ($qcmStatus === 'completed' && $letterStatus === 'sent'): ?>
                                            <span class="my-tickets-card-status completed">
                                                <i class="fas fa-check-circle"></i> Candidature complète
                    </span>
                    <?php endif; ?>
                </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
            <!-- Tickets en attente -->
            <div class="my-tickets-tab-pane" id="pending" role="tabpanel">
                <!-- Tickets en attente de QCM -->
                <?php if (!empty($pendingQCM)): ?>
                    <div class="mb-4">
                        <h5 class="text-warning">
                            <i class="fas fa-question-circle"></i> QCM en attente
                        </h5>
                        <div class="my-tickets-grid">
                            <?php foreach ($pendingQCM as $ticket): ?>
                                <div class="my-tickets-card">
                                    <div class="my-tickets-card-header">
                                        <h6 class="my-tickets-card-title"><?= htmlspecialchars($ticket['title']) ?></h6>
                                        <span class="my-tickets-card-badge pending">QCM</span>
                                    </div>
                                    <div class="my-tickets-card-body">
                                        <p class="my-tickets-empty-text">
                                            Vous devez passer le QCM pour continuer votre participation.
                                        </p>
                                        <div class="my-tickets-card-actions">
                                            <a href="/ticket/start-qcm/<?= $ticket['id'] ?>" class="my-tickets-card-button primary">
                                                <i class="fas fa-play"></i> Commencer le QCM
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                                            </div>
                                        </div>
                <?php endif; ?>

                <!-- Tickets en attente de lettre -->
                <?php if (!empty($pending_letter)): ?>
                    <div class="mb-4">
                        <h5 class="text-info">
                            <i class="fas fa-pen"></i> Lettre de motivation en attente
                        </h5>
                        <div class="my-tickets-grid">
                            <?php foreach ($pending_letter as $ticket): ?>
                                <div class="my-tickets-card">
                                    <div class="my-tickets-card-header">
                                        <h6 class="my-tickets-card-title"><?= htmlspecialchars($ticket['title']) ?></h6>
                                        <span class="my-tickets-card-badge pending">Lettre</span>
                                            </div>
                                    <div class="my-tickets-card-body">
                                        <p class="my-tickets-empty-text">
                                            Vous devez écrire votre lettre de motivation.
                                        </p>
                                        <div class="my-tickets-card-actions">
                                            <a href="/ticket/start-letter/<?= $ticket['id'] ?>" class="my-tickets-card-button primary">
                                                <i class="fas fa-pen"></i> Écrire la lettre
                                            </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                        </div>
                            <?php endif; ?>
                            
                <?php if (empty($pendingQCM) && empty($pending_letter)): ?>
                    <div class="my-tickets-empty-state">
                        <i class="fas fa-check-circle my-tickets-empty-icon"></i>
                        <h5 class="my-tickets-empty-title">Aucune action requise</h5>
                        <p class="my-tickets-empty-text">Tous vos tickets sont à jour.</p>
                    </div>
                            <?php endif; ?>
                        </div>

            <!-- Historique -->
            <div class="my-tickets-tab-pane" id="history" role="tabpanel">
                <?php if (empty($history)): ?>
                    <div class="my-tickets-empty-state">
                        <i class="fas fa-history my-tickets-empty-icon"></i>
                        <h5 class="my-tickets-empty-title">Aucun historique</h5>
                        <p class="my-tickets-empty-text">Vous n'avez pas encore d'historique de tickets.</p>
                    </div>
                <?php else: ?>
                    <table class="my-tickets-table">
                        <thead>
                            <tr>
                                <th>Ticket</th>
                                <th>Annonce</th>
                                <th>Prix</th>
                                <th>Statut</th>
                                <th>Date d'achat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $ticket): ?>
                                <tr>
                                    <td>
                                        <span class="my-tickets-card-value"><?= htmlspecialchars($ticket['numero_ticket']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($ticket['title']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="my-tickets-card-price"><?= number_format($ticket['ticket_price'], 2, ',', ' ') ?> €</span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($ticket['status']) {
                                            'active' => 'active',
                                            'used' => 'pending',
                                            'expired' => 'pending',
                                            'cancelled' => 'pending',
                                            default => 'pending'
                                        };
                                        $statusText = match($ticket['status']) {
                                            'active' => 'Actif',
                                            'used' => 'Utilisé',
                                            'expired' => 'Expiré',
                                            'cancelled' => 'Annulé',
                                            default => 'Inconnu'
                                        };
                                        ?>
                                        <span class="my-tickets-card-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y à H:i', strtotime($ticket['date_achat'])) ?>
                                    </td>
                                    <td>
                                        <a href="/listings/<?= $ticket['listing_id'] ?>" class="my-tickets-card-button secondary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
            <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Le JavaScript est maintenant géré par le fichier my-tickets.js -->
