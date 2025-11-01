<?php
/**
 * COMPOSANT BOUTON RÉUTILISABLE
 * =============================
 * 
 * Ce composant génère un bouton HTML avec des options
 * de personnalisation et une structure cohérente.
 * 
 * Variables disponibles :
 * - $type : type du bouton (button, submit, reset)
 * - $class : classes CSS additionnelles
 * - $text : texte du bouton
 * - $id : identifiant unique
 * - $disabled : état désactivé
 * - $icon : icône FontAwesome (optionnel)
 * - $size : taille du bouton (small, medium, large)
 * - $variant : variante de style (primary, secondary, success, danger, warning, info)
 */

// Valeurs par défaut si non définies
$type = $type ?? 'button';
$class = $class ?? 'btn';
$text = $text ?? 'Bouton';
$id = $id ?? '';
$disabled = $disabled ?? false;
$icon = $icon ?? '';
$size = $size ?? 'medium';
$variant = $variant ?? 'primary';

// Construire les classes CSS
$cssClasses = ['btn'];
$cssClasses[] = "btn-{$variant}";
$cssClasses[] = "btn-{$size}";
if ($class && $class !== 'btn') {
    $cssClasses[] = $class;
}

// Construire les attributs
$attributes = [];
if ($id) $attributes[] = "id=\"{$id}\"";
if ($disabled) $attributes[] = 'disabled';
if ($type !== 'button') $attributes[] = "type=\"{$type}\"";

$attributesString = implode(' ', $attributes);
$classesString = implode(' ', $cssClasses);
?>

<button class="<?= $classesString ?>" <?= $attributesString ?>>
    <?php if ($icon): ?>
        <i class="fas fa-<?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?>"></i>
        <span><?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?></span>
    <?php else: ?>
        <?= htmlspecialchars($text, ENT_QUOTES, 'UTF-8') ?>
    <?php endif; ?>
</button>
