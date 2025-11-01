<?php
/**
 * Vue FAQ - HOW I WIN MY HOME V1
 * Page des questions fréquemment posées
 */
?>

<!-- En-tête de la page -->
<header class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-title">Questions fréquemment posées</h1>
            <p class="page-subtitle">
                Trouvez rapidement les réponses à toutes vos questions sur notre plateforme de concours immobiliers
            </p>
        </div>
    </div>
</header>

<!-- Contenu principal -->
<main class="main-content">
    <div class="container">
        <!-- Section FAQ -->
        <section class="faq-section">
            <div class="faq-grid">
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item" data-faq="<?= $index ?>">
                    <div class="faq-header">
                        <h3 class="faq-question"><?= htmlspecialchars($faq['question']) ?></h3>
                        <button class="faq-toggle" aria-label="Afficher la réponse">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="faq-answer">
                        <p><?= htmlspecialchars($faq['reponse']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Section CTA -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2 class="cta-title">Prêt à tenter votre chance ?</h2>
                    <p class="cta-subtitle">
                        Rejoignez notre communauté et participez à nos concours exclusifs.
                        Votre rêve immobilier n'est qu'à quelques clics !
                    </p>
                    
                    <div class="faq-actions">
                        <?php if (!$isLoggedIn): ?>
                            <button type="button" class="btn btn-primary btn-large" data-modal="register">
                                <i class="fas fa-rocket"></i>
                                Commencer l'aventure
                            </button>
                            <button type="button" class="btn btn-outline btn-large" data-modal="login">
                                <i class="fas fa-sign-in-alt"></i>
                                Se connecter
                            </button>
                            <a href="/listings" class="btn btn-outline btn-large">
                                <i class="fas fa-search"></i>
                                Découvrir les annonces
                            </a>
                        <?php else: ?>
                            <a href="/listings" class="btn btn-primary btn-large">
                                <i class="fas fa-search"></i>
                                Découvrir les annonces
                            </a>
                            <a href="/dashboard" class="btn btn-outline btn-large">
                                <i class="fas fa-tachometer-alt"></i>
                                Mon espace personnel
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- JavaScript pour l'interactivité FAQ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const toggle = item.querySelector('.faq-toggle');
        const answer = item.querySelector('.faq-answer');
        
        toggle.addEventListener('click', function() {
            const isOpen = item.classList.contains('open');
            
            // Fermer tous les autres items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('open');
                    otherItem.querySelector('.faq-answer').style.maxHeight = '0';
                    otherItem.querySelector('.faq-toggle i').className = 'fas fa-chevron-down';
                }
            });
            
            // Toggle de l'item actuel
            if (isOpen) {
                item.classList.remove('open');
                answer.style.maxHeight = '0';
                toggle.querySelector('i').className = 'fas fa-chevron-down';
            } else {
                item.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                toggle.querySelector('i').className = 'fas fa-chevron-up';
            }
        });
    });
});
</script>
