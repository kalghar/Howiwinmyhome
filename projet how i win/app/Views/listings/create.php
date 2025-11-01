<?php

/**
 * VUE DE CRÉATION D'ANNONCE - HOW I WIN MY HOME V1
 * 
 * Formulaire de création d'annonce immobilière
 */

// Récupération des données depuis le contrôleur
$user = $data['user'] ?? [];
$errors = $data['errors'] ?? [];
$old = $data['old'] ?? [];
$isLoggedIn = $data['isLoggedIn'] ?? false;
$userRole = $data['userRole'] ?? null;

// Fonction helper pour afficher les erreurs (gère les arrays)
function displayError($errors, $field)
{
    if (!isset($errors[$field])) return '';
    $error = $errors[$field];
    return htmlspecialchars(is_array($error) ? implode(', ', $error) : $error);
}
?>

<div class="create-listing-page">
    <div class="create-listing-container">
        <!-- En-tête héroïque Mas Cabanids -->
        <div class="page-header">
            <div class="header-content">
                <div class="title-section">
                    <h1 class="page-title">
                        <i class="fas fa-home"></i>
                        Créer une annonce
                    </h1>
                    <p class="page-description">
                        Transformez votre bien immobilier en concours passionnant et vendez-le de manière équitable
                    </p>
                </div>
                <div class="header-actions">
                    <a href="/listings" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        Voir les annonces
                    </a>
                </div>
            </div>
        </div>

        <!-- Progress Stepper Mas Cabanids -->
        <div class="progress-stepper">
            <div class="stepper-container">
                <div class="stepper-line"></div>
                <div class="stepper-step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Informations générales</div>
                </div>
                <div class="stepper-step">
                    <div class="step-number">2</div>
                    <div class="step-label">Caractéristiques</div>
                </div>
                <div class="stepper-step">
                    <div class="step-number">3</div>
                    <div class="step-label">Localisation</div>
                </div>
                <div class="stepper-step">
                    <div class="step-number">4</div>
                    <div class="step-label">Finalisation</div>
                </div>
            </div>
        </div>

        <!-- Formulaire de création - Disposition Mas Cabanids -->
        <form action="/listings/store" method="POST" enctype="multipart/form-data" class="listing-form">
            <!-- Protection CSRF -->
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\SecurityManager::getInstance()->generateCsrfToken('listing_form')) ?>">
            <input type="hidden" name="csrf_identifier" value="listing_form">

            <!-- Grille principale inspirée de Mas Cabanids -->
            <div class="form-grid-mascabanids">

                <!-- Section principale - Informations générales -->
                <div class="main-form-section">
                    <div class="form-section">
                        <h2 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations générales
                        </h2>

                        <div class="form-group">
                            <label for="title" class="form-label">Titre de l'annonce *</label>
                            <input type="text" id="title" name="title" class="form-input"
                                value="<?= htmlspecialchars($old['title'] ?? '') ?>" required>
                            <?php if (isset($errors['title'])): ?>
                                <div class="form-error"><?= displayError($errors, 'title') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description *</label>
                            <textarea id="description" name="description" class="form-textarea" rows="5" required><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                            <?php if (isset($errors['description'])): ?>
                                <div class="form-error"><?= displayError($errors, 'description') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price" class="form-label">Prix du bien (€) *</label>
                                <input type="number" id="price" name="price" class="form-input"
                                    value="<?= htmlspecialchars($old['price'] ?? '') ?>" min="1" required
                                    data-calculate-tickets>
                                <div class="form-hint">Prix de vente souhaité pour votre bien</div>
                                <?php if (isset($errors['price'])): ?>
                                    <div class="form-error"><?= displayError($errors, 'price') ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="ticket_price" class="form-label">Prix d'un ticket (€) *</label>
                                <select id="ticket_price" name="ticket_price" class="form-select" required data-calculate-tickets>
                                    <option value="">Sélectionner</option>
                                    <option value="5" <?= ($old['ticket_price'] ?? '') == '5' ? 'selected' : '' ?>>5€ - Concours accessible</option>
                                    <option value="10" <?= ($old['ticket_price'] ?? '') == '10' ? 'selected' : '' ?>>10€ - Concours standard</option>
                                    <option value="15" <?= ($old['ticket_price'] ?? '') == '15' ? 'selected' : '' ?>>15€ - Concours premium</option>
                                    <option value="20" <?= ($old['ticket_price'] ?? '') == '20' ? 'selected' : '' ?>>20€ - Concours exclusif</option>
                                </select>
                                <div class="form-hint">Plus le prix est élevé, moins il y aura de participants</div>
                                <?php if (isset($errors['ticket_price'])): ?>
                                    <div class="form-error"><?= displayError($errors, 'ticket_price') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tickets_needed" class="form-label">Nombre minimum de tickets à vendre *</label>
                            <input type="number" id="tickets_needed" name="tickets_needed" class="form-input"
                                value="<?= htmlspecialchars($old['tickets_needed'] ?? '') ?>" min="1" required readonly>
                            <div class="form-hint">Nombre minimum de tickets qui doivent être vendus avant la fin du concours pour atteindre votre prix de vente</div>
                            <?php if (isset($errors['tickets_needed'])): ?>
                                <div class="form-error"><?= displayError($errors, 'tickets_needed') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Calcul automatique et informations -->
                <div class="sidebar-section">
                    <!-- Calcul automatique des tickets -->
                    <div class="calculation-card">
                        <div class="calculation-header">
                            <i class="fas fa-calculator"></i>
                            <h3 class="calculation-title">Calcul automatique</h3>
                        </div>
                        <div class="calculation-grid">
                            <div class="calculation-item">
                                <div class="calculation-label">Tickets minimum à vendre :</div>
                                <div class="calculation-value" id="calculated-tickets">-</div>
                            </div>
                            <div class="calculation-item">
                                <div class="calculation-label">Vos revenus nets :</div>
                                <div class="calculation-value" id="calculated-revenue">-</div>
                            </div>
                        </div>
                        <div class="calculation-note">
                            <i class="fas fa-info-circle"></i>
                            <span>Ces tickets doivent être vendus avant la fin du concours pour atteindre votre prix de vente</span>
                        </div>
                    </div>

                    <!-- Explication du concept -->
                    <div class="concept-card">
                        <div class="concept-header">
                            <i class="fas fa-lightbulb"></i>
                            <h4>Comment ça marche ?</h4>
                        </div>
                        <div class="concept-content">
                            <p>Votre concours se termine automatiquement lorsque le <strong>nombre minimum de tickets</strong> est atteint, ou à la <strong>date de fin</strong> que vous définissez.</p>
                            <ul>
                                <li><strong>Si l'objectif est atteint</strong> : Le concours se termine et un gagnant est sélectionné</li>
                                <li><strong>Si l'objectif n'est pas atteint</strong> : Les tickets sont remboursés aux participants</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Section caractéristiques -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-home"></i>
                        Caractéristiques du bien
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="property_type" class="form-label">Type de bien *</label>
                            <select id="property_type" name="property_type" class="form-select" required>
                                <option value="">Sélectionner</option>
                                <option value="apartment" <?= ($old['property_type'] ?? '') == 'apartment' ? 'selected' : '' ?>>Appartement</option>
                                <option value="house" <?= ($old['property_type'] ?? '') == 'house' ? 'selected' : '' ?>>Maison</option>
                                <option value="villa" <?= ($old['property_type'] ?? '') == 'villa' ? 'selected' : '' ?>>Villa</option>
                                <option value="studio" <?= ($old['property_type'] ?? '') == 'studio' ? 'selected' : '' ?>>Studio</option>
                                <option value="loft" <?= ($old['property_type'] ?? '') == 'loft' ? 'selected' : '' ?>>Loft</option>
                                <option value="other" <?= ($old['property_type'] ?? '') == 'other' ? 'selected' : '' ?>>Autre</option>
                            </select>
                            <?php if (isset($errors['property_type'])): ?>
                                <div class="form-error"><?= displayError($errors, 'property_type') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="property_size" class="form-label">Surface (m²) *</label>
                            <input type="number" id="property_size" name="property_size" class="form-input"
                                value="<?= htmlspecialchars($old['property_size'] ?? '') ?>" min="1" required>
                            <?php if (isset($errors['property_size'])): ?>
                                <div class="form-error"><?= displayError($errors, 'property_size') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rooms" class="form-label">Nombre de pièces *</label>
                            <input type="number" id="rooms" name="rooms" class="form-input"
                                value="<?= htmlspecialchars($old['rooms'] ?? '') ?>" min="1" required>
                            <?php if (isset($errors['rooms'])): ?>
                                <div class="form-error"><?= displayError($errors, 'rooms') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="bedrooms" class="form-label">Nombre de chambres *</label>
                            <input type="number" id="bedrooms" name="bedrooms" class="form-input"
                                value="<?= htmlspecialchars($old['bedrooms'] ?? '') ?>" min="0" required>
                            <?php if (isset($errors['bedrooms'])): ?>
                                <div class="form-error"><?= displayError($errors, 'bedrooms') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Section localisation -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Localisation
                    </h2>

                    <div class="form-group">
                        <label for="address" class="form-label">Adresse complète *</label>
                        <textarea id="address" name="address" class="form-textarea" rows="3" required><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                        <?php if (isset($errors['address'])): ?>
                            <div class="form-error"><?= displayError($errors, 'address') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city" class="form-label">Ville *</label>
                            <input type="text" id="city" name="city" class="form-input"
                                value="<?= htmlspecialchars($old['city'] ?? '') ?>" required>
                            <?php if (isset($errors['city'])): ?>
                                <div class="form-error"><?= displayError($errors, 'city') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="postal_code" class="form-label">Code postal *</label>
                            <input type="text" id="postal_code" name="postal_code" class="form-input"
                                value="<?= htmlspecialchars($old['postal_code'] ?? '') ?>" required>
                            <?php if (isset($errors['postal_code'])): ?>
                                <div class="form-error"><?= displayError($errors, 'postal_code') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images et documents - Section originale conservée -->
            <div class="form-section">
                <h2 class="section-title">
                    <i class="fas fa-image"></i>
                    Images et documents
                </h2>

                <!-- Images du bien -->
                <div class="form-group">
                    <label class="form-label">Photos du bien *</label>

                    <!-- Zone de drag & drop -->
                    <div class="image-upload-zone" id="imageUploadZone">
                        <div class="upload-content">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <h3 class="upload-title">Glissez vos photos ici</h3>
                            <p class="upload-subtitle">ou cliquez pour sélectionner</p>
                            <p class="upload-requirements">3-10 photos • JPG, PNG, GIF • Max 5MB par image</p>
                            <button type="button" class="btn btn-outline upload-btn">
                                <i class="fas fa-plus"></i>
                                Sélectionner des photos
                            </button>
                        </div>
                        <input type="file" id="images" name="images[]" class="hidden-file-input" accept="image/*" multiple>
                    </div>

                    <!-- Zone de prévisualisation -->
                    <div class="image-preview-container" id="imagePreviewContainer" class="image-preview-hidden">
                        <div class="preview-header">
                            <h4>Photos sélectionnées (<span id="imageCount">0</span>/10)</h4>
                            <p class="preview-hint">Glissez pour réorganiser l'ordre</p>
                        </div>
                        <div class="image-preview-grid" id="imagePreviewGrid">
                            <!-- Les prévisualisations seront ajoutées ici -->
                        </div>
                    </div>

                    <?php if (isset($errors['images'])): ?>
                        <div class="form-error"><?= displayError($errors, 'images') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Documents de vérification -->
                <div class="documents-section">
                    <h3 class="documents-title">
                        <i class="fas fa-file-alt"></i>
                        Documents de vérification requis
                    </h3>
                    <p class="documents-description">
                        Ces documents sont nécessaires pour vérifier votre identité et la propriété du bien.
                        Ils seront examinés par notre équipe avant la validation de votre annonce.
                    </p>

                    <div class="documents-grid">
                        <div class="document-item">
                            <label for="identity_document" class="document-label">
                                <i class="fas fa-id-card"></i>
                                Pièce d'identité *
                            </label>
                            <input type="file" id="identity_document" name="identity_document" class="form-input"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                            <div class="document-hint">Carte d'identité, passeport ou permis de conduire</div>
                            <?php if (isset($errors['identity_document'])): ?>
                                <div class="form-error"><?= displayError($errors, 'identity_document') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="document-item">
                            <label for="property_document" class="document-label">
                                <i class="fas fa-home"></i>
                                Acte de propriété *
                            </label>
                            <input type="file" id="property_document" name="property_document" class="form-input"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                            <div class="document-hint">Acte notarié, titre de propriété ou compromis de vente</div>
                            <?php if (isset($errors['property_document'])): ?>
                                <div class="form-error"><?= displayError($errors, 'property_document') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="document-item">
                            <label for="tax_document" class="document-label">
                                <i class="fas fa-receipt"></i>
                                Avis de taxe foncière *
                            </label>
                            <input type="file" id="tax_document" name="tax_document" class="form-input"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                            <div class="document-hint">Dernier avis de taxe foncière (année en cours)</div>
                            <?php if (isset($errors['tax_document'])): ?>
                                <div class="form-error"><?= displayError($errors, 'tax_document') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="document-item">
                            <label for="energy_certificate" class="document-label">
                                <i class="fas fa-leaf"></i>
                                DPE (optionnel)
                            </label>
                            <input type="file" id="energy_certificate" name="energy_certificate" class="form-input"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            <div class="document-hint">Diagnostic de Performance Énergétique</div>
                            <?php if (isset($errors['energy_certificate'])): ?>
                                <div class="form-error"><?= displayError($errors, 'energy_certificate') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="documents-note">
                        <i class="fas fa-shield-alt"></i>
                        <div>
                            <strong>Protection de vos données :</strong> Vos documents sont protégés et stockés de manière sécurisée.
                            Ils ne sont jamais partagés et ne sont accessibles qu'aux administrateurs autorisés pour la vérification de votre annonce.
                        </div>
                    </div>

                    <div class="privacy-info">
                        <h4 class="privacy-title">
                            <i class="fas fa-info-circle"></i>
                            Vos droits et notre engagement
                        </h4>
                        <div class="privacy-grid">
                            <div class="privacy-item">
                                <i class="fas fa-user-shield"></i>
                                <span>Accès restreint</span>
                            </div>
                            <div class="privacy-item">
                                <i class="fas fa-eye-slash"></i>
                                <span>Non-partage garanti</span>
                            </div>
                            <div class="privacy-item">
                                <i class="fas fa-trash-alt"></i>
                                <span>Droit à l'effacement</span>
                            </div>
                            <div class="privacy-item">
                                <i class="fas fa-download"></i>
                                <span>Droit à la portabilité</span>
                            </div>
                            <div class="privacy-item">
                                <i class="fas fa-clock"></i>
                                <span>Conservation limitée</span>
                            </div>
                            <div class="privacy-item">
                                <i class="fas fa-gavel"></i>
                                <span>Conformité RGPD</span>
                            </div>
                        </div>
                        <div class="privacy-note">
                            <p><strong>Durée de conservation :</strong> Vos documents sont conservés uniquement le temps nécessaire à la vérification de votre annonce et à la finalisation de la transaction. Ils sont automatiquement supprimés après 2 ans ou à votre demande.</p>
                            <p><strong>Finalité :</strong> Ces documents sont utilisés exclusivement pour vérifier votre identité et la propriété du bien immobilier dans le cadre de la plateforme de concours.</p>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date" class="form-label">Date de début du concours *</label>
                        <input type="date" id="start_date" name="start_date" class="form-input"
                            value="<?= htmlspecialchars($old['start_date'] ?? date('Y-m-d')) ?>"
                            min="<?= date('Y-m-d') ?>" required>
                        <div class="form-hint">Date à laquelle le concours commencera</div>
                        <?php if (isset($errors['start_date'])): ?>
                            <div class="form-error"><?= displayError($errors, 'start_date') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="end_date" class="form-label">Date de fin du concours *</label>
                        <input type="date" id="end_date" name="end_date" class="form-input"
                            value="<?= htmlspecialchars($old['end_date'] ?? '') ?>"
                            min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                        <div class="form-hint">Le concours se terminera à cette date ou lorsque l'objectif de tickets sera atteint</div>
                        <?php if (isset($errors['end_date'])): ?>
                            <div class="form-error"><?= displayError($errors, 'end_date') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    </div>

    <!-- Actions -->
    <div class="form-actions">
        <a href="/dashboard" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Annuler
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i>
            Créer l'annonce
        </button>
    </div>
    </form>
</div>
</div>

<!-- ⚠️ Sécurité PROD : JavaScript inline supprimé — utiliser fichier .js externe -->
<!-- Les scripts de validation sont maintenant gérés par le fichier listing-create.js -->