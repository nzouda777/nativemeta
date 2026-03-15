# 🚀 NativeMeta Platform

NativeMeta est une plateforme LMS (Learning Management System) d'élite conçue avec **Laravel 11**, **FilamentPHP v3**, **Inertia.js** et **React**.

## ✨ Caractéristiques

- 🛡️ **Sécurité Maximale** : Middlewares CSP, XSS, et protection avancée des accès.
- 💰 **Stripe Integration** : Checkout sécurisé, webhooks automatiques et gestion des remboursements.
- 🎟️ **Invitation System** : Inscription réservée aux clients après achat (anti-spam & premium).
- ⚡ **Frontend Immersif** : Design Dark Mode, GSAP animations, smooth scroll (Lenis) et curseur magnétique.
- 📊 **Admin Dashboard** : Gestion complète des formations, ventes, revenus et élèves via FilamentPHP.
- 🎓 **Course Player** : Interface de lecture optimisée avec support vidéo, documents et suivi de progression.

## 🛠️ Stack Technique

- **Backend** : PHP 8.3, Laravel 11, MySQL, Redis.
- **Admin** : FilamentPHP v3.
- **Frontend** : React 18, Vite, TailwindCSS, Inertia.js.
- **Animations** : GSAP 3, Framer Motion, Lenis Scroll.

## 🚀 Installation Rapide

1. **Cloner le repository**
   ```bash
   git clone <repository-url>
   cd nativemeta
   ```

2. **Configuration Docker**
   ```bash
   cp .env.example .env
   # Configure tes clés Stripe dans le .env
   docker-compose up -d
   ```

3. **Installer les dépendances**
   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   npm install
   npm run dev
   ```

4. **Créer un Admin**
   ```bash
   docker-compose exec app php artisan nativemeta:admin "Ton Nom" admin@nativemeta.com password
   ```

## 🔐 Maintenance
Nettoyer les tokens expirés manuellement ou via cron :
```bash
php artisan nativemeta:tokens-cleanup
```

---
NativeMeta - Designed for High-Performance Digital Businesses.
