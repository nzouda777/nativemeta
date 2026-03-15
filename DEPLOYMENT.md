# Guide de Déploiement en Production - NativeMeta

Ce document détaille les étapes nécessaires pour déployer le projet **NativeMeta** sur un serveur de production.

---

## 🏗️ Stack Technique
- **Backend** : Laravel 11 (PHP 8.3+)
- **Frontend** : React (via Inertia.js)
- **Styling** : Tailwind CSS
- **Base de données** : MariaDB / MySQL 8.0+
- **Cache & Queue** : Redis
- **Paiements** : Stripe API
- **Serveur Web** : Nginx / Apache
- **Automatisation** : Docker (optionnel mais inclus)

---

## 📋 Pré-requis
- Un serveur (VPS) sous Ubuntu 22.04+ ou Debian.
- PHP 8.3 avec les extensions : `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `intl`, `mbstring`, `openssl`, `pdo_mysql`, `redis`, `xml`, `zip`.
- Composer 2.x
- Node.js 20+ & NPM
- Un compte Stripe (Clés Test et Live).

---

## 🚀 Étapes de Déploiement

### 1. Cloner le projet
```bash
git clone https://github.com/votre-compte/nativemeta.git /var/www/nativemeta
cd /var/www/nativemeta
```

### 2. Configuration de l'environnement
Copiez le fichier d'exemple et configurez les variables critiques :
```bash
cp .env.example .env
nano .env
```

**Variables à modifier impérativement :**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://votre-domaine.com` (HTTPS est requis pour Stripe)
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`
- `MAIL_HOST`, `MAIL_PORT`, etc. (pour les emails transactionnels)

### 3. Installation des dépendances Backend
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

### 4. Installation & Build Frontend
```bash
npm install
npm run build
```

### 5. Base de données & Stockage
```bash
php artisan migrate --force
php artisan storage:link
```

### 6. Optimisation des performances
Ces commandes sont cruciales pour un environnement de production rapide :
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 💳 Configuration Stripe
Pour recevoir les notifications de paiement (ex: activation d'accès automatique), vous devez configurer le webhook :
1. Allez dans le [Dashboard Stripe](https://dashboard.stripe.com/webhooks).
2. Ajoutez un endpoint pointant vers `https://votre-domaine.com/stripe/webhook`.
3. Écoutez les événements : `checkout.session.completed`.
4. Récupérez le `Webhook Secret` et placez-le dans votre `.env` (`STRIPE_WEBHOOK_SECRET`).

---

## 🔒 Sécurité & Permissions
Assurez-vous que les dossiers Laravel ont les bons droits :
```bash
sudo chown -R www-data:www-data /var/www/nativemeta/storage
sudo chown -R www-data:www-data /var/www/nativemeta/bootstrap/cache
sudo chmod -R 775 /var/www/nativemeta/storage
sudo chmod -R 775 /var/www/nativemeta/bootstrap/cache
```

---

## ⚙️ Services Background (Cron & Worker)
Laravel nécessite deux processus tournant en permanence :

### Cron Job (Planificateur)
Ajoutez ceci à votre crontab (`crontab -e`) :
```bash
* * * * * cd /var/www/nativemeta && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (Tâches asynchrones)
Utilisez **Supervisor** pour gérer le worker :
```ini
[program:nativemeta-worker]
command=php /var/www/nativemeta/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
user=www-data
autostart=true
autorestart=true
```

---

## 🐳 Déploiement via Docker (Alternative)
Si vous utilisez Docker Compose en production :
1. Modifiez les mots de passe dans `docker-compose.yml`.
2. Lancez le déploiement :
```bash
docker compose up -d --build
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker compose exec app npm run build
```

---

## 📝 Check-list de mise en ligne
- [ ] Le SSL (HTTPS) est activé.
- [ ] Le `APP_DEBUG` est bien sur `false`.
- [ ] Les tâches planifiées (Cron) sont configurées.
- [ ] Stripe est configuré avec les clés **Live**.
- [ ] Les redirections HTTP vers HTTPS sont actives dans Nginx.
