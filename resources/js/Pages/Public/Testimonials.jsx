import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import { GlassCard } from '@/Components/UI/GlassCard';

export default function Testimonials() {
    const reviews = [
        { name: 'Loïc R.', role: 'E-commerçant', text: 'Une claque. Cette formation m\'a permis de passer de 2k à 15k par mois en moins de 3 mois.', avatar: 'LR' },
        { name: 'Sarah M.', role: 'Entrepreneur digital', text: 'Le support est incroyable et la qualité des vidéos est digne d\'un film hollywoodien.', avatar: 'SM' },
        { name: 'Thomas D.', role: 'Dropshipper Expert', text: 'Enfin du contenu concret sans langue de bois. L\'automatisation a sauvé mon business.', avatar: 'TD' },
    ];

    return (
        <Layout>
            <Head title="Témoignages" />
            <section className="py-20">
                <div className="max-w-7xl mx-auto px-6">
                    <SectionHeading
                        subtitle="Success Stories"
                        title="Ils ont transformé leur business"
                        description="Découvre les résultats de nos étudiants qui ont appliqué nos méthodes."
                    />

                    <div className="grid md:grid-cols-3 gap-8 mt-16">
                        {reviews.map((review, i) => (
                            <GlassCard key={i} className="hover:border-gold/20 transition-all">
                                <p className="text-white/70 italic mb-8 font-inter">"{review.text}"</p>
                                <div className="flex items-center gap-4">
                                    <div className="w-10 h-10 bg-gold rounded-full flex items-center justify-center font-bold text-dark">
                                        {review.avatar}
                                    </div>
                                    <div>
                                        <h4 className="font-bold text-sm">{review.name}</h4>
                                        <p className="text-[10px] text-white/40 uppercase tracking-widest">{review.role}</p>
                                    </div>
                                </div>
                            </GlassCard>
                        ))}
                    </div>
                </div>
            </section>
        </Layout>
    );
}
