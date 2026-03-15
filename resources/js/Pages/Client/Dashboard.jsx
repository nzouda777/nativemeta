import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { GlassCard } from '@/Components/UI/GlassCard';

export default function Dashboard({ auth, enrollments, lastActivity }) {
    return (
        <Layout>
            <Head title="Tableau de bord" />

            <section className="py-20">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                        <div>
                            <span className="text-gold font-syne text-sm uppercase tracking-[0.3em] font-bold block mb-4">Espace Membre</span>
                            <h1 className="text-4xl md:text-6xl font-inter font-bold">Bienvenue, <span className="text-gradient-gold">{auth.user.name.split(' ')[0]}</span> 👋</h1>
                        </div>
                        <Link href={route('profile.show')} className="text-white/40 hover:text-gold text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                            Gérer mon profil
                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </Link>
                    </div>

                    {/* Stats/Resume Bar */}
                    <div className="grid md:grid-cols-3 gap-8 mb-16">
                        <GlassCard className="!p-8 !bg-gold/5 border-gold/10">
                            <p className="text-[10px] text-white/40 uppercase font-bold tracking-widest mb-2">Formations suivies</p>
                            <p className="text-4xl font-inter font-bold text-gold">{enrollments.length}</p>
                        </GlassCard>

                        {lastActivity && (
                            <div className="md:col-span-2 glass-card p-8 flex items-center justify-between">
                                <div>
                                    <p className="text-[10px] text-white/40 uppercase font-bold tracking-widest mb-2">Dernière leçon consultée</p>
                                    <h4 className="text-lg font-jakarta font-bold mb-1">{lastActivity.lesson_title}</h4>
                                    <p className="text-xs text-white/30 italic">dans {lastActivity.course_title}</p>
                                </div>
                                <Link
                                    href={route('enrollments.show', lastActivity.course_slug)}
                                    className="btn-gold !py-2 !px-6 text-xs"
                                >
                                    Reprendre
                                </Link>
                            </div>
                        )}
                    </div>

                    <h2 className="text-2xl font-inter font-bold mb-8">Mes Formations</h2>

                    {enrollments.length > 0 ? (
                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            {enrollments.map(item => (
                                <motion.div
                                    key={item.id}
                                    whileHover={{ y: -5 }}
                                    className="glass-card !p-0 overflow-hidden group border border-white/5 hover:border-gold/20 transition-all duration-500"
                                >
                                    <div className="relative aspect-video">
                                        <img src={item.course.thumbnail} className="w-full h-full object-cover" />
                                        <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <Link href={route('enrollments.show', item.course.slug)} className="btn-gold !py-2 !px-6">Continuer</Link>
                                        </div>
                                    </div>
                                    <div className="p-6">
                                        <h3 className="text-lg font-inter font-bold mb-4">{item.course.title}</h3>

                                        <div className="space-y-4">
                                            <div className="flex justify-between items-center text-[10px] font-bold uppercase tracking-tighter text-white/40">
                                                <span>Progression</span>
                                                <span>{item.progress}%</span>
                                            </div>
                                            <div className="w-full h-1 bg-white/5 rounded-full overflow-hidden">
                                                <motion.div
                                                    initial={{ width: 0 }}
                                                    animate={{ width: `${item.progress}%` }}
                                                    transition={{ duration: 1, ease: 'easeOut' }}
                                                    className="h-full bg-gold shadow-[0_0_10px_rgba(245,158,11,0.5)]"
                                                ></motion.div>
                                            </div>
                                        </div>
                                    </div>
                                </motion.div>
                            ))}
                        </div>
                    ) : (
                        <GlassCard className="text-center py-20 flex flex-col items-center">
                            <span className="text-6xl mb-6">🏜️</span>
                            <h3 className="text-xl font-inter font-bold mb-4">Aucune formation pour le moment</h3>
                            <p className="text-white/40 text-sm max-w-sm mb-8">Commence ton aventure et rejoins une de nos formations d'élite.</p>
                            <Link href={route('courses.index')} className="btn-gold">Explorer le catalogue</Link>
                        </GlassCard>
                    )}
                </div>
            </section>
        </Layout>
    );
}
