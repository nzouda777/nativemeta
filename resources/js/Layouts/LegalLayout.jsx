import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';

export default function LegalLayout({ title, children }) {
    return (
        <Layout>
            <Head title={title} />
            <section className="py-20">
                <div className="max-w-4xl mx-auto px-6">
                    <h1 className="text-4xl md:text-6xl font-clash font-bold mb-16 text-center">{title}</h1>
                    <div className="prose prose-invert max-w-none prose-headings:font-clash prose-h2:text-2xl prose-h2:text-gold prose-p:text-white/60 prose-p:leading-relaxed prose-li:text-white/60">
                        {children}
                    </div>
                </div>
            </section>
        </Layout>
    );
}
