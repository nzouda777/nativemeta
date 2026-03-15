<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Permissions ───
        $permissions = [
            'view_courses', 'create_courses', 'edit_courses', 'delete_courses', 'publish_courses',
            'view_modules', 'create_modules', 'edit_modules', 'delete_modules',
            'view_lessons', 'create_lessons', 'edit_lessons', 'delete_lessons',
            'view_orders', 'refund_orders', 'export_orders',
            'view_users', 'create_users', 'edit_users', 'delete_users', 'manage_roles',
            'view_settings', 'edit_settings',
            'view_analytics',
            'view_enrollments', 'create_enrollments', 'edit_enrollments', 'delete_enrollments',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─── Roles ───
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::whereNotIn('name', ['edit_settings', 'manage_roles'])->get());

        $moderator = Role::firstOrCreate(['name' => 'moderator']);
        $moderator->syncPermissions([
            'view_courses', 'view_modules', 'view_lessons',
            'view_orders', 'view_users', 'view_analytics', 'view_enrollments',
        ]);

        Role::firstOrCreate(['name' => 'student']);

        // ─── Super Admin User ───
        $adminUser = User::firstOrCreate(
            ['email' => config('nativemeta.super_admin_email', 'admin@nativemeta.com')],
            [
                'name' => 'NativeMeta Admin',
                'password' => bcrypt(config('nativemeta.super_admin_password', 'password')),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $adminUser->assignRole('super_admin');

        // ____ Create student user seeder _____

        

        // ─── Categories ───
        $ecommerce = Category::firstOrCreate(
            ['slug' => 'e-commerce'],
            ['name' => 'E-Commerce', 'description' => 'Maîtrise les stratégies e-commerce', 'icon' => '🛒', 'color' => '#F59E0B', 'order' => 1]
        );

        $marketing = Category::firstOrCreate(
            ['slug' => 'marketing-digital'],
            ['name' => 'Marketing Digital', 'description' => 'Techniques avancées de marketing en ligne', 'icon' => '📈', 'color' => '#6366F1', 'order' => 2]
        );

        $automation = Category::firstOrCreate(
            ['slug' => 'automatisation'],
            ['name' => 'Automatisation', 'description' => 'Automatise ton business pour scaler', 'icon' => '⚡', 'color' => '#10B981', 'order' => 3]
        );

        // ─── Demo Course 1 ───
        $course1 = Course::firstOrCreate(
            ['slug' => 'masterclass-ecommerce-2024'],
            [
                'title' => 'Masterclass E-Commerce 2024',
                'description' => 'La formation la plus complète pour lancer et scaler ton business e-commerce. De 0 à 100K€/mois en 6 mois.',
                'long_description' => '<h2>Transforme ton business e-commerce</h2><p>Cette masterclass est le fruit de 5 ans d\'expérience en e-commerce et plus de 2M€ de chiffre d\'affaires généré. Tu vas apprendre toutes les stratégies que j\'utilise au quotidien pour créer des boutiques e-commerce rentables.</p><h3>Ce que tu vas apprendre :</h3><ul><li>Les fondamentaux du e-commerce moderne</li><li>Comment trouver des produits gagnants</li><li>Créer une boutique qui convertit</li><li>Maîtriser les Facebook Ads et TikTok Ads</li><li>Scaler de 0 à 100K€/mois</li><li>Automatiser ton business</li></ul>',
                'price' => 497.00,
                'sale_price' => 297.00,
                'currency' => 'EUR',
                'status' => 'published',
                'is_featured' => true,
                'category_id' => $ecommerce->id,
                'created_by' => $adminUser->id,
                'meta_title' => 'Masterclass E-Commerce 2024 - De 0 à 100K€/mois',
                'meta_description' => 'La formation e-commerce la plus complète. Apprends à créer, lancer et scaler ton business en ligne.',
            ]
        );

        // Create modules and lessons for course 1
        $this->createModulesForCourse($course1);

        // ─── Demo Course 2 ───
        $course2 = Course::firstOrCreate(
            ['slug' => 'facebook-ads-mastery'],
            [
                'title' => 'Facebook Ads Mastery',
                'description' => 'Maîtrise les Facebook Ads de A à Z. Stratégies avancées de ciblage, A/B testing et optimisation.',
                'long_description' => '<h2>Deviens un expert Facebook Ads</h2><p>Les Facebook Ads sont l\'un des leviers les plus puissants pour générer du chiffre d\'affaires en e-commerce. Cette formation te donnera toutes les clés pour créer des campagnes rentables.</p>',
                'price' => 297.00,
                'currency' => 'EUR',
                'status' => 'published',
                'is_featured' => true,
                'category_id' => $marketing->id,
                'created_by' => $adminUser->id,
                'meta_title' => 'Facebook Ads Mastery - Formations NativeMeta',
                'meta_description' => 'Apprends à maîtriser les Facebook Ads pour ton business e-commerce.',
            ]
        );

        // ─── Demo Course 3 ───
        Course::firstOrCreate(
            ['slug' => 'automatisation-business'],
            [
                'title' => 'Automatisation Business',
                'description' => 'Automatise chaque aspect de ton business pour gagner du temps et scaler plus vite.',
                'price' => 197.00,
                'currency' => 'EUR',
                'status' => 'published',
                'is_featured' => false,
                'category_id' => $automation->id,
                'created_by' => $adminUser->id,
            ]
        );

        // ─── Settings ──
        Setting::set('site.name', 'NativeMeta', 'general');
        Setting::set('site.tagline', 'Transforme ton Business en Machine à Cash', 'general');
        Setting::set('site.contact_email', 'contact@nativemeta.com', 'general');
        Setting::set('stats.students', 500, 'general');
        Setting::set('stats.revenue', '2M€', 'general');
        Setting::set('stats.satisfaction', '4.9/5', 'general');
        Setting::set('social.instagram', 'https://instagram.com/nativemeta', 'general');
        Setting::set('social.tiktok', 'https://tiktok.com/@nativemeta', 'general');
        Setting::set('social.youtube', 'https://youtube.com/@nativemeta', 'general');
    }

    private function createModulesForCourse(Course $course): void
    {
        $modules = [
            [
                'title' => 'Introduction & Mindset',
                'description' => 'Les fondamentaux pour réussir en e-commerce',
                'lessons' => [
                    ['title' => 'Bienvenue dans la Masterclass', 'type' => 'video', 'duration_seconds' => 420, 'is_preview' => true],
                    ['title' => 'Le mindset du e-commerçant à succès', 'type' => 'video', 'duration_seconds' => 900],
                    ['title' => 'Les erreurs à éviter absolument', 'type' => 'video', 'duration_seconds' => 780],
                    ['title' => 'Guide de démarrage rapide', 'type' => 'pdf', 'duration_seconds' => 300],
                ],
            ],
            [
                'title' => 'Trouver des Produits Gagnants',
                'description' => 'Méthodologie complète pour trouver des produits à fort potentiel',
                'lessons' => [
                    ['title' => 'Les 5 critères d\'un produit gagnant', 'type' => 'video', 'duration_seconds' => 1200],
                    ['title' => 'Outils de recherche de produits', 'type' => 'video', 'duration_seconds' => 1500],
                    ['title' => 'Analyser la concurrence', 'type' => 'video', 'duration_seconds' => 900],
                    ['title' => 'Checklist produit gagnant', 'type' => 'pdf', 'duration_seconds' => 180],
                ],
            ],
            [
                'title' => 'Créer ta Boutique',
                'description' => 'Construis une boutique qui convertit les visiteurs en clients',
                'lessons' => [
                    ['title' => 'Shopify de A à Z', 'type' => 'video', 'duration_seconds' => 2400],
                    ['title' => 'Design et branding', 'type' => 'video', 'duration_seconds' => 1800],
                    ['title' => 'Fiches produits qui convertissent', 'type' => 'video', 'duration_seconds' => 1200],
                    ['title' => 'Optimisation SEO boutique', 'type' => 'video', 'duration_seconds' => 900],
                ],
            ],
            [
                'title' => 'Publicité & Acquisition',
                'description' => 'Maîtrise les ads pour générer du trafic qualifié',
                'lessons' => [
                    ['title' => 'Stratégie Facebook Ads', 'type' => 'video', 'duration_seconds' => 2100],
                    ['title' => 'TikTok Ads pour e-commerce', 'type' => 'video', 'duration_seconds' => 1800],
                    ['title' => 'Créer des créatives qui convertissent', 'type' => 'video', 'duration_seconds' => 1500],
                    ['title' => 'Retargeting avancé', 'type' => 'video', 'duration_seconds' => 1200],
                    ['title' => 'Templates de créatives', 'type' => 'pdf', 'duration_seconds' => 0],
                ],
            ],
            [
                'title' => 'Scaler ton Business',
                'description' => 'Passe de 1K à 100K€/mois',
                'lessons' => [
                    ['title' => 'Le framework de scaling', 'type' => 'video', 'duration_seconds' => 1800],
                    ['title' => 'Optimisation des marges', 'type' => 'video', 'duration_seconds' => 1200],
                    ['title' => 'Automatisation et délégation', 'type' => 'video', 'duration_seconds' => 1500],
                    ['title' => 'Plan d\'action 90 jours', 'type' => 'pdf', 'duration_seconds' => 600],
                ],
            ],
        ];

        foreach ($modules as $moduleIndex => $moduleData) {
            $module = Module::create([
                'course_id' => $course->id,
                'title' => $moduleData['title'],
                'description' => $moduleData['description'],
                'order' => $moduleIndex + 1,
            ]);

            foreach ($moduleData['lessons'] as $lessonIndex => $lessonData) {
                Lesson::create([
                    'module_id' => $module->id,
                    'title' => $lessonData['title'],
                    'type' => $lessonData['type'],
                    'duration_seconds' => $lessonData['duration_seconds'],
                    'is_preview' => $lessonData['is_preview'] ?? false,
                    'order' => $lessonIndex + 1,
                ]);
            }
        }
    }
}
