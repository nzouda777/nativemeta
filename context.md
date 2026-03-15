Voici un prompt ultra-détaillé et structuré pour Claude Opus :

---

# PROMPT MAÎTRE — Plateforme NativeMeta (Produits Digitaux)

## 🎯 CONTEXTE & VISION

Tu es un expert full-stack senior. Tu vas construire de A à Z une plateforme complète de vente de formations digitales appelée **NativeMeta**, spécialisée dans l'e-commerce. La plateforme vend des formations au format vidéo, audio et PDF. Le fondateur est un expert e-commerce reconnu, et la plateforme doit refléter son autorité, son impact et son excellence.

---

## 🏗️ STACK TECHNIQUE OBLIGATOIRE

```
Backend      : Laravel 11 (dernière version)
Frontend     : React 18 + Inertia.js (SSR activé)
Admin Panel  : FilamentPHP 3.x
Base de données : MySQL 8
Cache/Queue  : Redis
Containerisation : Docker + Docker Compose
Paiements    : Stripe (Checkout + Webhooks + Customer Portal)
Mails        : Laravel Mail + Mailpit (dev) / SMTP configurable (prod)
Storage      : Laravel Storage (local dev / S3-compatible en prod)
Auth         : Laravel Breeze + Sanctum
Logs         : Laravel Activity Log (spatie/laravel-activitylog)
Permissions  : Spatie Laravel Permission (RBAC)
Soft Delete  : Activé sur tous les modèles sensibles
Sécurité     : Rate Limiting, CSRF, XSS Protection, Headers sécurisés
```

---

## 📁 ARCHITECTURE DU PROJET

```
nativemeta/
├── docker/
│   ├── nginx/
│   ├── php/
│   └── mysql/
├── docker-compose.yml
├── docker-compose.prod.yml
├── .env.example
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── Client/
│   │   │   ├── Webhook/
│   │   │   └── Public/
│   │   ├── Middleware/
│   │   │   ├── SecurityHeaders.php
│   │   │   ├── XssProtection.php
│   │   │   └── CheckCourseAccess.php
│   │   └── Requests/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Course.php
│   │   ├── Module.php
│   │   ├── Lesson.php
│   │   ├── Order.php
│   │   ├── Enrollment.php
│   │   ├── InvitationToken.php
│   │   └── ActivityLog.php
│   ├── Services/
│   │   ├── StripeService.php
│   │   ├── EnrollmentService.php
│   │   ├── InvitationService.php
│   │   └── MediaService.php
│   ├── Filament/
│   │   ├── Resources/
│   │   └── Widgets/
│   └── Policies/
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Public/    ← Site vitrine
│   │   │   ├── Auth/      ← Login, Register via token
│   │   │   └── Client/    ← Dashboard client
│   │   ├── Components/
│   │   │   ├── UI/
│   │   │   ├── Animations/
│   │   │   └── Player/    ← Video/Audio player
│   │   └── Layouts/
└── tests/
```

---

## 🌐 ÉTAPE 1 — SITE VITRINE (Public, React + Inertia)

### Design & Identité Visuelle

Le site doit avoir un design **premium, immersif et moderne**. Palette sombre avec accents dorés/orangés symbolisant la puissance et le succès dans l'e-commerce.

**Palette de couleurs :**
```css
--primary: #F59E0B (Ambre doré)
--secondary: #0F172A (Bleu nuit profond)
--accent: #6366F1 (Indigo électrique)
--dark: #020817
--light: #F8FAFC
--gradient-hero: linear-gradient(135deg, #020817 0%, #0F172A 50%, #1E1B4B 100%)
```

**Typographie :**
- Headings : `Clash Display` ou `Plus Jakarta Sans Bold`
- Body : `Inter`
- Accent : `Syne` pour les labels

### Pages du Site Vitrine

#### 1. Page d'Accueil (`/`)

