<?php

/**
 * PARTIAL FOOTER - PIED DE PAGE
 * HOW I WIN MY HOME V1
 * 
 * Ce partial gère le pied de page de l'application
 * avec les liens utiles, informations légales et réseaux sociaux
 */

// Récupération de l'année actuelle
$currentYear = date('Y');
?>

<footer class="main-footer-mascabanids" role="contentinfo">
    <div class="footer-container-mascabanids">
        <!-- Section principale avec design organique -->
        <div class="footer-main-mascabanids">
            <!-- Brand et description -->
            <div class="footer-brand-mascabanids">
                <div class="brand-card-mascabanids">
                    <div class="brand-header-mascabanids">
                        <div class="footer-logo-mascabanids">
                            <div class="logo-icon-mascabanids">
                                <i class="fas fa-home"></i>
                                <div class="logo-decoration-mascabanids">
                                    <div class="decoration-dot"></div>
                                    <div class="decoration-dot"></div>
                                    <div class="decoration-dot"></div>
                                </div>
                            </div>
                            <div class="logo-text-mascabanids">
                                <span class="logo-title-mascabanids">How I Win My Home</span>
                                <span class="logo-subtitle-mascabanids">Concours Immobiliers</span>
                            </div>
                        </div>
                    </div>
                    <div class="brand-content-mascabanids">
                        <p class="footer-description-mascabanids">
                            Plateforme de concours immobiliers pour gagner votre logement de rêve.
                            Participez à nos concours et tentez votre chance !
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation rapide -->
            <div class="footer-nav-mascabanids">
                <div class="nav-section-mascabanids">
                    <h4 class="nav-title-mascabanids">
                        <i class="fas fa-link nav-icon-mascabanids"></i>
                        Liens rapides
                    </h4>
                    <ul class="footer-nav-list-mascabanids">
                        <li class="footer-nav-item-mascabanids">
                            <a href="/" class="footer-nav-link-mascabanids">
                                <i class="fas fa-home nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Accueil</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                        <li class="footer-nav-item-mascabanids">
                            <a href="/listings" class="footer-nav-link-mascabanids">
                                <i class="fas fa-search nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Annonces</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                        <li class="footer-nav-item-mascabanids">
                            <a href="/how-it-works" class="footer-nav-link-mascabanids">
                                <i class="fas fa-question-circle nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Comment ça marche</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                        <li class="footer-nav-item-mascabanids">
                            <a href="/faq" class="footer-nav-link-mascabanids">
                                <i class="fas fa-info-circle nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">FAQ</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section-mascabanids">
                    <h4 class="nav-title-mascabanids">
                        <i class="fas fa-life-ring nav-icon-mascabanids"></i>
                        Support
                    </h4>
                    <ul class="footer-nav-list-mascabanids">
                        <li class="footer-nav-item-mascabanids">
                            <a href="/contact" class="footer-nav-link-mascabanids">
                                <i class="fas fa-envelope nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Contact</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                        <li class="footer-nav-item-mascabanids">
                            <a href="/help" class="footer-nav-link-mascabanids">
                                <i class="fas fa-question nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Aide</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                        <li class="footer-nav-item-mascabanids">
                            <a href="/terms" class="footer-nav-link-mascabanids">
                                <i class="fas fa-file-contract nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Conditions générales</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                        <li class="footer-nav-item-mascabanids">
                            <a href="/privacy" class="footer-nav-link-mascabanids">
                                <i class="fas fa-shield-alt nav-icon-mascabanids"></i>
                                <span class="footer-nav-text-mascabanids">Confidentialité</span>
                                <div class="footer-nav-decoration-mascabanids"></div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Réseaux sociaux -->
            <div class="footer-social-mascabanids">
                <div class="social-card-mascabanids">
                    <h4 class="social-title-mascabanids">
                        <i class="fas fa-share-alt social-icon-mascabanids"></i>
                        Suivez-nous
                    </h4>
                    <div class="social-links-mascabanids">
                        <a href="#" class="social-link-mascabanids" aria-label="Facebook" title="Facebook">
                            <i class="fab fa-facebook"></i>
                            <div class="social-decoration-mascabanids"></div>
                        </a>
                        <a href="#" class="social-link-mascabanids" aria-label="Twitter" title="Twitter">
                            <i class="fab fa-twitter"></i>
                            <div class="social-decoration-mascabanids"></div>
                        </a>
                        <a href="#" class="social-link-mascabanids" aria-label="Instagram" title="Instagram">
                            <i class="fab fa-instagram"></i>
                            <div class="social-decoration-mascabanids"></div>
                        </a>
                        <a href="#" class="social-link-mascabanids" aria-label="LinkedIn" title="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                            <div class="social-decoration-mascabanids"></div>
                        </a>
                        <a href="#" class="social-link-mascabanids" aria-label="YouTube" title="YouTube">
                            <i class="fab fa-youtube"></i>
                            <div class="social-decoration-mascabanids"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section inférieure -->
        <div class="footer-bottom-mascabanids">
            <div class="legal-info-mascabanids">
                <p class="copyright-mascabanids">
                    <i class="fas fa-copyright"></i>
                    <?= $currentYear ?> How I Win My Home. Tous droits réservés.
                </p>
                <div class="legal-links-mascabanids">
                    <a href="/terms" class="legal-link-mascabanids">Conditions générales</a>
                    <span class="separator-mascabanids">|</span>
                    <a href="/privacy" class="legal-link-mascabanids">Politique de confidentialité</a>
                    <span class="separator-mascabanids">|</span>
                    <a href="/cookies" class="legal-link-mascabanids">Gestion des cookies</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton retour en haut avec design Mas Cabanids -->
    <button class="back-to-top-mascabanids" aria-label="Retour en haut de la page">
        <i class="fas fa-arrow-up"></i>
        <div class="btn-decoration-mascabanids"></div>
    </button>
</footer>

<!-- Script pour le footer -->
<!-- Le JavaScript est maintenant géré par le fichier global-events.js -->