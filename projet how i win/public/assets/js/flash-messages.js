/**
 * GESTIONNAIRE DE MESSAGES FLASH - HOW I WIN MY HOME V1
 * 
 * Système de notification moderne et professionnel
 */

class FlashMessageManager {
    constructor() {
        this.container = null;
        this.init();
    }
    
    /**
     * Initialise le gestionnaire de messages
     */
    init() {
        // Créer le container s'il n'existe pas
        this.createContainer();
        
        // Écouter les messages flash du serveur
        this.listenForServerMessages();
    }
    
    /**
     * Crée le container pour les messages flash
     */
    createContainer() {
        this.container = document.getElementById('flash-messages');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'flash-messages';
            this.container.className = 'flash-messages';
            document.body.appendChild(this.container);
        }
    }
    
    /**
     * Écoute les messages flash du serveur
     */
    listenForServerMessages() {
        // Vérifier s'il y a des messages flash dans le DOM
        const serverMessages = document.querySelectorAll('[data-flash-message]');
        serverMessages.forEach(element => {
            const type = element.dataset.flashType || 'info';
            const message = element.textContent || element.dataset.flashMessage;
            this.show(type, message);
            element.remove(); // Supprimer l'élément du DOM
        });
    }
    
    /**
     * Affiche un message flash
     * @param {string} type Type de message (success, error, warning, info)
     * @param {string} message Message à afficher
     * @param {number} duration Durée d'affichage en ms (0 = permanent)
     */
    show(type = 'info', message, duration = 5000) {
        const messageElement = this.createMessageElement(type, message);
        this.container.appendChild(messageElement);
        
        // Forcer le reflow pour déclencher l'animation
        messageElement.offsetHeight;
        
        // Afficher le message
        messageElement.classList.add('show');
        
        // Auto-fermeture si une durée est spécifiée
        if (duration > 0) {
            setTimeout(() => {
                this.hide(messageElement);
            }, duration);
        }
        
        return messageElement;
    }
    
    /**
     * Crée un élément de message
     * @param {string} type Type de message
     * @param {string} message Message à afficher
     * @returns {HTMLElement} Élément de message
     */
    createMessageElement(type, message) {
        const messageElement = document.createElement('div');
        messageElement.className = `flash-message ${type}`;
        
        // Déterminer l'icône selon le type
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };
        
        // Déterminer le titre selon le type
        const titles = {
            success: 'Succès',
            error: 'Erreur',
            warning: 'Attention',
            info: 'Information'
        };
        
        messageElement.innerHTML = `
            <div class="icon">
                <i class="${icons[type] || icons.info}"></i>
            </div>
            <div class="content">
                <div class="title">${titles[type] || 'Information'}</div>
                <div class="message">${message}</div>
            </div>
            <button class="close" data-action="close-flash">
                <i class="fas fa-times"></i>
            </button>
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        `;
        
        // Gestionnaire d'événement pour le bouton de fermeture
        const closeBtn = messageElement.querySelector('[data-action="close-flash"]');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.hide(messageElement);
            });
        }
        
        return messageElement;
    }
    
    /**
     * Masque un message
     * @param {HTMLElement} messageElement Élément de message à masquer
     */
    hide(messageElement) {
        if (messageElement && messageElement.parentElement) {
            messageElement.classList.add('hide');
            setTimeout(() => {
                if (messageElement.parentElement) {
                    messageElement.parentElement.removeChild(messageElement);
                }
            }, 300);
        }
    }
    
    /**
     * Affiche un message de succès
     * @param {string} message Message de succès
     * @param {number} duration Durée d'affichage
     */
    success(message, duration = 5000) {
        return this.show('success', message, duration);
    }
    
    /**
     * Affiche un message d'erreur
     * @param {string} message Message d'erreur
     * @param {number} duration Durée d'affichage
     */
    error(message, duration = 8000) {
        return this.show('error', message, duration);
    }
    
    /**
     * Affiche un message d'avertissement
     * @param {string} message Message d'avertissement
     * @param {number} duration Durée d'affichage
     */
    warning(message, duration = 6000) {
        return this.show('warning', message, duration);
    }
    
    /**
     * Affiche un message d'information
     * @param {string} message Message d'information
     * @param {number} duration Durée d'affichage
     */
    info(message, duration = 5000) {
        return this.show('info', message, duration);
    }
    
    /**
     * Supprime tous les messages
     */
    clear() {
        const messages = this.container.querySelectorAll('.flash-message');
        messages.forEach(message => this.hide(message));
    }
}

// Initialiser le gestionnaire de messages flash
const flashManager = new FlashMessageManager();

// Exposer globalement pour utilisation dans d'autres scripts
window.flashManager = flashManager;