**Section Hero :**
- Background : Particules animées (tsParticles ou custom canvas) + gradient sombre
- Headline animé avec effet typewriter : *"Transforme ton Business en Machine à Cash avec NativeMeta"*
- Sous-titre avec animation fade-up
- Deux CTA : `Voir les Formations` (primary) + `Qui suis-je ?` (ghost)
- Floating mockup animé de la plateforme (parallax au scroll)
- Compteurs animés au scroll : `+500 élèves formés`, `+2M€ générés par nos clients`, `4.9/5 satisfaction`

**Section "Mon Impact" :**
- Timeline horizontale scrollable des succès et milestones
- Cards avec chiffres clés animés au scroll (CountUp.js)
- Logos de marques/partenaires en carrousel infini
- Vidéo background avec overlay + play button

**Section "Ce Que Je Fais" :**
- Grid de 3 piliers avec icônes animées (Lottie)
- Chaque pilier : E-commerce, Marketing Digital, Automatisation
- Hover effect : card qui se retourne (flip 3D) pour révéler le détail

**Section "Les Formations NativeMeta" :**
- Grid responsive de cards formation
- Chaque card : thumbnail, titre, description courte, prix, badge (Bestseller/Nouveau), nombre d'élèves, bouton d'achat
- Filter par catégorie avec animation smooth
- Hover : légère élévation + glow effect couleur primaire

**Section "Témoignages" :**
- Carousel 3D (Swiper.js) avec photos, noms, résultats obtenus
- Chaque témoignage : avant/après en chiffres

**Section "Qui Suis-Je ?" :**
- Layout asymétrique : photo gauche (avec border animé gradient), texte droite
- Timeline de parcours e-commerce
- Badges de credibilité animés

**Section FAQ :**
- Accordion custom avec animation smooth
- Questions pré-remplies (modifiables depuis le backoffice)

**Footer :**
- Links légaux, réseaux sociaux, newsletter inline
- Mini-carte des formations

#### 2. Page Formation Détail (`/formations/{slug}`)
- Hero avec thumbnail vidéo (preview trailer)
- Programme complet (accordéon par module)
- Ce que tu vas apprendre (checklist animée)
- Profil formateur
- Témoignages spécifiques à la formation
- Sticky sidebar avec prix + CTA achat (Stripe Checkout)
- Section garantie (badge animé)

#### 3. Page "À Propos" (`/a-propos`)
- Storytelling immersif avec parallax
- Timeline de carrière

#### 4. Page Légale (`/mentions-legales`, `/cgv`, `/politique-confidentialite`)

### Animations & Effets Requis

```javascript
// Librairies à utiliser
- GSAP (ScrollTrigger) → animations au scroll
- Framer Motion → transitions de pages et micro-interactions
- tsParticles → background hero
- Swiper.js → carousels
- CountUp.js → compteurs animés
- Lottie-react → icônes animées

// Effets spécifiques à implémenter
1. Page transition : fondu + slide entre les pages (Inertia progress bar custom)
2. Cursor personnalisé (cercle qui suit la souris, change au hover)
3. Parallax multi-couches sur le hero
4. Glassmorphism sur les cards (backdrop-filter)
5. Gradient border animé sur les éléments mis en avant
6. Text scramble effect sur les headlines
7. Magnetic buttons (effet d'attraction au hover)
8. Smooth scroll avec Lenis
```

---

## 🔧 ÉTAPE 2 — BACKOFFICE & PLATEFORME

### 2.1 Système d'Authentification & Invitations

#### Flux d'achat et d'accès :

```
1. Client achète une formation → Stripe Checkout
2. Stripe déclenche webhook → Laravel reçoit payment_intent.succeeded
3. Laravel crée un Order + Enrollment
4. Si le client n'a PAS de compte :
   → Génère un InvitationToken (UUID, expires_at = 7 jours)
   → Envoie un mail avec le lien : /register?token={uuid}
   → Le client clique → formulaire de création de compte
   → Après création : accès direct aux formations achetées
5. Si le client A déjà un compte :
   → Mail de confirmation d'achat
   → Accès immédiat depuis son dashboard
```

