<?php
/**
 * Vue de la page "Contact" - HOW I WIN MY HOME V1
 * 
 * Formulaire de contact moderne et sécurisé
 * AUCUNE information de contact direct affichée
 */
?>

<div class="contact-page">
    <!-- En-tête de la page -->
    <div class="contact-page-header">
        <div class="container">
            <h1 class="contact-page-title">Contactez-nous</h1>
            <p class="contact-page-subtitle">Nous sommes là pour vous aider et répondre à vos questions</p>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <!-- Section d'introduction -->
            <section class="contact-intro">
                <h2>Comment nous contacter ?</h2>
                <p class="lead">
                    Vous avez des questions sur nos concours immobiliers ? 
                    Besoin d'aide pour participer ? Notre équipe est là pour vous accompagner !
                </p>
                
                <!-- Méthodes de contact (sans informations directes) -->
                <div class="contact-methods">
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4>Formulaire de contact</h4>
                        <p>Envoyez-nous votre message via le formulaire ci-dessous</p>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Réponse rapide</h4>
                        <p>Nous répondons sous 24h en semaine</p>
                    </div>
                    
                    <div class="contact-method">
                        <div class="contact-method-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Confidentialité</h4>
                        <p>Vos données sont protégées et sécurisées</p>
                    </div>
                </div>
            </section>
            
            <!-- Formulaire de contact -->
            <section class="contact-form-section">
                <h3>Envoyez-nous un message</h3>
                
                <form action="/contact" method="POST" class="contact-form" id="contactForm" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">Prénom *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="firstname" 
                                   name="firstname" 
                                   required 
                                   placeholder="Votre prénom">
                            <div class="invalid-feedback">
                                Veuillez saisir votre prénom.
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastname">Nom *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="lastname" 
                                   name="lastname" 
                                   required 
                                   placeholder="Votre nom">
                            <div class="invalid-feedback">
                                Veuillez saisir votre nom.
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               required 
                               placeholder="votre.email@exemple.com">
                        <div class="invalid-feedback">
                            Veuillez saisir une adresse email valide.
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <select class="form-control" id="subject" name="subject" required>
                            <option value="">Choisissez un sujet</option>
                            <option value="general">Question générale</option>
                            <option value="technical">Problème technique</option>
                            <option value="billing">Question de facturation</option>
                            <option value="participation">Participation aux concours</option>
                            <option value="listing">Création d'annonce</option>
                            <option value="support">Support utilisateur</option>
                            <option value="other">Autre</option>
                        </select>
                        <div class="invalid-feedback">
                            Veuillez sélectionner un sujet.
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea class="form-control" 
                                  id="message" 
                                  name="message" 
                                  rows="5" 
                                  required 
                                  placeholder="Décrivez votre question ou votre problème en détail..."></textarea>
                        <div class="invalid-feedback">
                            Veuillez saisir votre message.
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="privacy" 
                                   name="privacy" 
                                   required>
                            <label class="form-check-label" for="privacy">
                                J'accepte que mes données soient traitées conformément à la 
                                <a href="/privacy" target="_blank">politique de confidentialité</a> *
                            </label>
                            <div class="invalid-feedback">
                                Vous devez accepter la politique de confidentialité.
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="spinner"></span>
                        Envoyer le message
                    </button>
                </form>
            </section>
            
            <!-- FAQ rapide -->
            <section class="quick-faq">
                <h3>Questions fréquemment posées</h3>
                <div class="row">
                    <div class="faq-item">
                        <h4>Comment fonctionne le processus de sélection ?</h4>
                        <p>Un jury indépendant évalue les participants selon des critères transparents et équitables. Chaque candidat est évalué sur les mêmes bases.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Combien coûtent les tickets ?</h4>
                        <p>Le prix varie selon la valeur du bien immobilier, généralement entre 5€ et 20€ par ticket. Plus vous participez, plus vous augmentez vos chances !</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Puis-je acheter plusieurs tickets ?</h4>
                        <p>Oui ! Vous pouvez acheter autant de tickets que vous le souhaitez pour un même bien. Plus vous participez, plus vous augmentez vos chances de gagner.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Comment le gagnant est-il sélectionné ?</h4>
                        <p>Un jury indépendant évalue les participants selon des critères transparents et équitables. Le processus est entièrement automatisé et vérifiable.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Que se passe-t-il si je gagne ?</h4>
                        <p>Vous êtes contacté immédiatement par notre équipe et accompagné dans toutes les étapes de finalisation de l'achat de votre bien immobilier.</p>
                    </div>
                    
                    <div class="faq-item">
                        <h4>Les concours sont-ils légaux ?</h4>
                        <p>Absolument ! Tous nos concours respectent la législation française et européenne. Nous sommes enregistrés et contrôlés par les autorités compétentes.</p>
                    </div>
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
                        
                        <div class="cta-actions">
                            <?php if (!$isLoggedIn): ?>
                                <button type="button" class="btn btn-primary btn-large" data-auth-action="register">
                                    <i class="fas fa-rocket"></i>
                                    Commencer l'aventure
                                </button>
                                <button type="button" class="btn btn-outline btn-large" data-auth-action="login">
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
    </div>
</div>

<!-- JavaScript pour la validation et l'envoi du formulaire -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Validation en temps réel
    const inputs = form.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
    });
    
    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });
    
    // Validation d'un champ
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        
        // Supprimer les classes d'erreur précédentes
        field.classList.remove('error', 'success');
        
        // Validation selon le type
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        } else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
            }
        }
        
        // Appliquer le style approprié
        if (isValid && value) {
            field.classList.add('success');
        } else if (!isValid) {
            field.classList.add('error');
        }
        
        return isValid;
    }
    
    // Validation du formulaire complet
    function validateForm() {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        // Validation de la checkbox
        const privacyCheckbox = document.getElementById('privacy');
        if (!privacyCheckbox.checked) {
            privacyCheckbox.classList.add('error');
            isValid = false;
        } else {
            privacyCheckbox.classList.remove('error');
        }
        
        return isValid;
    }
    
    // Envoi du formulaire
    function submitForm() {
        // Afficher l'état de chargement
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Envoi en cours...';
        
        // Simuler l'envoi (remplacer par un vrai appel AJAX)
        setTimeout(() => {
            // Succès
            showSuccessMessage('Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            
            // Réinitialiser le formulaire
            form.reset();
            inputs.forEach(input => {
                input.classList.remove('success', 'error');
            });
            
            // Restaurer le bouton
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Envoyer le message';
            
        }, 2000);
    }
    
    // Message de succès
    function showSuccessMessage(message) {
        const toast = document.createElement('div');
        toast.className = 'success-toast';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'polite');
        toast.textContent = message;
        // Les styles sont maintenant dans contact.css
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('slide-out');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, 5000);
    }
});
</script>
