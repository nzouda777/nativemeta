import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { GlassCard } from '@/Components/UI/GlassCard';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import { MagneticButton } from '@/Components/UI/MagneticButton';
import toast from 'react-hot-toast';

export default function Profile({ user }) {
    const { data, setData, post, processing, errors } = useForm({
        _method: 'PUT',
        name: user.name,
        email: user.email,
        avatar: null,
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('profile.update'), {
            onSuccess: () => {
                toast.success('Profil mis à jour');
                setData('password', '');
                setData('current_password', '');
                setData('password_confirmation', '');
            },
            forceFormData: true,
        });
    };

    return (
        <Layout>
            <Head title="Mon Profil" />

            <section className="py-20">
                <div className="max-w-4xl mx-auto px-6">
                    <SectionHeading
                        subtitle="Paramètres"
                        title="Gestion du Profil"
                        description="Mets à jour tes informations personnelles et ta sécurité."
                    />

                    <form onSubmit={handleSubmit} className="space-y-8">
                        {/* Civilite & Photo */}
                        <GlassCard className="!p-10">
                            <h3 className="text-xl font-inter font-bold mb-8">Informations Personnelles</h3>

                            <div className="flex flex-col md:flex-row items-center gap-10 mb-10">
                                <div className="relative group">
                                    <div className="w-32 h-32 rounded-3xl bg-white/5 border border-white/10 overflow-hidden">
                                        <img
                                            src={user.avatar || `https://ui-avatars.com/api/?name=${user.name}&background=F59E0B&color=fff`}
                                            alt={user.name}
                                            className="w-full h-full object-cover"
                                        />
                                    </div>
                                    <label className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center cursor-pointer transition-opacity rounded-3xl">
                                        <span className="text-[10px] uppercase font-bold tracking-widest">Changer</span>
                                        <input type="file" className="hidden" onChange={e => setData('avatar', e.target.files[0])} />
                                    </label>
                                </div>

                                <div className="flex-grow grid md:grid-cols-2 gap-6 w-full">
                                    <div>
                                        <label className="block text-[10px] font-syne font-bold uppercase tracking-widest text-white/40 mb-2">Nom complet</label>
                                        <input
                                            type="text"
                                            value={data.name}
                                            onChange={e => setData('name', e.target.value)}
                                            className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-gold/50 transition-colors"
                                        />
                                        {errors.name && <p className="text-red-400 text-[10px] mt-1">{errors.name}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-syne font-bold uppercase tracking-widest text-white/40 mb-2">Email</label>
                                        <input
                                            type="email"
                                            value={data.email}
                                            onChange={e => setData('email', e.target.value)}
                                            className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-gold/50 transition-colors"
                                        />
                                        {errors.email && <p className="text-red-400 text-[10px] mt-1">{errors.email}</p>}
                                    </div>
                                </div>
                            </div>
                        </GlassCard>

                        {/* Security */}
                        <GlassCard className="!p-10 border-red-500/10">
                            <h3 className="text-xl font-inter font-bold mb-8">Sécurité</h3>

                            <div className="space-y-6">
                                <div>
                                    <label className="block text-[10px] font-syne font-bold uppercase tracking-widest text-white/40 mb-2">Mot de passe actuel</label>
                                    <input
                                        type="password"
                                        value={data.current_password}
                                        onChange={e => setData('current_password', e.target.value)}
                                        className="w-full md:w-1/2 bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-gold/50 transition-colors"
                                        placeholder="••••••••"
                                    />
                                    {errors.current_password && <p className="text-red-400 text-[10px] mt-1">{errors.current_password}</p>}
                                </div>

                                <div className="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label className="block text-[10px] font-syne font-bold uppercase tracking-widest text-white/40 mb-2">Nouveau mot de passe</label>
                                        <input
                                            type="password"
                                            value={data.password}
                                            onChange={e => setData('password', e.target.value)}
                                            className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-gold/50 transition-colors"
                                            placeholder="••••••••"
                                        />
                                        {errors.password && <p className="text-red-400 text-[10px] mt-1">{errors.password}</p>}
                                    </div>
                                    <div>
                                        <label className="block text-[10px] font-syne font-bold uppercase tracking-widest text-white/40 mb-2">Confirmer le mot de passe</label>
                                        <input
                                            type="password"
                                            value={data.password_confirmation}
                                            onChange={e => setData('password_confirmation', e.target.value)}
                                            className="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-gold/50 transition-colors"
                                            placeholder="••••••••"
                                        />
                                    </div>
                                </div>
                            </div>
                        </GlassCard>

                        <div className="flex justify-end">
                            <MagneticButton>
                                <button
                                    disabled={processing}
                                    className="btn-gold !px-12"
                                >
                                    {processing ? 'Enregistrement...' : 'Sauvegarder les modifications'}
                                </button>
                            </MagneticButton>
                        </div>
                    </form>
                </div>
            </section>
        </Layout>
    );
}
