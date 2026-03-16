import React, { useRef, useEffect } from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link } from '@inertiajs/react';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { MagneticButton } from '@/Components/UI/MagneticButton';
import CourseCard from '@/Components/CourseCard';

gsap.registerPlugin(ScrollTrigger);

export default function Home({ courses, categories, stats }) {
    const containerRef = useRef(null);

    useEffect(() => {
        let ctx = gsap.context(() => {
            // Hero reveal
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from(".hero-line span", {
                y: 150,
                duration: 1.5,
                stagger: 0.15,
                skewY: 7
            })
                .from(".hero-description", {
                    opacity: 0,
                    y: 20,
                    duration: 1
                }, "-=1")
                .from(".hero-cta", {
                    opacity: 0,
                    scale: 0.9,
                    duration: 1,
                    stagger: 0.1
                }, "-=0.8");

            // Scroll animations for sections
            gsap.utils.toArray('.reveal-section').forEach(section => {
                gsap.from(section, {
                    scrollTrigger: {
                        trigger: section,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    },
                    opacity: 0,
                    y: 40,
                    duration: 1,
                    ease: "power2.out"
                });
            });

            // Specific bento highlight
            gsap.from('.bento-item', {
                scrollTrigger: {
                    trigger: '.bento-grid',
                    start: "top 80%",
                },
                opacity: 0,
                y: 30,
                stagger: 0.1,
                duration: 0.8
            });

        }, containerRef);

        return () => ctx.revert();
    }, []);

    const methodology = [
        {
            num: '01',
            title: 'IMMERSION ANALYTIQUE',
            desc: 'On ne devine pas, on analyse. Découvre comment identifier les marchés à forte croissance avant tout le monde.'
        },
        {
            num: '02',
            title: 'SYSTÈME D\'ÉLITE',
            desc: 'Automatise 90% de ton business. On implémente des structures qui tournent pour toi, pas l\'inverse.'
        },
        {
            num: '03',
            title: 'SCALING AGRESSIF',
            desc: 'Une fois le moteur lancé, on pousse les gaz. Apprends à multiplier ton chiffre d\'affaires de manière exponentielle.'
        }
    ];

    return (
        <Layout>
            <Head title="Académie d'Élite pour Entrepreneurs du Futur" />

            <div ref={containerRef} className="bg-dark selection:bg-white selection:text-dark overflow-hidden">

                {/* HERO: The Hook */}
                <section className="relative min-h-[90vh] flex flex-col items-center justify-center pt-20 pb-10">
                    <div className="absolute inset-0 overflow-hidden -z-10 opacity-40">
                        <div className="absolute top-[20%] left-[10%] w-[30vw] h-[30vw] bg-white/5 blur-[120px] rounded-full"></div>
                        <div className="absolute bottom-[20%] right-[10%] w-[25vw] h-[25vw] bg-gold/5 blur-[100px] rounded-full"></div>
                    </div>

                    <div className="max-w-7xl mx-auto px-6 text-center">
                        <div className="mb-8">
                            <span className="inline-block px-4 py-1 border border-white/10 rounded-full text-[10px] uppercase tracking-[0.4em] text-white/40 font-bold backdrop-blur-sm hero-description">
                                L'ÉLITE DU BUSINESS DIGITAL
                            </span>
                        </div>

                        <div className="hero-line overflow-hidden mb-4">
                            <span className="block text-5xl md:text-8xl font-clash font-bold leading-none tracking-tight">
                                CESSE DE REGARDER,
                            </span>
                        </div>
                        <div className="hero-line overflow-hidden mb-12">
                            <span className="block text-6xl md:text-[11vw] font-clash font-black leading-none tracking-tighter text-white">
                                <span className="text-gold">PRENDS</span> LE CONTRÔLE.
                            </span>
                        </div>

                        <div className="max-w-2xl mx-auto mb-16 hero-description">
                            <p className="text-xl md:text-2xl text-white/30 leading-relaxed font-inter font-light">
                                NativeMeta est le sanctuaire de ceux qui visent l'indépendance totale.
                                Pas de blabla, juste des systèmes d'élite pour dominer ton marché.
                            </p>
                        </div>

                        <div className="flex flex-col md:flex-row items-center justify-center gap-10 hero-cta ">
                            <MagneticButton>
                                <Link
                                    href={route('courses.index')}
                                    className="btn-gold !py-7 !px-16 !text-2xl"
                                >
                                    DÉCOUVRIR LE SYSTÈME
                                </Link>
                            </MagneticButton>

                            <Link
                                href={route('about')}
                                className="text-xs uppercase tracking-[0.4em] font-bold text-white/40 hover:text-gold transition-colors border-b border-white/10 pb-2"
                            >
                                Ma Philosophie →
                            </Link>
                        </div>
                    </div>
                </section>

                {/* TRUST: Social Proof Numbers */}
                <section className="py-32 border-y border-white/5">
                    <div className="max-w-7xl mx-auto px-6">
                        <div className="grid grid-cols-2 lg:grid-cols-4 gap-16">
                            {[
                                { val: stats.students + '+', label: 'Entrepreneurs Formés' },
                                { val: '98%', label: 'Taux de Succès Clients' },
                                { val: stats.revenue, label: 'Généré par nos membres' },
                                { val: 'Elite', label: 'Accompagnement VIP' }
                            ].map((stat, i) => (
                                <div key={i} className="reveal-section flex flex-col items-center lg:items-start group">
                                    <span className="text-5xl md:text-6xl font-clash font-bold tracking-tighter mb-4 group-hover:text-gold transition-colors duration-500">{stat.val}</span>
                                    <span className="text-[10px] uppercase tracking-[0.3em] font-bold text-gold/30">{stat.label}</span>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* METHODOLOGY: The Flow */}
                <section className="py-40 relative">
                    <div className="max-w-7xl mx-auto px-6">
                        <div className="grid lg:grid-cols-2 gap-32 items-center">
                            <div className="reveal-section">
                                <span className="text-gold font-bold text-[10px] uppercase tracking-[0.5em] block mb-8">La Méthode NativeMeta</span>
                                <h2 className="text-5xl md:text-8xl font-clash font-bold leading-[0.9] tracking-tighter mb-12">
                                    UNE LOGIQUE <br /> DE RÉSULTATS.
                                </h2>
                                <p className="text-xl text-white/30 leading-relaxed font-light font-inter max-w-lg mb-12">
                                    On ne vend pas du rêve. On propose une architecture business robuste,
                                    éprouvée par des années d'expertise sur le terrain.
                                </p>
                            </div>

                            <div className="space-y-16">
                                {methodology.map((item, i) => (
                                    <div key={i} className="reveal-section group flex gap-10">
                                        <span className="text-3xl font-clash font-bold text-gold/40 group-hover:text-gold transition-colors duration-500">{item.num}</span>
                                        <div>
                                            <h3 className="text-2xl font-clash font-bold mb-4 tracking-tight group-hover:translate-x-2 transition-transform duration-500">{item.title}</h3>
                                            <p className="text-white/40 leading-relaxed font-inter font-light">{item.desc}</p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>

                {/* BENTO: Ecosystem Highlights */}
                <section className="py-40 bg-white/[0.01]">
                    <div className="max-w-7xl mx-auto px-6">
                        <div className="mb-24 reveal-section">
                            <h2 className="text-5xl md:text-7xl font-clash font-bold tracking-tighter text-center">L'ÉCOSYSTÈME DE TA RÉUSSITE.</h2>
                        </div>

                        <div className="grid md:grid-cols-12 gap-8 bento-grid">
                            <div className="md:col-span-12 lg:col-span-8 bento-item">
                                <div className="h-full md:p-16 py-12 px-2 bg-white/[0.03] border border-white/5 rounded-[3rem] hover:bg-white/[0.05] transition-all duration-700 group overflow-hidden relative">
                                    <div className="relative z-10 md:block flex flex-col items-center justify-center text-center md:text-left">
                                        <span className="text-4xl mb-10 block">📡</span>
                                        <h3 className="text-3xl font-clash font-bold mb-6 tracking-tight">VÉITABLE ACCOMPAGNEMENT</h3>
                                        <p className="text-white/40 text-lg leading-relaxed max-w-xl font-light">
                                            Tu n'es plus seul. Accède à une communauté fermée d'entrepreneurs qui scalent,
                                            échanges des stratégies en temps réel et obtiens du feedback sur tes projets.
                                        </p>
                                    </div>
                                    <div className="absolute -right-20 -bottom-20 w-[400px] h-[400px] bg-gold/5 blur-[120px] rounded-full group-hover:bg-gold/10 transition-colors duration-1000"></div>
                                </div>
                            </div>

                            <div className="md:col-span-12 lg:col-span-4  bento-item">
                                <div className="h-full md:p-16 py-12 px-2 bg-white text-dark rounded-[3rem] hover:bg-gold transition-colors duration-700 flex flex-col justify-between items-center text-center">
                                    <span className="text-7xl">💎</span>
                                    <div>
                                        <h4 className="text-3xl font-clash font-bold mb-4 tracking-tighter">QUALITÉ 4K ELITE</h4>
                                        <p className="text-dark/60 text-sm font-bold uppercase tracking-widest leading-loose">
                                            IMMERSION TOTALE. <br /> SUPPORTS PDF HAUT DE GAMME. <br /> MISES À JOUR À VIE.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {/* COURSES: The Catalyst */}
                <section className="py-40 border-y border-white/5">
                    <div className="max-w-7xl mx-auto px-6">
                        <div className="flex flex-col md:flex-row justify-between items-end mb-24 gap-12 reveal-section">
                            <div className="max-w-2xl">
                                <span className="text-white/20 font-bold text-[10px] uppercase tracking-[0.5em] block mb-8">Les Passerelles vers le Sommet</span>
                                <h2 className="text-6xl md:text-[8vw] font-clash font-bold tracking-tighter leading-none uppercase">L'ARSENAL.</h2>
                            </div>
                            <Link href={route('courses.index')} className="group flex items-center gap-6 text-lg text-gold font-clash font-bold uppercase tracking-widest border border-gold/10 px-10 py-5 rounded-full hover:bg-gold hover:text-black transition-all duration-500">
                                <span>Voir tout</span>
                                <span className="group-hover:translate-x-2 transition-transform">→</span>
                            </Link>
                        </div>

                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-16">
                            {courses.map(course => (
                                <div key={course.id} className="reveal-section">
                                    <CourseCard course={course} />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA: Final Push */}
                <section className="py-60 relative overflow-hidden text-center">
                    <div className="max-w-4xl mx-auto px-6 reveal-section relative z-10">
                        <h2 className="text-7xl md:text-[10vw] font-clash font-black leading-none tracking-tighter mb-20">
                            LE FUTUR <br />  N'ATTEND <br /> <span className="text-gold italic">PERSONNE.</span>
                        </h2>

                        <MagneticButton>
                            <Link
                                href={route('courses.index')}
                                className="btn-gold !py-10 !px-20 !text-3xl"
                            >
                                JE REJOINS L'ÉLITE
                            </Link>
                        </MagneticButton>
                    </div>

                    {/* Infinite Marquee bg */}
                    <div className="absolute bottom-10 left-0 w-full opacity-[0.02] pointer-events-none select-none">
                        <div className="text-[250px] font-clash font-black whitespace-nowrap animate-marquee uppercase">
                            DOMINATION • LIBERTÉ • SYSTÈME • DOMINATION • LIBERTÉ • SYSTÈME • DOMINATION •
                        </div>
                    </div>
                </section>
            </div>
        </Layout>
    );
}