**Modèle InvitationToken :**
```php
Schema::create('invitation_tokens', function (Blueprint $table) {
    $table->id();
    $table->string('token', 64)->unique();
    $table->string('email');
    $table->foreignId('order_id')->constrained();
    $table->timestamp('expires_at');
    $table->timestamp('used_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### 2.2 Modèles de Données Complets

```php
// USERS
users: id, name, email, password, avatar, role, stripe_customer_id, 
       email_verified_at, last_login_at, is_active, timestamps, soft_deletes

// COURSES (Formations)
courses: id, title, slug, description, long_description, thumbnail, 
         trailer_url, price, sale_price, currency, status(draft/published), 
         is_featured, meta_title, meta_description, 
         category_id, created_by, timestamps, soft_deletes

// MODULES
modules: id, course_id, title, description, order, timestamps, soft_deletes

// LESSONS (Leçons)
lessons: id, module_id, title, description, type(video/audio/pdf/text), 
         content_url, content_text, duration_seconds, order, 
         is_preview, timestamps, soft_deletes

// ORDERS (Commandes)
orders: id, user_id, stripe_session_id, stripe_payment_intent_id, 
        amount, currency, status(pending/paid/refunded/failed), 
        metadata(json), timestamps, soft_deletes

// ORDER_ITEMS
order_items: id, order_id, course_id, amount, timestamps

// ENROLLMENTS (Accès formation)
enrollments: id, user_id, course_id, order_id, enrolled_at, 
             expires_at(nullable), timestamps, soft_deletes

// PROGRESS
lesson_progress: id, user_id, lesson_id, is_completed, 
                 watched_seconds, completed_at, timestamps

// SETTINGS
settings: id, key, value(json), group, timestamps

// CATEGORIES
categories: id, name, slug, description, icon, timestamps, soft_deletes
```

### 2.3 RBAC — Système de Permissions (Spatie)

```php
// RÔLES
- super_admin  : accès total, ne peut pas être supprimé
- admin        : gestion contenu et clients, pas les paramètres critiques  
- moderator    : lecture seule + support client
- student      : accès uniquement à ses formations achetées

// PERMISSIONS GRANULAIRES
Courses  : view_courses, create_courses, edit_courses, delete_courses, publish_courses
Modules  : view_modules, create_modules, edit_modules, delete_modules  
Lessons  : view_lessons, create_lessons, edit_lessons, delete_lessons
Orders   : view_orders, refund_orders, export_orders
Users    : view_users, create_users, edit_users, delete_users, manage_roles
Settings : view_settings, edit_settings
Analytics: view_analytics
```

### 2.4 Panel Super Admin (FilamentPHP 3)

#### Dashboard Principal

**Widgets KPI (row 1) :**
```
┌─────────────────┬─────────────────┬─────────────────┬─────────────────┐
│  Revenus Totaux  │  Clients Actifs  │  Formations      │  Tx Conversion  │
│  47,250 €       │  523            │  12 publiées     │  3.8%           │
│  ↑ +12% ce mois │  ↑ +34 ce mois  │  3 en draft      │  ↑ +0.4%       │
└─────────────────┴─────────────────┴─────────────────┴─────────────────┘
```

**Widgets Graphiques (row 2) :**
- Graphique linéaire : Revenus sur 12 mois (Filament Charts)
- Graphique barres : Nouvelles inscriptions / mois
- Donut chart : Répartition des ventes par formation

**Widgets Liste (row 3) :**
- Dernières commandes (5 lignes, lien vers détail)
- Derniers clients inscrits
- Formations les plus vendues (top 5)

**Widgets Activité (row 4) :**
- Feed d'activité en temps réel (Activity Log)
- Alertes système (paiements échoués, tokens expirés)

#### Ressources FilamentPHP

**1. Resource : Courses (Formations)**
```
List   : Tableau avec thumbnail, titre, prix, statut, nb élèves, actions
Create : Formulaire multi-étapes :
  Étape 1 - Infos générales :
    - Titre, slug (auto-généré), catégorie
    - Description courte (textarea)
    - Description longue → Éditeur riche custom (type Notion) :
        * Blocs : Texte, H1/H2/H3, Liste, Citation, Code, Image, Vidéo embed, 
                  Divider, Callout (info/warning/success), Table
        * Drag & drop des blocs pour réorganiser
        * Commande slash "/" pour insérer un bloc
    - Thumbnail (upload avec preview)
    - Trailer URL (YouTube/Vimeo embed ou upload direct)
    
  Étape 2 - Prix & Publication :
    - Prix normal + Prix promo + Devise
    - Statut : Draft / Publié
    - Mise en avant (featured)
    - Meta SEO (titre, description)
    
  Étape 3 - Contenu (Modules & Leçons) :
    - Repeater pour les modules
    - Chaque module : Repeater pour les leçons
    - Chaque leçon : titre, type, upload/URL, durée, aperçu gratuit oui/non
    - Drag & drop pour réordonner modules et leçons

