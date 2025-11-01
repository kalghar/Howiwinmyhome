<?php
/**
 * Vue de dépôt de fonds
 */
?>

<div class="page-header">
    <div class="container">
        <div class="page-title">
            <h1>
                <i class="fas fa-wallet"></i>
                Recharger mon compte
            </h1>
            <p class="page-description">
                Ajoutez des fonds à votre compte pour participer aux concours
            </p>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="deposit-section">
            <div class="deposit-card">
                <div class="deposit-header">
                    <h2 class="deposit-title">
                        <i class="fas fa-euro-sign"></i>
                        Montant du dépôt
                    </h2>
                    <div class="current-balance">
                        <span class="balance-label">Solde actuel :</span>
                        <span class="balance-amount"><?= number_format($userBalance ?? 0, 2, ',', ' ') ?>€</span>
                    </div>
                </div>
                
                <form method="POST" action="/account/process-deposit" class="deposit-form" id="deposit-form" data-no-ajax="true">
                    <!-- Montants prédéfinis -->
                    <div class="amount-presets">
                        <h4 class="presets-title">Montants rapides</h4>
                        <div class="presets-grid">
                            <button type="button" class="preset-btn" data-amount="10">10 €</button>
                            <button type="button" class="preset-btn" data-amount="25">25 €</button>
                            <button type="button" class="preset-btn" data-amount="50">50 €</button>
                            <button type="button" class="preset-btn" data-amount="100">100 €</button>
                            <button type="button" class="preset-btn" data-amount="250">250 €</button>
                            <button type="button" class="preset-btn" data-amount="500">500 €</button>
                        </div>
                    </div>
                    
                    <!-- Montant personnalisé -->
                    <div class="custom-amount">
                        <label for="amount" class="form-label">
                            <i class="fas fa-euro-sign"></i>
                            Montant personnalisé
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   class="form-input"
                                   value="10"
                                   min="1" 
                                   max="1000" 
                                   step="0.01"
                                   required>
                            <span class="input-suffix">€</span>
                        </div>
                        <div class="input-help">
                            Montant minimum : 1€ | Maximum : 1000€
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-plus"></i>
                            Ajouter les fonds
                        </button>
                        <a href="/dashboard" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Retour au tableau de bord
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
