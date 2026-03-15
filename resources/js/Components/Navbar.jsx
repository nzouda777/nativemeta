import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function Navbar() {
    const { auth } = usePage().props;
    const [scrolled, setScrolled] = useState(false);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 50);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const navLinks = [
        { name: 'Formations', href: route('courses.index') },
        { name: 'À Propos', href: route('about') },
    ];

    return (
        <nav
            className={`fixed top-0 left-0 w-full z-50 transition-all duration-500 ${scrolled ? 'py-4 bg-dark/80 backdrop-blur-lg border-b border-white/5' : 'pt-4 bg-transparent'
                }`}
        >
            <div className="max-w-7xl mx-auto px-6 flex justify-between items-center">
                <Link href="#" className="flex items-center gap-2 group">
                    <div className="w-10 h-10 bg-gold text-black rounded-xl flex items-center justify-center font-inter text-xl font-bold group-hover:rotate-12 transition-transform shadow-[0_0_20px_rgba(45,127,249,0.3)]">
                        N
                    </div>
                    <span className="font-inter text-2xl font-bold tracking-tight">
                        Native<span className="text-gold">Meta</span>
                    </span>
                </Link>

                {/* Desktop Nav */}
                <div className="hidden md:flex items-center gap-8">
                    {/* {navLinks.map((link) => (
                        <Link
                            key={link.name}
                            href={link.href}
                            className="text-[10px] uppercase tracking-[0.2em] font-inter font-bold text-white/50 hover:text-gold transition-colors"
                        >
                            {link.name}
                        </Link>
                    ))} */}

                    <div className="h-4 w-px bg-white/10 mx-2"></div>

                    {auth.user ? (
                        <Link
                            href={route('dashboard')}
                            className="text-[10px] uppercase tracking-[0.2em] font-inter font-bold text-white hover:text-gold transition-colors"
                        >
                            Mon Compte
                        </Link>
                    ) : (
                        <div className="flex items-center gap-8">
                            <Link href={route('login')} className="text-[10px] uppercase tracking-[0.2em] font-inter font-bold text-white/50 hover:text-gold transition-colors">
                                Connexion
                            </Link>
                            <Link href={route('register')} className="btn-gold !py-3 !px-8">
                                S'inscrire
                            </Link>
                        </div>
                    )}
                </div>

                {/* Mobile Menu Toggle */}
                <button
                    className="md:hidden text-white"
                    onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                >
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d={mobileMenuOpen ? "M6 18L18 6M6 6l12 12" : "M4 6h16M4 12h16m-7 6h7"} />
                    </svg>
                </button>
            </div>

            {/* Mobile Menu */}
            <div className={`absolute top-full left-0 w-full bg-dark/95 backdrop-blur-xl border-b border-white/5 py-2 px-8 transition-all duration-500 overflow-hidden ${mobileMenuOpen ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0 opacity-0 pointer-events-none'}`}>
                <div className="flex flex-col gap-0 text-center">
                    {/* {navLinks.map((link) => (
                        <Link key={link.name} href={link.href} className="text-2xl font-inter font-bold py-2">{link.name}</Link>
                    ))} */}
                    <hr className="border-white/5" />
                    {auth.user ? (
                        <Link href={route('dashboard')} className="text-2xl font-inter font-bold text-gold">Mon Espace</Link>
                    ) : (
                        <div className="flex flex-col gap-6">
                            <Link href={route('login')} className="text-2xl font-inter font-bold">Connexion</Link>
                            <Link href={route('register')} className="py-4 bg-white text-dark rounded-full font-inter font-bold text-xl">S'inscrire</Link>
                        </div>
                    )}
                </div>
            </div>
        </nav>
    );
}