Edit   : Même formulaire pré-rempli
View   : Page détail lecture seule avec stats
```

**2. Resource : Users (Clients & Admins)**
```
List   : Nom, email, rôle, nb formations, dernière connexion, statut, actions
Create : Nom, email, mot de passe, rôle, avatar
Edit   : Modification profil + changement rôle + reset password
View   : Profil complet + historique commandes + formations accessibles
Actions:
  - Assigner formation manuellement
  - Révoquer accès
  - Envoyer mail invitation
  - Suspendre/Réactiver compte
  - Voir logs d'activité du client
```

**3. Resource : Orders (Commandes)**
```
List   : ID, client, montant, statut, formation(s), date, actions
View   : Détail complet commande + infos Stripe + articles
Actions:
  - Rembourser (appel API Stripe)
  - Renvoyer mail confirmation
  - Voir dans Stripe Dashboard (lien)
Filtres: Par statut, par formation, par période, par montant
Export : CSV / Excel des commandes filtrées
```

**4. Resource : Enrollments (Accès)**
```
List   : Client, formation, date inscription, expiration, statut
Create : Assigner manuellement une formation à un client
Edit   : Modifier date expiration
Actions: Révoquer accès, prolonger accès
```

**5. Resource : Categories**
```
CRUD complet avec icône, couleur, slug
```

**6. Resource : Settings (Paramètres Globaux)**

Organisé en onglets :
```
Onglet "Général" :
  - Nom du site, logo, favicon, tagline
  - Email de contact, email d'expédition
  - Réseaux sociaux (URLs)

Onglet "Paiements" :
  - Stripe Public Key, Secret Key, Webhook Secret
  - Devise par défaut
  - Toggle : Mode test Stripe

Onglet "Emails" :
  - Driver SMTP (host, port, user, pass)
  - Template email header/footer
  - Texte email invitation, email confirmation

Onglet "Apparence" :
  - Couleur primaire (color picker)
  - Couleur secondaire
  - Mode clair/sombre par défaut
  - Police (sélecteur)

Onglet "Sécurité" :
  - Durée session
  - Nombre max tentatives login
  - 2FA obligatoire (toggle)
  - IP whitelist admin

Onglet "Stockage" :
  - Driver (local/S3)
  - Config S3 (bucket, region, key, secret)

Onglet "Maintenance" :
  - Mode maintenance (toggle)
  - Message maintenance
  - Purger cache
  - Voir logs système
