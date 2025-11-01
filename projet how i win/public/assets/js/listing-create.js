/**
 * JAVASCRIPT POUR LA CRÉATION D'ANNONCES - HOW I WIN MY HOME V1
 * 
 * Gestion du drag & drop d'images avec prévisualisation
 * et réorganisation par glisser-déposer
 */

class ImageUploadManager {
    constructor() {
        this.uploadZone = document.getElementById('imageUploadZone');
        this.fileInput = document.getElementById('images');
        this.previewContainer = document.getElementById('imagePreviewContainer');
        this.previewGrid = document.getElementById('imagePreviewGrid');
        this.imageCount = document.getElementById('imageCount');
        this.uploadBtn = document.querySelector('.upload-btn');
        
        this.images = [];
        this.maxImages = 10;
        this.minImages = 3;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.updateUI();
    }
    
    setupEventListeners() {
        // Clic sur la zone d'upload
        this.uploadZone.addEventListener('click', () => {
            this.fileInput.click();
        });
        
        // Clic sur le bouton d'upload
        this.uploadBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.fileInput.click();
        });
        
        // Changement de fichiers
        this.fileInput.addEventListener('change', (e) => {
            this.handleFiles(e.target.files);
        });
        
        // Drag & Drop
        this.uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.uploadZone.classList.add('dragover');
        });
        
        this.uploadZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.uploadZone.classList.remove('dragover');
        });
        
        this.uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.uploadZone.classList.remove('dragover');
            this.handleFiles(e.dataTransfer.files);
        });
    }
    
    handleFiles(files) {
        const fileArray = Array.from(files);
        
        // Vérifier le nombre total d'images
        if (this.images.length + fileArray.length > this.maxImages) {
            alert(`Vous ne pouvez pas ajouter plus de ${this.maxImages} photos au total.`);
            return;
        }
        
        // Traiter chaque fichier
        fileArray.forEach(file => {
            if (this.validateFile(file)) {
                this.addImage(file);
            }
        });
        
        this.updateUI();
    }
    
    validateFile(file) {
        // Vérifier le type de fichier
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert(`Le fichier ${file.name} n'est pas un format d'image valide.`);
            return false;
        }
        
        // Vérifier la taille (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            alert(`Le fichier ${file.name} est trop volumineux (max 5MB).`);
            return false;
        }
        
        return true;
    }
    
    addImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const imageData = {
                id: Date.now() + Math.random(),
                file: file,
                url: e.target.result,
                name: file.name,
                size: file.size,
                isPrimary: this.images.length === 0 // Première image = principale
            };
            
            this.images.push(imageData);
            this.renderImagePreview(imageData);
            this.updateFileInput();
        };
        reader.readAsDataURL(file);
    }
    
    renderImagePreview(imageData) {
        const previewItem = document.createElement('div');
        previewItem.className = `image-preview-item ${imageData.isPrimary ? 'primary' : ''}`;
        previewItem.draggable = true;
        previewItem.dataset.imageId = imageData.id;
        
        previewItem.innerHTML = `
            <img src="${imageData.url}" alt="${imageData.name}">
            <div class="image-preview-number">${this.images.length}</div>
            ${imageData.isPrimary ? '<div class="image-preview-primary-badge">PRINCIPALE</div>' : ''}
            <div class="image-preview-size">${this.formatFileSize(imageData.size)}</div>
            <div class="image-preview-overlay">
                <div class="image-preview-actions">
                    <button type="button" class="image-preview-btn primary" data-action="set-primary" data-image-id="${imageData.id}" title="Définir comme principale">
                        <i class="fas fa-star"></i>
                    </button>
                    <button type="button" class="image-preview-btn remove" data-action="remove-image" data-image-id="${imageData.id}" title="Supprimer">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Gestionnaires d'événements pour les boutons
        const primaryBtn = previewItem.querySelector('[data-action="set-primary"]');
        const removeBtn = previewItem.querySelector('[data-action="remove-image"]');
        
        if (primaryBtn) {
            primaryBtn.addEventListener('click', () => {
                this.setPrimary(imageData.id);
            });
        }
        
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                this.removeImage(imageData.id);
            });
        }
        
        // Drag & Drop pour réorganiser
        previewItem.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', imageData.id);
            previewItem.classList.add('dragging');
        });
        
        previewItem.addEventListener('dragend', () => {
            previewItem.classList.remove('dragging');
        });
        
        previewItem.addEventListener('dragover', (e) => {
            e.preventDefault();
            previewItem.classList.add('drag-over');
        });
        
        previewItem.addEventListener('dragleave', () => {
            previewItem.classList.remove('drag-over');
        });
        
        previewItem.addEventListener('drop', (e) => {
            e.preventDefault();
            previewItem.classList.remove('drag-over');
            
            const draggedId = e.dataTransfer.getData('text/plain');
            if (draggedId !== imageData.id) {
                this.reorderImages(draggedId, imageData.id);
            }
        });
        
        this.previewGrid.appendChild(previewItem);
    }
    
    reorderImages(draggedId, targetId) {
        const draggedIndex = this.images.findIndex(img => img.id == draggedId);
        const targetIndex = this.images.findIndex(img => img.id == targetId);
        
        if (draggedIndex !== -1 && targetIndex !== -1) {
            // Déplacer l'élément dans le tableau
            const draggedImage = this.images.splice(draggedIndex, 1)[0];
            this.images.splice(targetIndex, 0, draggedImage);
            
            // Re-rendre toutes les prévisualisations
            this.renderAllPreviews();
        }
    }
    
    renderAllPreviews() {
        this.previewGrid.innerHTML = '';
        this.images.forEach((imageData, index) => {
            imageData.isPrimary = index === 0;
            this.renderImagePreview(imageData);
        });
    }
    
    setPrimary(imageId) {
        const imageIndex = this.images.findIndex(img => img.id == imageId);
        if (imageIndex !== -1) {
            // Déplacer l'image en première position
            const image = this.images.splice(imageIndex, 1)[0];
            this.images.unshift(image);
            
            // Re-rendre toutes les prévisualisations
            this.renderAllPreviews();
        }
    }
    
    removeImage(imageId) {
        const imageIndex = this.images.findIndex(img => img.id == imageId);
        if (imageIndex !== -1) {
            this.images.splice(imageIndex, 1);
            
            // Supprimer l'élément du DOM
            const previewItem = document.querySelector(`[data-image-id="${imageId}"]`);
            if (previewItem) {
                previewItem.remove();
            }
            
            this.updateFileInput();
            this.updateUI();
        }
    }
    
    updateFileInput() {
        // Créer un nouveau FileList avec les fichiers actuels
        const dt = new DataTransfer();
        this.images.forEach(imageData => {
            dt.items.add(imageData.file);
        });
        this.fileInput.files = dt.files;
    }
    
    updateUI() {
        const count = this.images.length;
        this.imageCount.textContent = count;
        
        // Afficher/masquer la zone de prévisualisation
        if (count > 0) {
            this.previewContainer.style.display = 'block';
        } else {
            this.previewContainer.style.display = 'none';
        }
        
        // Mettre à jour le texte de la zone d'upload
        if (count >= this.maxImages) {
            this.uploadZone.style.pointerEvents = 'none';
            this.uploadZone.style.opacity = '0.5';
        } else {
            this.uploadZone.style.pointerEvents = 'auto';
            this.uploadZone.style.opacity = '1';
        }
        
        // Mettre à jour les étapes de progression
        updateProgressSteps();
    }
    
    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    validateImages() {
        if (this.images.length < this.minImages) {
            alert(`Vous devez fournir au moins ${this.minImages} photos du bien.`);
            return false;
        }
        return true;
    }
}

// Instance globale
let imageManager;

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    imageManager = new ImageUploadManager();
    
    // Calcul initial si des valeurs existent
    calculateTickets();
    
    // Ajouter les événements pour le calcul automatique
    const priceInput = document.getElementById('price');
    const ticketPriceSelect = document.getElementById('ticket_price');
    
    if (priceInput) {
        priceInput.addEventListener('input', calculateTickets);
        priceInput.addEventListener('change', calculateTickets);
    }
    
    if (ticketPriceSelect) {
        ticketPriceSelect.addEventListener('change', calculateTickets);
    }
});

// Calcul automatique des tickets
function calculateTickets() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const ticketPrice = parseFloat(document.getElementById('ticket_price').value) || 0;
    
    if (price > 0 && ticketPrice > 0) {
        // Commission de 10% sur chaque ticket
        const commissionRate = 0.10; // 10%
        const netTicketPrice = ticketPrice * (1 - commissionRate); // Prix net par ticket (90% du prix)
        
        // Calculer le nombre minimum de tickets nécessaires
        // Prix souhaité ÷ Prix net par ticket (après commission)
        const ticketsNeeded = Math.ceil(price / netTicketPrice);
        
        // Revenus bruts (tous les tickets vendus)
        const grossRevenue = ticketsNeeded * ticketPrice;
        
        // Revenus nets pour le vendeur (après commission)
        const netRevenue = ticketsNeeded * netTicketPrice;
        
        // Commission totale
        const totalCommission = grossRevenue - netRevenue;
        
        // Mettre à jour l'affichage
        document.getElementById('calculated-tickets').textContent = ticketsNeeded.toLocaleString();
        document.getElementById('calculated-revenue').textContent = netRevenue.toLocaleString() + '€';
        
        // Mettre à jour le champ caché
        document.getElementById('tickets_needed').value = ticketsNeeded;
        
        // Mettre à jour les étapes de progression
        updateProgressSteps();
    } else {
        document.getElementById('calculated-tickets').textContent = '-';
        document.getElementById('calculated-revenue').textContent = '-';
        document.getElementById('tickets_needed').value = '';
    }
}

// Mise à jour des étapes de progression
function updateProgressSteps() {
    const price = document.getElementById('price').value;
    const ticketPrice = document.getElementById('ticket_price').value;
    const title = document.getElementById('title').value;
    const description = document.getElementById('description').value;
    
    // Étape 1 - Informations générales
    if (price && ticketPrice && title && description) {
        document.querySelector('.progress-step:nth-child(1)').classList.add('completed');
        document.querySelector('.progress-step:nth-child(2)').classList.add('active');
    }
    
    // Étape 2 - Caractéristiques
    const propertyType = document.getElementById('property_type').value;
    const propertySize = document.getElementById('property_size').value;
    const rooms = document.getElementById('rooms').value;
    const bedrooms = document.getElementById('bedrooms').value;
    
    if (propertyType && propertySize && rooms && bedrooms) {
        document.querySelector('.progress-step:nth-child(2)').classList.add('completed');
        document.querySelector('.progress-step:nth-child(3)').classList.add('active');
    }
    
    // Étape 3 - Localisation
    const address = document.getElementById('address').value;
    const city = document.getElementById('city').value;
    const postalCode = document.getElementById('postal_code').value;
    
    if (address && city && postalCode) {
        document.querySelector('.progress-step:nth-child(3)').classList.add('completed');
        document.querySelector('.progress-step:nth-child(4)').classList.add('active');
    }
    
    // Étape 4 - Finalisation
    const endDate = document.getElementById('end_date').value;
    const hasImages = imageManager && imageManager.images.length >= 3;
    
    if (endDate && hasImages) {
        document.querySelector('.progress-step:nth-child(4)').classList.add('completed');
    }
}

// Validation en temps réel
function validateForm() {
    const requiredFields = [
        'title', 'description', 'price', 'ticket_price', 'tickets_needed',
        'property_type', 'property_size', 'rooms', 'bedrooms',
        'address', 'city', 'postal_code', 'end_date'
    ];
    
    let isValid = true;
    
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            field.classList.add('error');
            isValid = false;
        } else {
            field.classList.remove('error');
        }
    });
    
    // Validation des images
    if (!imageManager || !imageManager.validateImages()) {
        isValid = false;
    }
    
    return isValid;
}

// Événements de validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.listing-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
            updateProgressSteps();
        });
        
        input.addEventListener('input', function() {
            this.classList.remove('error');
            updateProgressSteps();
        });
    });
    
    // Validation à la soumission
    form.addEventListener('submit', function(e) {
        console.log('Form submission started');
        console.log('ImageManager:', imageManager);
        console.log('Images count:', imageManager ? imageManager.images.length : 'No imageManager');
        
        // Vérifier qu'il y a des images
        if (!imageManager || imageManager.images.length < 3) {
            e.preventDefault();
            alert('Vous devez sélectionner au moins 3 photos du bien');
            return false;
        }
        
        // Vérifier les documents confidentiels requis
        const requiredDocuments = ['identity_document', 'property_document', 'tax_document'];
        const missingDocuments = [];
        
        requiredDocuments.forEach(docType => {
            const fileInput = document.getElementById(docType);
            if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                missingDocuments.push(docType);
            }
        });
        
        if (missingDocuments.length > 0) {
            e.preventDefault();
            alert('Vous devez fournir tous les documents requis : ' + missingDocuments.join(', '));
            return false;
        }
        
        // Créer un FormData avec les fichiers
        const formData = new FormData();
        
        // Ajouter tous les champs du formulaire
        const formElements = form.querySelectorAll('input, textarea, select');
        formElements.forEach(element => {
            if (element.type === 'file') {
                // Traiter les fichiers (documents confidentiels)
                if (element.files && element.files.length > 0) {
                    formData.append(element.name, element.files[0]);
                }
                return;
            }
            if (element.name && element.value) {
                formData.append(element.name, element.value);
            }
        });
        
        // Ajouter les images
        imageManager.images.forEach((imageData, index) => {
            formData.append('images[]', imageData.file);
        });
        
        // Soumettre via fetch
        e.preventDefault();
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text(); // Récupérer le texte brut d'abord
        })
        .then(text => {
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Response was:', text);
                throw new Error('Invalid JSON response: ' + text.substring(0, 100));
            }
        })
        .then(data => {
            if (data.success) {
                // Afficher le message de succès professionnel
                flashManager.success(data.message, 3000);
                
                // Rediriger après un délai pour laisser le temps de voir le message
                setTimeout(() => {
                    window.location.href = data.redirect || '/dashboard';
                }, 2000);
            } else {
                // Afficher les erreurs
                flashManager.error(data.message, 8000);
                if (data.errors) {
                    // Afficher les erreurs spécifiques
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.querySelector(`[name="${field}"]`);
                        if (errorElement) {
                            errorElement.classList.add('error');
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Erreur lors de la soumission:', error);
            flashManager.error('Une erreur s\'est produite lors de la soumission du formulaire', 8000);
        });
    });
});
