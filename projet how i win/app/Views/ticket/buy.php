<?php
/**
 * VUE D'ACHAT DE TICKETS - HOW I WIN MY HOME V1
 * 
 * Interface d'achat de ticket pour participer
 * aux concours immobiliers
 */

// Récupération des données depuis le contrôleur
$listing = $data['listing'] ?? [];
$ticketsVendus = $data['ticketsVendus'] ?? 0;
$ticketsRestants = $data['ticketsRestants'] ?? 0;
$ticketsTotal = $data['ticketsTotal'] ?? 0;
$userBalance = $data['userBalance'] ?? 0;
$errors = $data['errors'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
?>

<div class="ticket-buy-page">
    <div class="ticket-buy-container">
        <!-- En-tête de la page -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-ticket-alt"></i>
                    Acheter un ticket
                </h1>
                <p class="page-description">
                    Participez à ce concours immobilier en achetant votre ticket de participation
                </p>
            </div>
            
            <!-- Navigation retour -->
            <div class="header-actions">
                <a href="/listings/view?id=<?= $listing['id'] ?? '' ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i>
                    Retour à l'annonce
                </a>
            </div>
        </div>
        
        <!-- Aperçu de l'annonce -->
        <div class="listing-preview">
            <div class="preview-card">
                <div class="listing-image">
                    <?php if (!empty($listing['image'])): ?>
                        <img src="/uploads/<?= htmlspecialchars($listing['image']) ?>" 
                             alt="<?= htmlspecialchars($listing['title'] ?? 'Annonce') ?>"
                             class="preview-img">
                    <?php else: ?>
                        <div class="no-image">
                            <i class="fas fa-home"></i>
                            <span>Aucune image</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="listing-details">
                    <h2 class="listing-title"><?= htmlspecialchars($listing['title'] ?? 'Annonce') ?></h2>
                    <p class="listing-description">
                        <?= htmlspecialchars(substr($listing['description'] ?? '', 0, 200)) ?>
                        <?= strlen($listing['description'] ?? '') > 200 ? '...' : '' ?>
                    </p>
                    
                    <div class="listing-stats">
                        <div class="stat-item">
                            <span class="stat-label">Prix total :</span>
                            <span class="stat-value price"><?= number_format($listing['prix_total'] ?? 0, 0, ',', ' ') ?> €</span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Prix du ticket :</span>
                            <span class="stat-value ticket-price"><?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> €</span>
                        </div>
                        
                        <div class="stat-item">
                            <span class="stat-label">Objectif minimum :</span>
                            <span class="stat-value target"><?= $ticketsTotal ?> tickets</span>
                        </div>
                        
                        
                        <?php if (!empty($listing['end_date'])): ?>
                        <div class="stat-item">
                            <span class="stat-label">Date de fin :</span>
                            <span class="stat-value end-date">
                                <?= date('d/m/Y', strtotime($listing['end_date'])) ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations du concours -->
        <div class="contest-info-simple">
            <div class="info-card">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Comment ça marche ?
                </h3>
                
                <div class="info-content">
                    <div class="info-item">
                        <i class="fas fa-ticket-alt"></i>
                        <div>
                            <strong>Achetez des tickets</strong>
                            <span>Plus vous en achetez, plus vous avez de chances de gagner</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-target"></i>
                        <div>
                            <strong>Objectif minimum</strong>
                            <span><?= $ticketsTotal ?> tickets doivent être vendus pour que le vendeur puisse vendre son bien</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-trophy"></i>
                        <div>
                            <strong>Gagnant unique</strong>
                            <span>Un seul gagnant sera tiré au sort parmi tous les participants</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Formulaire d'achat -->
        <?php if (true): // Pas de limite sur l'achat de tickets ?>
            <div class="purchase-form">
                <div class="form-card">
                    <h3 class="section-title">
                        <i class="fas fa-credit-card"></i>
                        Acheter votre ticket
                    </h3>
                    
                    <?php if (!$isLoggedIn): ?>
                        <!-- Connexion requise -->
                        <div class="login-required">
                            <div class="login-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h4 class="login-title">Connexion requise</h4>
                            <p class="login-description">
                                Vous devez être connecté pour acheter un ticket de participation.
                            </p>
                            
                            <div class="login-actions">
                                <a href="/auth/login?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Se connecter
                                </a>
                                
                                <a href="/auth/register?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn btn-outline">
                                    <i class="fas fa-user-plus"></i>
                                    Créer un compte
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Formulaire d'achat -->
                        <form class="purchase-form-content" method="POST" action="/ticket/process-purchase" data-no-ajax="true">
                            <input type="hidden" name="listing_id" value="<?= $listing['id'] ?? '' ?>">
                            
                            <!-- Informations de l'utilisateur -->
                            <div class="user-info">
                                <h4 class="info-title">
                                    <i class="fas fa-user"></i>
                                    Vos informations
                                </h4>
                                
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="info-label">Solde disponible :</span>
                                        <span class="info-value balance">
                                            <?= number_format($userBalance, 0, ',', ' ') ?> €
                                        </span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-label">Prix du ticket :</span>
                                        <span class="info-value ticket-price">
                                            <?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> €
                                        </span>
                                    </div>
                                    
                                    <div class="info-item">
                                        <span class="info-label">Solde après achat :</span>
                                        <span class="info-value remaining-balance">
                                            <?= number_format($userBalance - ($listing['ticket_price'] ?? 0), 0, ',', ' ') ?> €
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Vérification du solde -->
                            <?php if ($userBalance < ($listing['ticket_price'] ?? 0)): ?>
                                <div class="balance-warning">
                                    <div class="warning-icon">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="warning-content">
                                        <h5 class="warning-title">Solde insuffisant</h5>
                                        <p class="warning-description">
                                            Votre solde actuel (<?= number_format($userBalance, 0, ',', ' ') ?> €) 
                                            n'est pas suffisant pour acheter ce ticket (<?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> €).
                                        </p>
                                        
                                        <div class="warning-actions">
                                            <a href="/dashboard" class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                                Recharger mon compte
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Confirmation d'achat -->
                                <div class="purchase-confirmation">
                                    <h4 class="confirmation-title">
                                        <i class="fas fa-check-circle"></i>
                                        Confirmation d'achat
                                    </h4>
                                    
                                    <div class="confirmation-details">
                                        <div class="detail-row">
                                            <span class="detail-label">Bien immobilier :</span>
                                            <span class="detail-value"><?= htmlspecialchars($listing['title'] ?? 'Annonce') ?></span>
                                        </div>
                                        
                                        <div class="detail-row">
                                            <span class="detail-label">Prix du ticket :</span>
                                            <span class="detail-value"><?= number_format($listing['ticket_price'] ?? 0, 0, ',', ' ') ?> €</span>
                                        </div>
                                        
                                        <div class="detail-row">
                                            <span class="detail-label">Votre solde :</span>
                                            <span class="detail-value"><?= number_format($userBalance, 0, ',', ' ') ?> €</span>
                                        </div>
                                        
                                        <div class="detail-row total">
                                            <span class="detail-label">Solde après achat :</span>
                                            <span class="detail-value"><?= number_format($userBalance - ($listing['ticket_price'] ?? 0), 0, ',', ' ') ?> €</span>
                                        </div>
                                    </div>
                                    
                                    <div class="confirmation-actions">
                                        <button type="submit" class="btn btn-primary btn-large">
                                            <i class="fas fa-shopping-cart"></i>
                                            Confirmer l'achat
                                        </button>
                                        
                                        <a href="/listings/view?id=<?= $listing['id'] ?? '' ?>" class="btn btn-outline btn-large">
                                            <i class="fas fa-times"></i>
                                            Annuler
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Tous les tickets vendus -->
            <div class="tickets-sold-out">
                <div class="sold-out-card">
                    <div class="sold-out-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3 class="sold-out-title">Tous les tickets ont été vendus</h3>
                    <p class="sold-out-description">
                        Malheureusement, tous les tickets pour ce concours ont été vendus.
                        Le concours est maintenant fermé aux nouvelles participations.
                    </p>
                    
                    <div class="sold-out-actions">
                        <a href="/listings" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Voir d'autres annonces
                        </a>
                        
                        <a href="/tickets/my-tickets" class="btn btn-outline">
                            <i class="fas fa-ticket-alt"></i>
                            Mes tickets
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Informations importantes -->
        <div class="important-info">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-content">
                    <h4>Informations importantes</h4>
                    <ul class="info-list">
                        <li>L'achat d'un ticket est définitif et non remboursable</li>
                        <li>Après l'achat, vous devrez passer un questionnaire QCM</li>
                        <li>Un score minimum de 50% est requis pour continuer</li>
                        <li>En cas de victoire, le bien vous sera transféré gratuitement</li>
                        <li>Vous recevrez des notifications par email à chaque étape</li>
                        <li>Pour toute question, contactez notre support</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script de gestion de l'achat -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const purchaseForm = document.querySelector('.purchase-form-content');
    
    if (purchaseForm) {
        purchaseForm.addEventListener('submit', function(e) {
            // Confirmation finale
            if (!confirm('Êtes-vous sûr de vouloir acheter ce ticket ? Cette action est définitive et non remboursable.')) {
                e.preventDefault();
                return false;
            }
            
            // Désactivation du bouton pendant le traitement
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
            }
        });
    }
    
    // Animation des éléments
    const listingPreview = document.querySelector('.listing-preview');
    if (listingPreview) {
        setTimeout(() => {
            listingPreview.classList.add('animate');
        }, 100);
    }
    
    const ticketsProgress = document.querySelector('.tickets-progress');
    if (ticketsProgress) {
        setTimeout(() => {
            ticketsProgress.classList.add('animate');
        }, 200);
    }
    
    const purchaseFormElement = document.querySelector('.purchase-form');
    if (purchaseFormElement) {
        setTimeout(() => {
            purchaseFormElement.classList.add('animate');
        }, 300);
    }
    
    // Animation de la barre de progression
    const progressFill = document.querySelector('.progress-fill');
    if (progressFill) {
        setTimeout(() => {
            progressFill.style.transition = 'width 1s ease-out';
        }, 500);
    }
});
</script>
