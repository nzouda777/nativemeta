import React from 'react';
import { Link } from '@inertiajs/react';

export default function Footer() {
    return (
        <footer className="bg-dark-lighter border-t border-white/5 py-20">
            <div className="max-w-7xl mx-auto px-6">
                <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
                    <div className="col-span-1 md:col-span-1">
                        <Link href="/" className="flex items-center gap-2 mb-6">
                            <div className="w-8 h-8 bg-gold rounded-lg flex items-center justify-center font-syne text-dark text-lg font-bold">N</div>
                            <span className="font-clash text-xl font-bold">Native<span className="text-gold">Meta</span></span>
                        </Link>
                        <p className="text-white/50 text-sm leading-relaxed mb-6 font-inter">
                            Transforme ton business en machine à cash avec les meilleures stratégies e-commerce et automatisation.
                        </p>
                        <div className="flex gap-4">
                            {/* Social Icons Placeholders */}
                            {[1, 2, 3].map(i => (
                                <div key={i} className="w-10 h-10 border border-white/10 rounded-full flex items-center justify-center hover:border-gold hover:text-gold transition-all cursor-pointer">
                                    <div className="w-4 h-4 bg-current rounded-sm"></div>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div>
                        <h4 className="font-clash text-lg mb-6">Formations</h4>
                        <ul className="flex flex-col gap-3 text-white/50 text-sm font-jakarta">
                            <li><Link href="#" className="hover:text-gold transition-colors">E-Commerce Mastery</Link></li>
                            <li><Link href="#" className="hover:text-gold transition-colors">TikTok Ads Masterclass</Link></li>
                            <li><Link href="#" className="hover:text-gold transition-colors">Email Marketing</Link></li>
                            <li><Link href="#" className="hover:text-gold transition-colors">Automatisation Business</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="font-clash text-lg mb-6">Plateforme</h4>
                        <ul className="flex flex-col gap-3 text-white/50 text-sm font-jakarta">
                            <li><Link href={route('about')} className="hover:text-gold transition-colors">À Propos</Link></li>
                            <li><Link href={route('testimonials')} className="hover:text-gold transition-colors">Témoignages</Link></li>
                            <li><Link href={route('login')} className="hover:text-gold transition-colors">Espace Membre</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h4 className="font-clash text-lg mb-6">Légal</h4>
                        <ul className="flex flex-col gap-3 text-white/50 text-sm font-jakarta">
                            <li><Link href={route('legal.mentions')} className="hover:text-gold transition-colors">Mentions Légales</Link></li>
                            <li><Link href={route('legal.cgv')} className="hover:text-gold transition-colors">CGV</Link></li>
                            <li><Link href={route('legal.privacy')} className="hover:text-gold transition-colors">Confidentialité</Link></li>
                        </ul>
                    </div>
                </div>

                <div className="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-white/30 text-xs">
                    <p>&copy; {new Date().getFullYear()} NativeMeta Platform. Tous droits réservés.</p>
                    <p>Designed with ❤️ for High-Performance Entrepreneurs.</p>
                </div>
            </div>
        </footer>
    );
}
