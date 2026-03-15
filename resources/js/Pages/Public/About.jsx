import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import { GlassCard } from '@/Components/UI/GlassCard';

export default function About() {
    const stats = [
        { label: 'Étudiants', value: '500+', icon: '👥' },
        { label: 'Formations', value: '10+', icon: '📚' },
        { label: 'Satisfaction', value: '98%', icon: '⭐' },
        { label: 'Chiffre d\'affaires', value: '2M€+', icon: '💰' },
    ];

    return (
        <Layout>
            <Head title="À Propos" />

            {/* Hero Section */}
            <section className="py-20 relative overflow-hidden">
                <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 opacity-30">
                    <div className="absolute top-0 left-1/4 w-96 h-96 bg-gold/20 blur-[120px] rounded-full"></div>
                    <div className="absolute bottom-0 right-1/4 w-96 h-96 bg-primary/20 blur-[120px] rounded-full"></div>
                </div>

                <div className="max-w-7xl mx-auto px-6 text-center">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                    >
                        <span className="text-gold font-syne text-sm uppercase tracking-[0.3em] font-bold block mb-6">Notre Vision</span>
                        <h1 className="text-5xl md:text-8xl font-clash font-bold leading-tight mb-8">
                            Propulser les <span className="text-gradient-gold">Entrepreneurs</span> de demain.
                        </h1>
                        <p className="text-xl text-white/50 max-w-3xl mx-auto leading-relaxed font-jakarta">
                            NativeMeta est bien plus qu'une plateforme de formation. C'est un écosystème conçu pour ceux qui refusent la médiocrité et visent l'excellence financière.
                        </p>
                    </motion.div>
                </div>
            </section>

            {/* Stats */}
            <section className="pb-20">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="grid md:grid-cols-4 gap-8">
                        {stats.map((stat, index) => (
                            <motion.div
                                key={index}
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ delay: index * 0.1 }}
                            >
                                <GlassCard className="text-center group hover:border-gold/30 transition-all duration-500">
                                    <span className="text-4xl mb-4 block">{stat.icon}</span>
                                    <h3 className="text-3xl font-clash font-bold text-gold mb-1">{stat.value}</h3>
                                    <p className="text-xs text-white/40 uppercase tracking-widest font-bold">{stat.label}</p>
                                </GlassCard>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Content Section */}
            <section className="py-20 bg-white/5">
                <div className="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <SectionHeading
                            subtitle="Notre Histoire"
                            title="L'expertise au service du résultat."
                            description="Nous avons commencé avec une seule idée en tête : démocratiser les stratégies qui permettent réellement de générer des revenus en ligne."
                        />
                        <div className="space-y-6 text-white/60 font-jakarta leading-relaxed mt-10">
                            <p>
                                Après avoir généré des millions d'euros en e-commerce et en marketing digital, nous avons décidé de créer NativeMeta pour partager nos méthodes éprouvées.
                            </p>
                            <p>
                                Pas de théorie inutile. Uniquement des stratégies actionnables, des outils puissants et un accompagnement vers la liberté financière.
                            </p>
                        </div>
                    </div>
                    <div className="relative">
                        <div className="aspect-square rounded-3xl overflow-hidden glass-card p-2 border-white/10 group">
                            <div className="w-full h-full rounded-2xl bg-gradient-to-br from-gold/20 to-primary/20 flex items-center justify-center">
                                <span className="text-9xl group-hover:scale-110 transition-transform duration-700">🚀</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
