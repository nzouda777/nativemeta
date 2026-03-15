import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link } from '@inertiajs/react';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import { MagneticButton } from '@/Components/UI/MagneticButton';

export default function CheckoutSuccess({ session }) {
    return (
        <Layout>
            <Head title="Paiement Réussi - NativeMeta" />

            <section className="min-h-[80vh] flex items-center justify-center py-20">
                <div className="max-w-3xl mx-auto px-6 text-center">
                    <div className="mb-12 inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-500/10 text-green-500 text-5xl animate-bounce">
                        ✓
                    </div>

                    <SectionHeading
                        subtitle="Succès"
                        title="BIENVENUE DANS L'ÉLITE."
                        centered={true}
                    />

                    <div className="glass-card mb-12 animate-in">
                        <p className="text-xl text-white/60 leading-relaxed mb-8">
                            Ton paiement de <span className="text-white font-bold">{session.amount_total} {session.currency}</span> a été confirmé avec succès.
                        </p>

                        <div className="p-8 bg-white/5 rounded-2xl border border-white/5 text-left mb-8">
                            <h3 className="text-lg font-inter font-bold mb-4">Prochaine étape :</h3>
                            <p className="text-white/40 leading-relaxed">
                                Un email vient d'être envoyé à <span className="text-white font-bold">{session.customer_email}</span>.
                                Il contient un lien sécurisé pour créer ton compte (ou te connecter) et accéder instantanément à ta formation.
                            </p>
                        </div>

                        <p className="text-sm text-white/20 italic">
                            Si tu ne reçois rien d'ici 5 minutes, vérifie tes spams ou contacte notre support.
                        </p>
                    </div>

                    <div className="flex flex-col items-center gap-8">
                        <Link href="/" className="text-white/40 hover:text-white transition-colors uppercase tracking-[0.3em] font-bold text-[10px]">
                            Retour à l'accueil
                        </Link>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
