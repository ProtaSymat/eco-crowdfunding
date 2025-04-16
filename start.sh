#!/bin/bash

# Attendre que les variables d'environnement soient chargées
sleep 2

# Configurer Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exécuter les migrations si nécessaire
php artisan migrate --force

# Démarrer Apache
apache2-foreground