import React, { useState, useRef, useEffect } from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { gsap } from 'gsap';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import { GlassCard } from '@/Components/UI/GlassCard';
import { MagneticButton } from '@/Components/UI/MagneticButton';
import SyllabusAccordion from '@/Components/SyllabusAccordion';

export default function CourseDetail({ course, userHasAccess }) {
    const { auth = { user: null } } = usePage().props;
    const [showEmailStep, setShowEmailStep] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const handlePurchase = (e) => {
        e.preventDefault();

        // If not logged in and email step not shown, show it
        if (!auth?.user && !showEmailStep) {
            setShowEmailStep(true);
            return;
        }

        // If not logged in and email step is shown, but email is empty, don't submit
        if (!auth?.user && showEmailStep && !data.email) {
            return;
        }

        post(route('checkout.create', course.id));
    };

    const faqs = [
        {
            q: "Est-ce que l'accès est vraiment à vie ?",
            a: "Oui, une fois ton accès validé, tu peux consulter la formation 24h/24, 7j/7, pour toujours. Toutes les mises à jour futures sont incluses gratuitement."
        },
        {
            q: "Y aura-t-il un accompagnement ?",
            a: "Absolument. Tu bénéficies d'un support dédié pour répondre à toutes tes questions techniques ou stratégiques durant ton parcours."
        },
        {
            q: "La formation est-elle adaptée aux débutants ?",
            a: "Nos parcours sont conçus pour t'amener du niveau débutant au niveau expert. Chaque concept complexe est décomposé pour être actionnable immédiatement."
        },
        {
            q: "Puis-je obtenir une facture ?",
            a: "Oui, une facture détaillée te sera envoyée automatiquement par email immédiatement après ton règlement via Stripe."
        }
    ];

    const reviews = [
        {
            name: "Lucas R.",
            role: "Entrepreneur Digital",
            text: "La claque. Les stratégies sont chirurgicales et le design de la plateforme rend l'apprentissage addictif.",
            stars: 5
        },
        {
            name: "Sofia K.",
            role: "Freelance",
            text: "J'ai rentabilisé la formation en 10 jours seulement. Le module sur la psychologie client est une pépite.",
            stars: 5
        },
        {
            name: "Marc-Antoine P.",
            role: "E-commerçant",
            text: "Enfin du contenu qui ne se trouve pas sur YouTube. L'approche 'Native' change tout.",
            stars: 5
        }
    ];

    return (
        <Layout>
            <Head title={course.title} />



            <section className="md:pt-8 pt-1 pb-40 animate-in">
                <div className="max-w-7xl mx-auto px-6 grid lg:grid-cols-3 gap-16">

                    {/* Main Content */}
                    <div className="lg:col-span-2 space-y-32">
                        <div className="reveal-section">
                            <h1 className="text-3xl md:text-5xl font-inter font-bold leading-[1] tracking-tighter md:mb-4 mb-2">
                                {course.title}
                            </h1>

                            <div className="flex flex-wrap items-center gap-8 text-[10px] font-inter font-bold uppercase tracking-widest text-white/40 md:mb-12 mb-6">
                                <div className="flex items-center gap-3">
                                    <span className="w-2 h-2 rounded-lg bg-white/5 flex items-center justify-center text-white">🎯</span>
                                    {course.category}
                                </div>
                                {/* <div className="flex items-center gap-3">
                                    <span className="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-white">⏱️</span>
                                    {course.duration}
                                </div> */}
                                <div className="flex items-center gap-3">
                                    <span className="w-2 h-2 rounded-lg bg-white/5 flex items-center justify-center text-white">📈</span>
                                    Niveau Expert
                                </div>
                            </div>

                            <div className="relative aspect-video rounded-2xl overflow-hidden bg-white/5 border border-white/5 shadow-2xl group">
                                <img src={course.thumbnail} className="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" />
                               { course.trailer_url && (
                                <div className="absolute inset-0 bg-black/40 flex items-center justify-center transition-opacity duration-500 opacity-100">
                                    <div className="w-20 h-20 bg-white text-black rounded-full flex items-center justify-center hover:bg-gold hover:scale-110 transition-all duration-500 shadow-xl">
                                        <svg className="w-8 h-8 translate-x-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                                    </div>
                                </div>
                               )}
                            </div>
                        </div>

                        <div className=" !mt-8 prose prose-invert max-w-none prose-h2:font-inter prose-h2:text-4xl prose-h2:tracking-tight prose-p:text-white/40 prose-p:leading-relaxed prose-h2:mb-10 reveal-section" dangerouslySetInnerHTML={{ __html: course.long_description }} />

                        {/* Syllabus */}
                        <div className="reveal-section !mt-12">
                            <SectionHeading
                                subtitle="Syllabus"
                                title="Programme détaillé"
                                description="Un parcours millimétré pour ton succès."
                            />
                            <SyllabusAccordion modules={course.modules} />
                        </div>

                        {/* Reviews */}
                        <div className="reveal-section !mt-12">
                            <SectionHeading
                                subtitle="Témoignages"
                                title="Ils ont sauté le pas"
                                description="Rejoins les centaines d'élèves qui ont déjà transformé leur vision du business."
                            />
                            <div className="grid md:grid-cols-2 gap-6">
                                {reviews.map((review, i) => (
                                    <GlassCard key={i} className="!p-8 hover:border-gold/20 transition-colors">
                                        <div className="flex gap-1 mb-6">
                                            {[...Array(review.stars)].map((_, j) => (
                                                <span key={j} className="text-gold text-xs">★</span>
                                            ))}
                                        </div>
                                        <p className="text-white/60 mb-8 font-inter leading-relaxed italic line-clamp-3">"{review.text}"</p>
                                        <div className="flex items-center gap-4">
                                            <div className="w-10 h-10 rounded-full bg-gold/10 flex items-center justify-center font-inter text-gold text-xs font-bold">
                                                {review.name.charAt(0)}
                                            </div>
                                            <div>
                                                <p className="font-inter font-bold text-sm">{review.name}</p>
                                                <p className="text-[10px] text-white/20 uppercase tracking-widest">{review.role}</p>
                                            </div>
                                        </div>
                                    </GlassCard>
                                ))}
                                <GlassCard className="!p-8 bg-gold/[0.02] border-gold/10 flex flex-col justify-center items-center text-center">
                                    <p className="text-3xl font-inter font-bold mb-2">4.9/5</p>
                                    <p className="text-[10px] text-gold uppercase tracking-[0.2em] font-bold">Note moyenne globale</p>
                                </GlassCard>
                            </div>
                        </div>

                        {/* FAQ */}
                        <div className="reveal-section !mt-12">
                            <SectionHeading
                                subtitle="FAQ"
                                title="Questions fréquentes"
                                description="Tout ce que tu dois savoir avant de nous rejoindre."
                            />
                            <div className="space-y-4">
                                {faqs.map((faq, i) => (
                                    <details key={i} className="group glass-card !p-0 overflow-hidden border-white/5 hover:border-white/10 transition-all">
                                        <summary className="list-none py-2 px-6 flex justify-between items-center cursor-pointer select-none">
                                            <span className="font-inter font-bold text-base md:text-xl tracking-tight">{faq.q}</span>
                                            <span className="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center group-open:rotate-180 transition-transform duration-500">
                                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
                                            </span>
                                        </summary>
                                        <div className="px-8 pb-8 text-white/40 font-inter leading-relaxed max-w-2xl animate-in">
                                            {faq.a}
                                        </div>
                                    </details>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar Desktop */}
                    <aside className="hidden lg:block">
                        <div className="sticky top-32 space-y-8">
                            <div className="glass-card !p-12 border-white/5 shadow-2xl relative overflow-hidden group">
                                <div className="absolute top-0 right-0 w-32 h-32 bg-gold/10 blur-[60px] rounded-full -mr-16 -mt-16 group-hover:bg-gold/20 transition-colors duration-700"></div>

                                <span className="inline-block px-4 py-1 bg-white/5 text-white/40 text-[10px] font-bold uppercase tracking-[0.3em] rounded-full mb-10">Accès Premium</span>

                                <div className="flex items-baseline gap-4 mb-10">
                                    <h2 className="text-6xl font-inter font-bold tracking-tighter text-gold">{course.effective_price}€</h2>
                                    {course.is_on_sale && (
                                        <span className="text-xl text-white/20 line-through font-light">{course.price}€</span>
                                    )}
                                </div>

                                <ul className="space-y-6 mb-12 text-sm font-inter text-white/60">
                                    {[
                                        'Accès instantané & à vie',
                                        'Mises à jour offertes',
                                        'Support VIP & Communauté',
                                        'Certificat d\'excellence'
                                    ].map((item, i) => (
                                        <li key={i} className="flex items-center gap-4">
                                            <div className="w-5 h-5 rounded-md bg-gold/10 flex items-center justify-center text-gold text-[10px] font-bold border border-gold/10">✓</div>
                                            {item}
                                        </li>
                                    ))}
                                </ul>

                                <div className="space-y-4">
                                    {userHasAccess ? (
                                        <Link href={route('enrollments.show', course.slug)} className="btn-gold w-full flex items-center justify-center !py-4 !px-4 leading-release text-lg font-inter">
                                            Voir la formation
                                        </Link>
                                    ) : (
                                        <div className="space-y-4">
                                            {!auth?.user && showEmailStep && (
                                                <div className="animate-in pb-2">
                                                    <label className="text-[10px] uppercase tracking-widest text-white/30 font-bold mb-3 block text-center">Ton email pour l'activation</label>
                                                    <input
                                                        type="email"
                                                        value={data.email}
                                                        onChange={e => setData('email', e.target.value)}
                                                        placeholder="nom@exemple.com"
                                                        className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-5 text-white text-center focus:border-gold outline-none transition-all placeholder:text-white/10"
                                                        required
                                                    />
                                                    {errors.email && <p className="text-red-500 text-[10px] mt-2 text-center font-bold uppercase tracking-widest">{errors.email}</p>}
                                                </div>
                                            )}
                                            <MagneticButton className="w-full">
                                                <button
                                                    onClick={handlePurchase}
                                                    disabled={processing}
                                                    className="btn-gold w-full text-xl !py-6 shadow-[0_30px_60px_-15px_rgba(245,158,11,0.3)] active:scale-95 border border-white/10"
                                                >
                                                    {processing ? '...' : (showEmailStep && !auth.user ? 'Valider' : 'Rejoindre l\'Élite')}
                                                </button>
                                            </MagneticButton>
                                        </div>
                                    )}
                                </div>

                                <div className="flex items-center justify-center gap-3 mt-12 opacity-30 grayscale hover:grayscale-0 transition-all">
                                    <span className="text-[8px] font-bold tracking-widesta uppercase">Paiement 100% Sécurisé</span>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </section>

            {/* Sticky Mobile CTA */}
            <div className="lg:hidden fixed bottom-0 left-0 w-full z-[100] p-4 bg-dark/80 backdrop-blur-2xl border-t border-white/5 safe-area-bottom transform-gpu">
                {userHasAccess ? (
                    <Link href={route('enrollments.show', course.slug)} className="btn-gold font-inter w-full flex items-center justify-center !py-3 text-xl">
                        Voir la formation
                    </Link>
                ) : (
                    <>
                        {!auth?.user && showEmailStep && (
                            <div className="mb-4 animate-in">
                                <input
                                    type="email"
                                    placeholder="Ton email pour l'activation"
                                    value={data.email}
                                    onChange={e => setData('email', e.target.value)}
                                    className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-gold outline-none transition-all placeholder:text-white/20"
                                />
                            </div>
                        )}
                        <div className="flex items-center gap-4">
                            <div className="flex flex-col">
                                <span className="text-[10px] uppercase tracking-widest text-white/30 font-bold">Total</span>
                                <span className="text-xl font-inter font-bold text-gold">{course.effective_price}€</span>
                            </div>
                            <button
                                onClick={handlePurchase}
                                disabled={processing}
                                className="btn-gold !py-3 flex-1 text-sm font-inter shadow-[0_10px_30px_rgba(245,158,11,0.2)]"
                            >
                                {processing ? '...' : (showEmailStep && !auth.user ? 'Confirmer' : 'Prendre mon accès')}
                            </button>
                        </div>
                    </>
                )}

            </div>
        </Layout>
    );
}
