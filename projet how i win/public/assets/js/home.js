/**
 * HOME.JS - HOW I WIN MY HOME V1
 * ========================================
 *
 * FICHIER JAVASCRIPT DE LA PAGE D'ACCUEIL
 * Fonctionnalités spécifiques à la page d'accueil
 *
 * AUTEUR : How I Win My Home Team
 * VERSION : 1.0.0
 * DATE : 2025-08-17
 * ========================================
 */

// ========================================
// GESTIONNAIRE DE LA PAGE D'ACCUEIL
// ========================================

class HomePageManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupHeroSection();
        this.setupFeatures();
        this.setupTestimonials();
        this.setupNewsletter();
        this.setupScrollAnimations();
        this.setupCounters();
    }
    
    // ========================================
    // SECTION HÉRO
    // ========================================
    
    setupHeroSection() {
        const heroSection = document.querySelector('.hero-section');
        if (!heroSection) return;
        
        // Animation d'entrée des éléments
        const heroElements = heroSection.querySelectorAll('.hero-title, .hero-subtitle, .hero-cta');
        heroElements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                element.style.transition = 'all 0.6s ease-out';
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Parallax effect sur le background
        this.setupParallaxEffect(heroSection);
    }
    
    setupParallaxEffect(element) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            element.style.transform = `translateY(${rate}px)`;
        });
    }
    
    // ========================================
    // SECTION FONCTIONNALITÉS
    // ========================================
    
    setupFeatures() {
        const featureCards = document.querySelectorAll('.feature-card');
        if (!featureCards.length) return;
        
        // Animation au scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, { threshold: 0.1 });
        
        featureCards.forEach(card => {
            observer.observe(card);
        });
        
        // Hover effects
        featureCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                this.animateFeatureCard(card, 'in');
            });
            
            card.addEventListener('mouseleave', () => {
                this.animateFeatureCard(card, 'out');
            });
        });
    }
    
    animateFeatureCard(card, direction) {
        const icon = card.querySelector('.feature-icon');
        const title = card.querySelector('.feature-title');
        const description = card.querySelector('.feature-description');
        
        if (direction === 'in') {
            icon.style.transform = 'scale(1.1) rotate(5deg)';
            title.style.color = 'var(--primary-color)';
            description.style.transform = 'translateY(-5px)';
        } else {
            icon.style.transform = 'scale(1) rotate(0deg)';
            title.style.color = 'var(--gray-900)';
            description.style.transform = 'translateY(0)';
        }
    }
    
    // ========================================
    // SECTION TÉMOIGNAGES
    // ========================================
    
    setupTestimonials() {
        const testimonialSlider = document.querySelector('.testimonial-slider');
        if (!testimonialSlider) return;
        
        this.initTestimonialSlider(testimonialSlider);
    }
    
    initTestimonialSlider(container) {
        const testimonials = container.querySelectorAll('.testimonial');
        let currentIndex = 0;
        
        if (testimonials.length <= 1) return;
        
        // Masquer tous les témoignages sauf le premier
        testimonials.forEach((testimonial, index) => {
            testimonial.style.display = index === 0 ? 'block' : 'none';
        });
        
        // Auto-rotation
        setInterval(() => {
            this.showNextTestimonial(testimonials, currentIndex);
            currentIndex = (currentIndex + 1) % testimonials.length;
        }, 5000);
        
        // Navigation manuelle
        const prevBtn = container.querySelector('.testimonial-prev');
        const nextBtn = container.querySelector('.testimonial-next');
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
                this.showTestimonial(testimonials, currentIndex);
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % testimonials.length;
                this.showTestimonial(testimonials, currentIndex);
            });
        }
    }
    
    showNextTestimonial(testimonials, currentIndex) {
        const nextIndex = (currentIndex + 1) % testimonials.length;
        this.showTestimonial(testimonials, nextIndex);
    }
    
    showTestimonial(testimonials, index) {
        testimonials.forEach((testimonial, i) => {
            if (i === index) {
                testimonial.style.display = 'block';
                testimonial.style.animation = 'fadeInRight 0.5s ease-out';
            } else {
                testimonial.style.display = 'none';
            }
        });
    }
    
    // ========================================
    // NEWSLETTER
    // ========================================
    
    setupNewsletter() {
        const newsletterForm = document.querySelector('.newsletter-form');
        if (!newsletterForm) return;
        
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleNewsletterSubmit(newsletterForm);
        });
        
        // Validation en temps réel
        const emailInput = newsletterForm.querySelector('input[type="email"]');
        if (emailInput) {
            emailInput.addEventListener('input', () => {
                this.validateNewsletterEmail(emailInput);
            });
        }
    }
    
    handleNewsletterSubmit(form) {
        const emailInput = form.querySelector('input[type="email"]');
        const submitBtn = form.querySelector('button[type="submit"]');
        
        if (!this.validateNewsletterEmail(emailInput)) {
            return;
        }
        
        // Désactiver le bouton
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Inscription...';
        
        // Simulation d'envoi (remplacer par votre logique)
        setTimeout(() => {
            this.showNewsletterSuccess(form);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            form.reset();
        }, 2000);
    }
    
    validateNewsletterEmail(input) {
        const email = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            this.showEmailError(input, 'L\'email est requis');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            this.showEmailError(input, 'Format d\'email invalide');
            return false;
        }
        
        this.clearEmailError(input);
        return true;
    }
    
    showEmailError(input, message) {
        this.clearEmailError(input);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'email-error';
        errorDiv.textContent = message;
        errorDiv.style.color = 'var(--error-color)';
        errorDiv.style.fontSize = 'var(--font-size-sm)';
        errorDiv.style.marginTop = 'var(--spacing-xs)';
        
        input.parentNode.appendChild(errorDiv);
        input.classList.add('error');
    }
    
    clearEmailError(input) {
        const existingError = input.parentNode.querySelector('.email-error');
        if (existingError) {
            existingError.remove();
        }
        input.classList.remove('error');
    }
    
    showNewsletterSuccess(form) {
        const successMessage = document.createElement('div');
        successMessage.className = 'newsletter-success';
        successMessage.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>Inscription réussie ! Merci de vous être abonné à notre newsletter.</span>
        `;
        successMessage.style.cssText = `
            background: var(--success-color);
            color: white;
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            text-align: center;
            margin-top: var(--spacing-md);
        `;
        
        form.appendChild(successMessage);
        
        setTimeout(() => {
            successMessage.remove();
        }, 5000);
    }
    
    // ========================================
    // ANIMATIONS AU SCROLL
    // ========================================
    
    setupScrollAnimations() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        if (!animatedElements.length) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, { threshold: 0.1 });
        
        animatedElements.forEach(element => {
            observer.observe(element);
        });
    }
    
    // ========================================
    // COMPTEURS ANIMÉS
    // ========================================
    
    setupCounters() {
        const counters = document.querySelectorAll('.counter');
        if (!counters.length) return;
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounter(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }
    
    animateCounter(counter) {
        const target = parseInt(counter.dataset.target);
        const duration = 2000; // 2 secondes
        const step = target / (duration / 16); // 60 FPS
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            counter.textContent = Math.floor(current).toLocaleString();
        }, 16);
    }
}

// ========================================
// GESTIONNAIRE DES STATISTIQUES
// ========================================

class StatsManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.loadStats();
        this.setupRefresh();
    }
    
    async loadStats() {
        try {
            // Simulation de chargement des statistiques
            const stats = {
                users: 15420,
                properties: 89,
                winners: 156,
                tickets: 45230
            };
            
            this.updateStatsDisplay(stats);
        } catch (error) {
            console.error('Erreur lors du chargement des statistiques:', error);
        }
    }
    
    updateStatsDisplay(stats) {
        Object.keys(stats).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"]`);
            if (element) {
                element.textContent = stats[key].toLocaleString();
            }
        });
    }
    
    setupRefresh() {
        // Rafraîchir les stats toutes les 5 minutes
        setInterval(() => {
            this.loadStats();
        }, 5 * 60 * 1000);
    }
}

