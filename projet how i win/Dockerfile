# ========================================
# DOCKERFILE - APPLICATION WEB HOW I WIN MY HOME
# ========================================
#
# Ce Dockerfile définit l'image Docker pour l'application
# web How I Win My Home basée sur PHP 8.2 et Apache.
#
# FONCTIONNALITÉS PRINCIPALES :
# - Image de base PHP 8.2 avec serveur Apache
# - Configuration PHP optimisée pour l'application
# - Extensions PHP nécessaires (PDO, MySQL, GD, ZIP)
# - Configuration Apache personnalisée
# - Gestion des permissions et des répertoires
# - Script de démarrage personnalisé
#
# ARCHITECTURE DE L'IMAGE :
# 1. Image de base PHP 8.2-Apache
# 2. Installation des dépendances système
# 3. Configuration des extensions PHP
# 4. Configuration Apache et modules
# 5. Configuration PHP personnalisée
# 6. Création des répertoires de travail
# 7. Configuration des permissions
# 8. Script de démarrage et exposition des ports
#
# VARIABLES D'ENVIRONNEMENT :
# - APACHE_DOCUMENT_ROOT : Répertoire racine d'Apache
# - PHP_MEMORY_LIMIT : Limite mémoire PHP (256M)
# - PHP_MAX_EXECUTION_TIME : Temps d'exécution max (300s)
# - PHP_UPLOAD_MAX_FILESIZE : Taille max des uploads (10M)
# - PHP_POST_MAX_SIZE : Taille max des données POST (10M)
#
# AUTEUR : How I Win My Home Team
# VERSION : 2.0.0
# DATE : 2025-08-12
# ========================================

# ========================================
# IMAGE DE BASE
# ========================================
#
# Utilisation de l'image officielle PHP 8.2
# avec serveur Apache intégré
#

FROM php:8.2-apache

# ========================================
# VARIABLES D'ENVIRONNEMENT
# ========================================
#
# Définition des variables d'environnement
# pour la configuration de l'application
#

# Répertoire racine d'Apache pointant vers le dossier public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Configuration PHP pour les performances et la stabilité
ENV PHP_MEMORY_LIMIT=512M
ENV PHP_MAX_EXECUTION_TIME=300
ENV PHP_UPLOAD_MAX_FILESIZE=200M
ENV PHP_POST_MAX_SIZE=200M

# ========================================
# INSTALLATION DES DÉPENDANCES SYSTÈME
# ========================================
#
# Installation des packages système nécessaires
# pour le bon fonctionnement de l'application
#

# Installation des bibliothèques de développement
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# ========================================
# CONFIGURATION ET INSTALLATION DES EXTENSIONS PHP
# ========================================
#
# Installation et configuration des extensions PHP
# nécessaires au fonctionnement de l'application
#

# Configuration de l'extension GD avec support FreeType et JPEG
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        gd \
        zip \
        mysqli

# ========================================
# ACTIVATION DES MODULES APACHE
# ========================================
#
# Activation des modules Apache nécessaires
# pour le bon fonctionnement de l'application
#

# Module rewrite pour les URLs propres, headers pour la sécurité
RUN a2enmod rewrite headers

# ========================================
# CONFIGURATION PHP PERSONNALISÉE
# ========================================
#
# Création d'un fichier de configuration PHP
# personnalisé avec les paramètres de l'application
#

RUN echo "memory_limit = ${PHP_MEMORY_LIMIT}" > /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "max_execution_time = ${PHP_MAX_EXECUTION_TIME}" >> /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "upload_max_filesize = ${PHP_UPLOAD_MAX_FILESIZE}" >> /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "post_max_size = ${PHP_POST_MAX_SIZE}" >> /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "date.timezone = Europe/Paris" >> /usr/local/etc/php/conf.d/memory-limit.ini

# ========================================
# RÉPERTOIRE DE TRAVAIL
# ========================================
#
# Définition du répertoire de travail
# pour les commandes suivantes
#

WORKDIR /var/www/html

# ========================================
# COPIE DES FICHIERS DU PROJET
# ========================================
#
# Copie de tous les fichiers du projet
# dans le conteneur Docker
#

COPY . /var/www/html/

# ========================================
# CRÉATION DES RÉPERTOIRES NÉCESSAIRES
# ========================================
#
# Création des répertoires système
# nécessaires au fonctionnement de l'application
#

# Répertoire des fichiers uploadés
RUN mkdir -p /var/www/html/uploads \
    && mkdir -p /var/www/html/logs \
    && mkdir -p /var/www/html/temp

# ========================================
# CONFIGURATION APACHE PERSONNALISÉE
# ========================================
#
# Création d'une configuration VirtualHost
# personnalisée pour l'application
#

# Configuration du nom du serveur et du VirtualHost
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf \
    && echo '    ServerAdmin webmaster@localhost' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf \
    && echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-default.conf \
    && echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-default.conf \
    && echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# ========================================
# ATTRIBUTION DES PERMISSIONS
# ========================================
#
# Configuration des permissions des fichiers
# et répertoires pour la sécurité et le bon fonctionnement
#

# Propriétaire www-data pour tous les fichiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/uploads \
    && chmod -R 777 /var/www/html/logs \
    && chmod -R 777 /var/www/html/temp

# ========================================
# EXPOSITION DU PORT
# ========================================
#
# Exposition du port 80 pour permettre
# l'accès HTTP à l'application
#

EXPOSE 80

# ========================================
# SCRIPT DE DÉMARRAGE
# ========================================
#
# Copie et configuration du script
# de démarrage personnalisé
#

# Copie du script d'entrée dans le conteneur
COPY docker-entrypoint.sh /usr/local/bin/

# Attribution des permissions d'exécution au script
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# ========================================
# POINT D'ENTRÉE ET COMMANDE PAR DÉFAUT
# ========================================
#
# Configuration du point d'entrée et
# de la commande de démarrage par défaut
#

# Point d'entrée personnalisé pour l'initialisation
ENTRYPOINT ["docker-entrypoint.sh"]

# Commande par défaut pour démarrer Apache
CMD ["apache2-foreground"]