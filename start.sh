#!/bin/bash

# Attendre que les variables d'environnement soient chargées
sleep 2

# Configurer Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Créer le lien symbolique pour le stockage public
php artisan storage:link

# Exécuter les migrations si nécessaire
php artisan migrate --force

# Démarrer Apache
apache2-foreground