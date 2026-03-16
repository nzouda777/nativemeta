import React, { useEffect } from 'react';
import Navbar from '@/Components/Navbar';
import Footer from '@/Components/Footer';
import { useSmoothScroll } from '@/Hooks/useSmoothScroll';
import { Toaster } from 'react-hot-toast';
import FlashMessages from '@/Components/UI/FlashMessages';

export default function Layout({ children }) {
    useSmoothScroll();

    return (
        <div className="min-h-screen bg-dark overflow-x-hidden selection:bg-gold selection:text-dark">
            <Navbar />
            <FlashMessages />

            <main className="relative z-10 pt-24">
                {children}
            </main>

            {/* <Footer /> */}

            <Toaster
                position="bottom-right"
                toastOptions={{
                    style: {
                        background: '#121214',
                        color: '#fff',
                        border: '1px solid rgba(255,255,255,0.05)',
                        fontFamily: 'Inter',
                    },
                    success: {
                        iconTheme: {
                            primary: '#f59e0b',
                            secondary: '#fff',
                        },
                    },
                }}
            />

            {/* Atmospheric Background Elements - Uniform & Immersive */}
            <div className="fixed top-0 left-0 w-full h-full pointer-events-none -z-10 overflow-hidden">
                <div className="absolute top-[-20%] left-[-20%] w-[70vw] h-[70vw] bg-gold/10 blur-[150px] rounded-full opacity-60"></div>
                <div className="absolute bottom-[-20%] right-[-20%] w-[60vw] h-[60vw] bg-violet-600/10 blur-[150px] rounded-full opacity-60"></div>
                <div className="absolute top-[20%] right-[-10%] w-[40vw] h-[40vw] bg-indigo-600/10 blur-[150px] rounded-full opacity-40"></div>

                {/* Subtle Grain / Texture layer for depth */}
                <div className="absolute inset-0 opacity-[0.03] mix-blend-overlay bg-[url('https://grainy-gradients.vercel.app/noise.svg')] pointer-events-none"></div>
            </div>
        </div>
    );
}
