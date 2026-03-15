import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link } from '@inertiajs/react';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import { MagneticButton } from '@/Components/UI/MagneticButton';

export default function CheckoutCancel() {
    return (
        <Layout>
            <Head title="Paiement Annulé - NativeMeta" />

            <section className="min-h-[80vh] flex items-center justify-center py-20">
                <div className="max-w-2xl mx-auto px-6 text-center">
                    <div className="mb-12 inline-flex items-center justify-center w-24 h-24 rounded-full bg-red-500/10 text-red-500 text-5xl">
                        ✕
                    </div>

                    <SectionHeading
                        subtitle="Annulation"
                        title="PAIEMENT NON FINALISÉ."
                        centered={true}
                    />

                    <p className="text-xl text-white/40 leading-relaxed mb-12 animate-in">
                        On dirait que le paiement a été annulé ou qu'une erreur est survenue.
                        Ne t'inquiète pas, ton compte n'a pas été débité.
                    </p>

                    <div className="flex flex-col md:flex-row items-center justify-center gap-8">
                        <MagneticButton>
                            <Link href={route('courses.index')} className="btn-gold">
                                Retour au catalogue
                            </Link>
                        </MagneticButton>

                        <Link href="/" className="text-white/20 hover:text-white transition-colors uppercase tracking-[0.3em] font-bold text-[10px]">
                            Besoin d'aide ?
                        </Link>
                    </div>
                </div>
            </section>
        </Layout>
    );
}