```

**7. Widget : Activity Log (Journal)**
```
Table avec : qui, action, sur quoi, données avant/après (JSON diff), IP, date
Filtres : par user, par type d'action, par date
Export CSV
```

**8. Resource : Roles & Permissions**
```
- Créer/modifier des rôles custom
- Assigner des permissions granulaires à chaque rôle
- Interface checkbox groupée par module
```

### 2.5 Dashboard Client (React + Inertia)

**Route protégée :** `/dashboard` (middleware auth + email verified)

**Layout Client :**
```
┌─────────────────────────────────────────────────────────────┐
│  NativeMeta Logo    [Mes Formations] [Profil] [Se déconnecter]│
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Bonjour Jean ! 👋  Tu as accès à 3 formations.            │
│                                                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌────────────┐ │
│  │ [Thumbnail]     │  │ [Thumbnail]     │  │[Thumbnail] │ │
│  │ Formation A     │  │ Formation B     │  │Formation C │ │
│  │ ████████░░ 80%  │  │ ░░░░░░░░░░  0% │  │████░░░ 40%│ │
│  │ [Continuer]     │  │ [Commencer]     │  │[Continuer] │ │
│  └─────────────────┘  └─────────────────┘  └────────────┘ │
│                                                             │
│  Dernière activité : Formation A - Module 2, Leçon 3       │
└─────────────────────────────────────────────────────────────┘
```

**Page Formation (lecteur) :**
```
┌──────────────────────────────┬──────────────────────────────┐
│                              │  MODULE 1 : Introduction     │
│   [LECTEUR VIDÉO/AUDIO/PDF]  │  ✅ Leçon 1 : Bienvenue      │
│                              │  ✅ Leçon 2 : Les bases       │
│   Titre de la leçon          │  ▶ Leçon 3 : Stratégie ←     │
│   Description                │                              │
│                              │  MODULE 2 : Avancé           │
│   [← Précédent] [Suivant →] │  ○ Leçon 4 : ...             │
│   [✅ Marquer comme terminé] │  ○ Leçon 5 : ...             │
└──────────────────────────────┴──────────────────────────────┘
```

**Lecteur vidéo custom :**
- HTML5 Video avec controls custom (play/pause, volume, vitesse x1/x1.25/x1.5/x2, plein écran)
- Reprise automatique depuis la dernière position
- Tracking de progression (sauvegarde toutes les 10 secondes)

**Lecteur audio custom :**
- Waveform visuel (WaveSurfer.js)
- Controls play/pause, seek, vitesse

**Lecteur PDF :**
- react-pdf avec navigation pages, zoom, téléchargement désactivable

---

## 🔒 SÉCURITÉ — Implémentation Complète

### 1. Rate Limiting

```php
// config/rate_limiting.php et RouteServiceProvider

// API publique
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->ip());
});

// Login (anti brute-force)
RateLimiter::for('login', function (Request $request) {
    return [
        Limit::perMinute(5)->by($request->ip()),
        Limit::perMinute(3)->by($request->input('email')),
    ];
});

// Inscription via token
RateLimiter::for('register', function (Request $request) {
    return Limit::perHour(10)->by($request->ip());
});

// Stripe Webhooks (pas de rate limit, sécurisé par signature)
// Mot de passe oublié
RateLimiter::for('forgot-password', function (Request $request) {
    return Limit::perHour(5)->by($request->ip());
});
```

### 2. Protection XSS

```php
// Middleware XssProtection.php
class XssProtection
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize tous les inputs string
        $input = $request->all();
        array_walk_recursive($input, function (&$value) {
            if (is_string($value)) {
                $value = strip_tags($value);
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        });
        $request->merge($input);
        
        return $next($request);
    }
}

// SecurityHeaders Middleware
class SecurityHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Content-Security-Policy', 
            "default-src 'self'; script-src 'self' 'unsafe-inline' https://js.stripe.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "font-src 'self' https://fonts.gstatic.com; " .
            "img-src 'self' data: https:; " .
            "connect-src 'self' https://api.stripe.com; " .
            "frame-src https://js.stripe.com;"
        );
        return $response;
    }
}
```

### 3. Soft Delete (sur tous les modèles critiques)

```php
// Appliquer sur : User, Course, Module, Lesson, Order, Enrollment, InvitationToken

use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;
    // Les enregistrements supprimés restent en BDD avec deleted_at renseigné
    // Récupérables depuis le panel admin
}
```

### 4. Activity Log (Journal Complet)

```php
// Via spatie/laravel-activitylog
// Logger automatiquement sur tous les modèles :
class Course extends Model
{
    use LogsActivity;
    
