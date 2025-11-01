/**
 * IMAGE-CAROUSEL.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * Gestionnaire de carrousel d'images pour les annonces
 * Permet de naviguer entre toutes les photos d'une annonce
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-01-18
 * ========================================
 */

class ImageCarousel {
    constructor() {
        this.carousels = new Map();
        this.init();
    }

    init() {
        // Initialiser tous les carrousels présents sur la page
        document.querySelectorAll('.image-carousel, .home-image-carousel, .components-image-carousel').forEach(carousel => {
            this.initCarousel(carousel);
        });

        // Écouter les clics sur les boutons de navigation
        document.addEventListener('click', (e) => {
            if (e.target.closest('.carousel-btn, .home-carousel-btn, .components-carousel-btn')) {
                this.handleNavigation(e.target.closest('.carousel-btn, .home-carousel-btn, .components-carousel-btn'));
            }

            if (e.target.closest('.indicator')) {
                this.handleIndicator(e.target.closest('.indicator'));
            }
        });

        // Navigation au clavier
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                const activeCarousel = document.querySelector('.image-carousel:hover, .home-image-carousel:hover, .components-image-carousel:hover');
                if (activeCarousel) {
                    e.preventDefault();
                    this.handleKeyboard(e.key, activeCarousel);
                }
            }
        });
    }

    initCarousel(carouselElement) {
        const listingId = carouselElement.dataset.listingId;
        const slides = carouselElement.querySelectorAll('.carousel-slide, .home-carousel-slide, .components-carousel-slide');
        const indicators = carouselElement.querySelectorAll('.indicator');
        const counter = null; // Compteur supprimé


        if (slides.length <= 1) {
            // Masquer les contrôles si une seule image
            const controls = carouselElement.querySelector('.carousel-controls, .home-carousel-controls, .components-carousel-controls');
            const indicatorsContainer = carouselElement.querySelector('.carousel-indicators, .home-carousel-indicators, .components-carousel-indicators');
            if (controls) controls.style.display = 'none';
            if (indicatorsContainer) indicatorsContainer.style.display = 'none';
            return;
        }

        this.carousels.set(listingId, {
            element: carouselElement,
            slides: slides,
            indicators: indicators,
            counter: counter,
            currentSlide: 0,
            totalSlides: slides.length
        });
    }

    handleNavigation(button) {
        const listingId = button.dataset.listingId;
        const carousel = this.carousels.get(listingId);

        if (!carousel) return;

        const isNext = button.classList.contains('next');

        if (isNext) {
            carousel.currentSlide = (carousel.currentSlide + 1) % carousel.totalSlides;
        } else {
            carousel.currentSlide = (carousel.currentSlide - 1 + carousel.totalSlides) % carousel.totalSlides;
        }

        this.updateCarousel(carousel);
    }

    handleIndicator(indicator) {
        const listingId = indicator.dataset.listingId;
        const slideIndex = parseInt(indicator.dataset.slide);
        const carousel = this.carousels.get(listingId);

        if (!carousel) return;

        carousel.currentSlide = slideIndex;
        this.updateCarousel(carousel);
    }

    handleKeyboard(key, carouselElement) {
        const listingId = carouselElement.dataset.listingId;
        const carousel = this.carousels.get(listingId);

        if (!carousel) return;

        if (key === 'ArrowLeft') {
            carousel.currentSlide = (carousel.currentSlide - 1 + carousel.totalSlides) % carousel.totalSlides;
        } else if (key === 'ArrowRight') {
            carousel.currentSlide = (carousel.currentSlide + 1) % carousel.totalSlides;
        }

        this.updateCarousel(carousel);
    }

    updateCarousel(carousel) {
        // Mettre à jour les slides
        carousel.slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === carousel.currentSlide);
        });

        // Mettre à jour les indicateurs
        carousel.indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === carousel.currentSlide);
        });

        // Compteur supprimé
    }

    // Méthode pour ajouter un nouveau carrousel dynamiquement
    addCarousel(carouselElement) {
        this.initCarousel(carouselElement);
    }

    // Méthode pour supprimer un carrousel
    removeCarousel(listingId) {
        this.carousels.delete(listingId);
    }
}

// Initialiser le carrousel quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    window.imageCarousel = new ImageCarousel();
});

// Exporter pour utilisation globale
window.ImageCarousel = ImageCarousel;
