#!/bin/bash

# ========================================
# SCRIPT D'ENTRÉE DOCKER - HOW I WIN MY HOME
# ========================================
#
# Ce script est exécuté au démarrage du conteneur Docker
# pour initialiser l'environnement de l'application.
#
# FONCTIONNALITÉS PRINCIPALES :
# - Création des répertoires système nécessaires
# - Configuration des permissions des fichiers
# - Création automatique du fichier .htaccess
# - Vérification de la configuration PHP
# - Initialisation de l'environnement Apache
#
# PROCESSUS D'EXÉCUTION :
# 1. Création des répertoires uploads, logs et temp
# 2. Configuration des permissions et propriétaires
# 3. Création du fichier .htaccess si nécessaire
# 4. Vérification des extensions PHP
# 5. Démarrage d'Apache avec la commande passée
#
# SÉCURITÉ IMPLÉMENTÉE :
# - Permissions restrictives sur les fichiers sensibles
# - Propriétaire www-data pour tous les fichiers
# - Permissions complètes uniquement sur les répertoires nécessaires
# - Vérification de la configuration avant démarrage
#
# AUTEUR : How I Win My Home Team
# VERSION : 2.0.0
# DATE : 2025-08-12
# ========================================

# Script d'entrée Docker pour How I Win My Home
set -e

# ========================================
# MESSAGE DE DÉMARRAGE
# ========================================
#
# Affichage d'un message informatif
# pour indiquer le début de l'initialisation
#

echo "🚀 Démarrage de How I Win My Home..."

# ========================================
# CRÉATION DES RÉPERTOIRES SYSTÈME
# ========================================
#
# Création des répertoires nécessaires
# au bon fonctionnement de l'application
#

echo "📁 Création des dossiers nécessaires..."

# Répertoire pour les fichiers uploadés par les utilisateurs
mkdir -p /var/www/html/uploads

# Répertoire pour les logs de l'application
mkdir -p /var/www/html/logs

# Répertoire temporaire pour les fichiers de cache
mkdir -p /var/www/html/temp

# ========================================
# CONFIGURATION DES PERMISSIONS
# ========================================
#
# Attribution des permissions appropriées
# pour la sécurité et le bon fonctionnement
#

echo "🔐 Vérification des permissions..."

# Attribution du propriétaire www-data à tous les fichiers
chown -R www-data:www-data /var/www/html

# Permissions standard (755) pour l'application
chmod -R 755 /var/www/html

# Permissions complètes (777) pour les répertoires d'écriture
chmod -R 777 /var/www/html/uploads
chmod -R 777 /var/www/html/logs
chmod -R 777 /var/www/html/temp

# ========================================
# CRÉATION AUTOMATIQUE DU FICHIER .HTACCESS
# ========================================
#
# Création du fichier .htaccess avec la configuration
# Apache si il n'existe pas déjà
#

if [ ! -f "/var/www/html/public/.htaccess" ]; then
    echo "📝 Création du fichier .htaccess..."
    
    # Création du fichier .htaccess avec la configuration complète
    cat > /var/www/html/public/.htaccess << 'EOF'
# ========================================
# CONFIGURATION APACHE - .HTACCESS
# HOW I WIN MY HOME
# ========================================
#
# Configuration Apache pour la réécriture d'URL
# et la sécurité de l'application
#

# ========================================
# RÉÉCRITURE D'URL - ROUTAGE MVC
# ========================================
#
# Redirige toutes les requêtes vers index.php
# pour permettre le routage côté application
#

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# ========================================
# SÉCURITÉ DES FICHIERS
# ========================================
#
# Configuration des permissions d'accès
# aux différents types de fichiers
#

# Autorise l'accès aux fichiers PHP
<Files "*.php">
    Order Allow,Deny
    Allow from all
</Files>

# Interdit l'accès aux fichiers SQL
<Files "*.sql">
    Order Deny,Allow
    Deny from all
</Files>

# ========================================
# CACHE ET EXPIRATION DES RESSOURCES
# ========================================
#
# Configuration du cache pour améliorer
# les performances de l'application
#

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
</IfModule>

# ========================================
# COMPRESSION DES RÉPONSES
# ========================================
#
# Configuration de la compression gzip
# pour réduire la taille des réponses HTTP
#

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>
EOF
fi

# ========================================
# VÉRIFICATION DE LA CONFIGURATION PHP
# ========================================
#
# Vérification que toutes les extensions PHP
# nécessaires sont bien installées et activées
#

echo "⚙️ Vérification de la configuration PHP..."

# Vérification des extensions PHP critiques
php -m | grep -E "(pdo|mysql|gd|zip)" || echo "⚠️ Certaines extensions PHP peuvent être manquantes"

# ========================================
# FINALISATION ET DÉMARRAGE
# ========================================
#
# Message de confirmation et démarrage
# de la commande passée en paramètre
#

echo "🎉 Configuration terminée! Démarrage d'Apache..."

# ========================================
# EXÉCUTION DE LA COMMANDE PRINCIPALE
# ========================================
#
# Exécution de la commande passée en paramètre
# (généralement apache2-foreground)
#

exec "$@" 