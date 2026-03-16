import React from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { MagneticButton } from '@/Components/UI/MagneticButton';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <Layout>
            <Head title="Connexion - NativeMeta" />

            <section className="min-h-[90vh] flex items-center justify-center py-20 animate-in">
                <div className="w-full max-w-xl px-6">
                    <div className="text-center mb-16">
                        <span className="text-gold font-inter text-xs uppercase tracking-[0.4em] font-bold block mb-6">Accès Membre</span>
                        <h1 className="text-2xl md:text-3xl font-inter font-bold tracking-tighter mb-6 underline decoration-white/10 underline-offset-8">
                            CONTENT DE TE <span className="text-white/20">VOIR.</span>
                        </h1>
                        <p className="text-white/40 text-lg font-inter font-light">
                            Entre tes accès pour retrouver ton arsenal.
                        </p>
                    </div>

                    <div className="glass-card !px-6 py-12 md:!p-8 border-white/5 shadow-lg relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-32 h-32 bg-gold/5 blur-3xl -z-10"></div>

                        <form onSubmit={handleSubmit} className="space-y-10">
                            <div className="space-y-8">
                                <div className="space-y-4">
                                    <label className="block text-[10px] font-inter font-bold uppercase tracking-[0.3em] text-white/30">Ton Email</label>
                                    <input
                                        type="email"
                                        value={data.email}
                                        onChange={e => setData('email', e.target.value)}
                                        className="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 focus:border-gold outline-none transition-all text-white placeholder:text-white/10"
                                        placeholder="empire@elite.com"
                                        required
                                    />
                                    {errors.email && <p className="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest">{errors.email}</p>}
                                </div>

                                <div className="space-y-4">
                                    <div className="flex justify-between items-center">
                                        <label className="block text-[10px] font-inter font-bold uppercase tracking-[0.3em] text-white/30">Mot de passe</label>
                                        <Link href="#" className="text-[10px] text-gold/40 hover:text-gold transition-colors font-bold uppercase tracking-widest">Oublié ?</Link>
                                    </div>
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

                                <div className="flex items-center gap-3">
                                    <input
                                        type="checkbox"
                                        checked={data.remember}
                                        onChange={e => setData('remember', e.target.checked)}
                                        className="w-5 h-5 rounded border-white/10 bg-white/5 text-gold focus:ring-gold transition-all"
                                    />
                                    <span className="text-[10px] text-white/30 font-inter font-bold uppercase tracking-widest">Rester connecté</span>
                                </div>
                            </div>

                            <MagneticButton className="w-full">
                                <button
                                    disabled={processing}
                                    className="btn-gold w-full !py-4  text-base px-8  shadow-[0_30px_60px_-15px_rgba(255,255,255,0.1)] active:scale-95 inline-flex items-center justify-center gap-4"
                                >
                                    {processing ? 'Identification...' : 'Ouvrir mon espace →'}
                                </button>
                            </MagneticButton>
                        </form>
                    </div>

                    <p className="text-center text-[10px] text-white/20 mt-12 font-inter uppercase tracking-widest leading-loose">
                        Pas encore de compte ? <br />
                        Nos accès sont réservés aux acquéreurs de nos formations. <br />
                        {/* <Link href={route('courses.index')} className="text-gold font-black mt-2 inline-block border-b border-gold/20 pb-1">Découvrir le catalogue</Link> */}
                    </p>
                </div>
            </section>
        </Layout>
    );
}