    protected static $logAttributes = ['title', 'price', 'status'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'course';
    
    public function getDescriptionForEvent(string $eventName): string
    {
        return "La formation {$this->title} a été {$eventName}";
    }
}

// Events manuels à logger :
// - Connexion réussie / échouée
// - Achat complété / remboursé
// - Accès révoqué
// - Changement de rôle
// - Modification paramètres
// - Export de données
activity()
    ->causedBy(auth()->user())
    ->performedOn($model)
    ->withProperties(['ip' => request()->ip(), 'extra' => $data])
    ->log('action_description');
```

### 5. Validation Webhook Stripe

```php
class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');
        
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            abort(400, 'Invalid webhook signature');
        }
        
        match($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object),
            'payment_intent.payment_failed' => $this->handlePaymentFailed($event->data->object),
            'charge.refunded' => $this->handleRefund($event->data->object),
            default => null
        };
        
        return response()->json(['received' => true]);
    }
}
```

---

## 🐳 DOCKER CONFIGURATION

```yaml
# docker-compose.yml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    volumes:
      - .:/var/www/html
      - ./storage:/var/www/html/storage
    environment:
      - PHP_OPCACHE_ENABLE=1
    depends_on:
      - mysql
      - redis

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/nginx.conf:/etc/nginx/conf.d/default.conf

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"

  redis:
    image: redis:alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data

  mailpit:
    image: axllent/mailpit
    ports:
      - "8025:8025"
      - "1025:1025"

  queue:
    build:
      context: .
      dockerfile: docker/php/Dockerfile
    command: php artisan queue:work --sleep=3 --tries=3 --max-time=3600
    depends_on:
      - redis
      - mysql

volumes:
  mysql_data:
  redis_data:
```

---

## 📧 EMAILS (Templates)

**1. Email Invitation (nouveau client) :**
```
Objet : 🎉 Accède à ta formation [NOM FORMATION] - NativeMeta

Contenu :
- Header avec logo NativeMeta
- "Félicitations ! Ton paiement a été confirmé."
- Bouton CTA : "Créer mon compte et accéder à ma formation"
  → Lien : /register?token={token} (valide 7 jours)
- Note : "Ce lien expire dans 7 jours. Si tu as déjà un compte, connecte-toi et retrouve ta formation dans ton dashboard."
- Footer légal
```

**2. Email Confirmation (client existant) :**
```
Objet : ✅ Accès confirmé : [NOM FORMATION]

Contenu :
- "Ton achat est confirmé !"
- Récapitulatif commande
- Bouton CTA : "Accéder à ma formation"
- Support email
```

**3. Email de Bienvenue (après création compte) :**
```
Objet : Bienvenue sur NativeMeta ! 🚀

Contenu :
- Personnalisé avec prénom
- Lien vers dashboard
- Petite présentation de comment naviguer
```

---

## 🗺️ ROUTES COMPLÈTES

```php
// PUBLIC
GET  /                          → Public\HomeController@index
GET  /formations                → Public\CourseController@index
GET  /formations/{slug}         → Public\CourseController@show
GET  /a-propos                  → Public\AboutController@index
GET  /mentions-legales          → Public\LegalController@mentions
GET  /cgv                       → Public\LegalController@cgv
POST /checkout/{course}         → Payment\CheckoutController@create
GET  /checkout/success          → Payment\CheckoutController@success
GET  /checkout/cancel           → Payment\CheckoutController@cancel
POST /stripe/webhook            → Webhook\StripeController@handle

// AUTH
GET  /login                     → Auth\LoginController@show
POST /login                     → Auth\LoginController@store
GET  /register                  → Auth\RegisterController@show (token requis)
POST /register                  → Auth\RegisterController@store
POST /logout                    → Auth\LoginController@destroy
GET  /forgot-password           → Auth\PasswordController@request
POST /forgot-password           → Auth\PasswordController@email
GET  /reset-password/{token}    → Auth\PasswordController@reset
POST /reset-password            → Auth\PasswordController@update

