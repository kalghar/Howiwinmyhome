<?php
/**
 * COMPOSANT ALERTE RÉUTILISABLE
 * ==============================
 * 
 * Ce composant génère une alerte HTML avec différents types
 * et options de personnalisation.
 * 
 * Variables disponibles :
 * - $type : type d'alerte (success, danger, warning, info, primary, secondary, light, dark)
 * - $message : message à afficher
 * - $title : titre de l'alerte (optionnel)
 * - $dismissible : si l'alerte peut être fermée
 * - $class : classes CSS additionnelles
 * - $id : identifiant unique
 * - $icon : icône FontAwesome (optionnel)
 */

// Valeurs par défaut si non définies
$type = $type ?? 'info';
$message = $message ?? '';
$title = $title ?? '';
$dismissible = $dismissible ?? false;
$class = $class ?? '';
$id = $id ?? '';
$icon = $icon ?? '';

// Construire les classes CSS
$cssClasses = ['alert'];
$cssClasses[] = "alert-{$type}";
if ($dismissible) $cssClasses[] = 'alert-dismissible fade show';
if ($class) $cssClasses[] = $class;

// Construire les attributs
$attributes = [];
if ($id) $attributes[] = "id=\"{$id}\"";
$attributesString = implode(' ', $attributes);
$classesString = implode(' ', $cssClasses);

// Icônes par défaut selon le type
$defaultIcons = [
    'success' => 'check-circle',
    'danger' => 'exclamation-triangle',
    'warning' => 'exclamation-circle',
    'info' => 'info-circle',
    'primary' => 'info-circle',
    'secondary' => 'info-circle',
    'light' => 'info-circle',
    'dark' => 'info-circle'
];

$iconToUse = $icon ?: ($defaultIcons[$type] ?? 'info-circle');
?>

<div class="<?= htmlspecialchars($classesString, ENT_QUOTES, 'UTF-8') ?>" <?= $attributesString ?> role="alert">
    <?php if ($iconToUse): ?>
        <i class="fas fa-<?= htmlspecialchars($iconToUse, ENT_QUOTES, 'UTF-8') ?> me-2"></i>
    <?php endif; ?>
    
    <?php if ($title): ?>
        <h6 class="alert-heading"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h6>
    <?php endif; ?>
    
    <div class="alert-content">
        <?= $message ?>
    </div>
    
    <?php if ($dismissible): ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer">
            <span aria-hidden="true">&times;</span>
        </button>
    <?php endif; ?>
</div>