// ========================================
// GESTIONNAIRE DES ACTUALITÉS
// ========================================

class NewsManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.loadLatestNews();
        this.setupNewsSlider();
    }
    
    async loadLatestNews() {
        try {
            // Simulation de chargement des actualités
            const news = [
                {
                    title: 'Nouveau concours lancé !',
                    excerpt: 'Découvrez notre dernière propriété mise en jeu...',
                    date: '2025-08-17',
                    image: '/assets/images/news-1.jpg'
                },
                {
                    title: 'Félicitations aux gagnants !',
                    excerpt: 'Retrouvez les photos des derniers heureux gagnants...',
                    date: '2025-08-15',
                    image: '/assets/images/news-2.jpg'
                }
            ];
            
            this.displayNews(news);
        } catch (error) {
            console.error('Erreur lors du chargement des actualités:', error);
        }
    }
    
    displayNews(news) {
        const newsContainer = document.querySelector('.latest-news');
        if (!newsContainer) return;
        
        const newsHTML = news.map(item => `
            <article class="news-item">
                <div class="news-image">
                    <img src="${item.image}" alt="${item.title}" loading="lazy">
                </div>
                <div class="news-content">
                    <h3 class="news-title">${item.title}</h3>
                    <p class="news-excerpt">${item.excerpt}</p>
                    <time class="news-date">${new Date(item.date).toLocaleDateString('fr-FR')}</time>
                </div>
            </article>
        `).join('');
        
        newsContainer.innerHTML = newsHTML;
    }
    
    setupNewsSlider() {
        const newsSlider = document.querySelector('.news-slider');
        if (!newsSlider) return;
        
        // Logique du slider d'actualités
        let currentSlide = 0;
        const slides = newsSlider.querySelectorAll('.news-item');
        
        if (slides.length <= 1) return;
        
        setInterval(() => {
            slides[currentSlide].style.opacity = '0';
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].style.opacity = '1';
        }, 4000);
    }
}

// ========================================
// INITIALISATION
// ========================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialiser les gestionnaires de la page d'accueil
    window.homePageManager = new HomePageManager();
    window.statsManager = new StatsManager();
    window.newsManager = new NewsManager();
    
    console.log('🏠 Page d\'accueil initialisée avec succès !');
});

// ========================================
// GESTION DES ERREURS
// ========================================

window.addEventListener('error', (e) => {
    console.error('❌ Erreur sur la page d\'accueil:', e.error);
});

// ========================================
// EXPORT DES CLASSES
// ========================================

if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        HomePageManager,
        StatsManager,
        NewsManager
    };
}