// CLIENT (auth required)
GET  /dashboard                 → Client\DashboardController@index
GET  /mes-formations            → Client\EnrollmentController@index
GET  /mes-formations/{slug}     → Client\EnrollmentController@show
GET  /lecon/{lesson}            → Client\LessonController@show
POST /lecon/{lesson}/progress   → Client\ProgressController@update
GET  /profil                    → Client\ProfileController@show
PUT  /profil                    → Client\ProfileController@update

// ADMIN FilamentPHP
/admin/**                       → Filament Panel (guards: admin, super_admin)
```

---

## ⚙️ COMMANDES ARTISAN CUSTOM

```bash
# Créer le super admin
php artisan nativemeta:create-super-admin

# Nettoyer tokens expirés
php artisan nativemeta:clean-expired-tokens

# Générer rapport revenus mensuel
php artisan nativemeta:monthly-report

# Synchroniser statuts commandes avec Stripe
php artisan nativemeta:sync-stripe-orders
```

---

## 🧪 TESTS À IMPLÉMENTER

```php
// Feature Tests
- AuthenticationTest : login, register via token, logout
- CheckoutTest : création session Stripe, webhook handling
- EnrollmentTest : accès formation après paiement
- InvitationTest : génération token, expiration, utilisation unique

// Unit Tests  
- StripeServiceTest
- EnrollmentServiceTest
- InvitationServiceTest
```

---

## 📋 ORDRE D'IMPLÉMENTATION

```
Phase 1 — Infrastructure
  1. Docker setup complet
  2. Laravel 11 install + config
  3. Migrations base de données
  4. Seeders (super admin, rôles, permissions, données de démo)

Phase 2 — Backend Core
  5. Models + Relations + Policies
  6. Stripe Service
  7. Enrollment Service
  8. Invitation Service
  9. Webhooks Stripe
  10. Mail templates

Phase 3 — Sécurité
  11. Middlewares (XSS, Security Headers, Rate Limiting)
  12. CSRF configuration
  13. Validation des inputs (Form Requests)

Phase 4 — Admin Panel
  14. FilamentPHP setup + guard admin
  15. Toutes les Resources
  16. Dashboard widgets + graphiques
  17. Activity Log integration
  18. Settings resource

Phase 5 — Frontend
  19. Inertia + React setup
  20. Layout global + animations (GSAP, Framer Motion, Lenis)
  21. Site vitrine (toutes les sections)
  22. Dashboard client
  23. Lecteur formations (vidéo, audio, PDF)

Phase 6 — Finalisation
  24. Tests
  25. Commandes Artisan custom
  26. Documentation .env.example complet
  27. README avec instructions déploiement
```

---

## 🚀 INSTRUCTIONS FINALES POUR CLAUDE OPUS

1. **Génère chaque fichier complet**, sans troncature ni `// ... reste du code`. Chaque fichier doit être fonctionnel tel quel.

2. **Commence par la Phase 1** et avance séquentiellement.

3. **Pour chaque fichier créé**, indique clairement son chemin complet (ex: `app/Services/StripeService.php`).

4. **Applique les best practices Laravel 11** : typed properties, enums, readonly properties, new helpers.

5. **Le site vitrine doit être ÉPOUSTOUFLANT** visuellement. Chaque animation doit avoir un but UX clair.

6. **Ne génère jamais de faux commentaires** du type `// logique ici`. Si tu ne peux pas implémenter quelque chose, explique pourquoi et propose une alternative.

7. **Tous les textes** (copy, headlines, descriptions) doivent être rédigés comme si tu étais un copywriter expert en e-commerce — percutants, orientés bénéfices, avec preuve sociale.

8. **La sécurité n'est pas optionnelle** — chaque endpoint doit être protégé, chaque input validé, chaque action loguée.

---

*Commence maintenant par la Phase 1 : génère le `docker-compose.yml`, le `Dockerfile` PHP, la config Nginx, puis enchaîne avec l'installation Laravel et les premières migrations.*

---

Ce prompt couvre l'intégralité de ton projet. Tu peux le passer directement à Opus en lui précisant de commencer par la Phase 1. Si tu veux, je peux aussi générer un second prompt pour la Phase 5 (frontend) avec encore plus de détail sur les animations et le design system.