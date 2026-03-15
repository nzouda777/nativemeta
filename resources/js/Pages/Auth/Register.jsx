import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { MagneticButton } from '@/Components/UI/MagneticButton';

export default function Register({ token, email }) {
    const { data, setData, post, processing, errors } = useForm({
        token: token,
        name: '',
        email: email, // Read-only from invitation
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('register'));
    };

    return (
        <Layout>
            <Head title="Finaliser l'inscription - NativeMeta" />

            <section className="min-h-[90vh] flex items-center justify-center py-20 animate-in">
                <div className="w-full max-w-xl px-6">
                    <div className="text-center mb-16">
                        <span className="text-gold font-inter text-xs uppercase tracking-[0.4em] font-bold block mb-6">Onboarding Élite</span>
                        <h1 className="text-4xl md:text-6xl font-inter font-bold tracking-tighter mb-6 underline decoration-white/10 underline-offset-8">
                            CRÉE TON <span className="text-white/20">PROFIL.</span>
                        </h1>
                        <p className="text-white/40 text-lg font-inter font-light">
                            Dernière étape avant d'accéder à l'arsenal.
                        </p>
                    </div>

                    <div className="glass-card !p-12 md:!p-16 border-white/5 shadow-2xl relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-32 h-32 bg-gold/5 blur-3xl -z-10"></div>

                        <form onSubmit={handleSubmit} className="space-y-10">
                            <div className="grid gap-10">
                                <div className="space-y-4">
                                    <label className="block text-[10px] font-inter font-bold uppercase tracking-[0.3em] text-white/30">Email d'activation (non modifiable)</label>
                                    <input
                                        type="email"
                                        value={data.email}
                                        disabled
                                        className="w-full bg-white/[0.02] border border-white/5 rounded-2xl px-6 py-4 opacity-40 cursor-not-allowed text-white/50 font-inter"
                                    />
                                </div>

                                <div className="space-y-4">
                                    <label className="block text-[10px] font-inter font-bold uppercase tracking-[0.3em] text-white/30">Ton Nom Complet</label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:border-gold outline-none transition-all text-white placeholder:text-white/10"
                                        placeholder="Ex: Marc Aurèle"
                                        required
                                    />
                                    {errors.name && <p className="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest">{errors.name}</p>}
                                </div>

                                <div className="grid md:grid-cols-2 gap-8">
                                    <div className="space-y-4">
                                        <label className="block text-[10px] font-inter font-bold uppercase tracking-[0.3em] text-white/30">Mot de passe</label>
                                        <input
                                            type="password"
                                            value={data.password}
                                            onChange={e => setData('password', e.target.value)}
                                            className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:border-gold outline-none transition-all text-white placeholder:text-white/10"
                                            placeholder="••••••••"
                                            required
                                        />
                                        {errors.password && <p className="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest">{errors.password}</p>}
                                    </div>

                                    <div className="space-y-4">
                                        <label className="block text-[10px] font-inter font-bold uppercase tracking-[0.3em] text-white/30">Confirmation</label>
                                        <input
                                            type="password"
                                            value={data.password_confirmation}
                                            onChange={e => setData('password_confirmation', e.target.value)}
                                            className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:border-gold outline-none transition-all text-white placeholder:text-white/10"
                                            placeholder="••••••••"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <MagneticButton className="w-full">
                                <button
                                    disabled={processing}
                                    className="btn-gold w-full !py-6 text-xl shadow-[0_30px_60px_-15px_rgba(255,255,255,0.1)] active:scale-95"
                                >
                                    {processing ? 'Initialisation...' : 'Prendre mes accès →'}
                                </button>
                            </MagneticButton>
                        </form>
                    </div>

                    <p className="text-center text-[10px] text-white/20 mt-12 font-inter uppercase tracking-widest">
                        En finalisant ton profil, tu acceptes nos conditions générales d'utilisation.
                    </p>
                </div>
            </section>
        </Layout>
    );
}
