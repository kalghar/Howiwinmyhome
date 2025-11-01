<?php
/**
 * COMPOSANT CARTE RÉUTILISABLE
 * =============================
 * 
 * Ce composant génère une carte HTML avec header, contenu
 * et footer optionnels.
 * 
 * Variables disponibles :
 * - $title : titre de la carte
 * - $content : contenu principal
 * - $footer : contenu du footer
 * - $class : classes CSS additionnelles
 * - $id : identifiant unique
 * - $image : URL de l'image (optionnel)
 * - $imageAlt : texte alternatif de l'image
 * - $headerClass : classes CSS pour le header
 * - $bodyClass : classes CSS pour le corps
 * - $footerClass : classes CSS pour le footer
 */

// Valeurs par défaut si non définies
$title = $title ?? '';
$content = $content ?? '';
$footer = $footer ?? '';
$class = $class ?? 'card';
$id = $id ?? '';
$image = $image ?? '';
$imageAlt = $imageAlt ?? '';
$headerClass = $headerClass ?? 'card-header';
$bodyClass = $bodyClass ?? 'card-body';
$footerClass = $footerClass ?? 'card-footer';

// Construire les attributs
$attributes = [];
if ($id) $attributes[] = "id=\"{$id}\"";
$attributesString = implode(' ', $attributes);
?>

<div class="<?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>" <?= $attributesString ?>>
    <?php if ($image): ?>
        <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" 
             alt="<?= htmlspecialchars($imageAlt, ENT_QUOTES, 'UTF-8') ?>" 
             class="card-img-top">
    <?php endif; ?>
    
    <?php if ($title): ?>
        <div class="<?= htmlspecialchars($headerClass, ENT_QUOTES, 'UTF-8') ?>">
            <h5 class="card-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h5>
        </div>
    <?php endif; ?>
    
    <?php if ($content): ?>
        <div class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
            <?= $content ?>
        </div>
    <?php endif; ?>
    
    <?php if ($footer): ?>
        <div class="<?= htmlspecialchars($footerClass, ENT_QUOTES, 'UTF-8') ?>">
            <?= $footer ?>
        </div>
    <?php endif; ?>
</div>
