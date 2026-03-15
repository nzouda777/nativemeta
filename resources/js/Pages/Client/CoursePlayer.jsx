import React, { useState, useEffect } from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link, usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import axios from 'axios';

export default function CoursePlayer({ course, modules, currentLessonId: initialLessonId, progress: initialProgress }) {
    const [currentLessonId, setCurrentLessonId] = useState(initialLessonId);
    const [lessonData, setLessonData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [courseProgress, setCourseProgress] = useState(initialProgress);
    const [sidebarOpen, setSidebarOpen] = useState(true);

    useEffect(() => {
        loadLesson(currentLessonId);
    }, [currentLessonId]);

    const loadLesson = async (id) => {
        setLoading(true);
        try {
            const response = await axios.get(route('lesson.show', id));
            setLessonData(response.data.lesson);
            // Optionally handle local progress here too
        } catch (error) {
            console.error("Erreur lors du chargement de la leçon", error);
        } finally {
            setLoading(false);
        }
    };

    const handleProgress = async (isCompleted = true) => {
        try {
            const response = await axios.post(route('lesson.progress', currentLessonId), {
                is_completed: isCompleted
            });
            // Update UI state if needed
            // This is a simplified version - in a real app you might reload progress or use state
        } catch (error) {
            console.error("Erreur lors de la mise à jour de la progression");
        }
    };

    return (
        <div className="min-h-screen bg-dark text-white flex flex-col font-inter">
            <Head title={`Lecture : ${course.title}`} />

            {/* Player Header */}
            <header className="h-16 border-b border-white/5 bg-dark-lighter flex items-center justify-between px-6 z-50">
                <div className="flex items-center gap-6">
                    <Link href={route('dashboard')} className="p-2 hover:bg-white/5 rounded-lg transition-colors">
                        <svg className="w-5 h-5 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </Link>
                    <div className="h-6 w-px bg-white/10"></div>
                    <div>
                        <h1 className="text-sm font-inter font-bold truncate max-w-[200px] md:max-w-md">{course.title}</h1>
                        <p className="text-[10px] text-white/30 uppercase tracking-widest font-bold">NativeMeta Platform</p>
                    </div>
                </div>

                <div className="flex items-center gap-6">
                    <div className="hidden md:flex items-center gap-3">
                        <span className="text-[10px] font-bold text-white/40">{courseProgress}%</span>
                        <div className="w-32 h-1 bg-white/5 rounded-full overflow-hidden">
                            <div className="h-full bg-gold" style={{ width: `${courseProgress}%` }}></div>
                        </div>
                    </div>
                    <button
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                        className={`p-2 rounded-lg transition-colors ${sidebarOpen ? 'bg-gold/10 text-gold' : 'hover:bg-white/5 text-white/50'}`}
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </div>
            </header>

            <div className="flex-grow flex overflow-hidden">
                {/* Main Content Area */}
                <main className="flex-grow overflow-y-auto bg-dark p-6 lg:p-12">
                    <div className="max-w-5xl mx-auto space-y-12">
                        {loading ? (
                            <div className="aspect-video rounded-3xl bg-white/5 animate-pulse flex items-center justify-center">
                                <p className="text-white/20 font-bold uppercase tracking-widest">Chargement de la leçon...</p>
                            </div>
                        ) : (
                            <>
                                <div className="aspect-video rounded-3xl overflow-hidden glass-card p-0 shadow-2xl relative">
                                    {lessonData.type === 'video' ? (
                                        <iframe
                                            src={lessonData.content_url}
                                            className="w-full h-full"
                                            frameBorder="0"
                                            allow="autoplay; fullscreen; picture-in-picture"
                                            allowFullScreen
                                        ></iframe>
                                    ) : (
                                        <div className="w-full h-full flex flex-col items-center justify-center p-12 text-center">
                                            <span className="text-6xl mb-6">📄</span>
                                            <h3 className="text-2xl font-inter font-bold mb-4">Support de cours PDF</h3>
                                            <a href={lessonData.content_url} target="_blank" className="btn-gold">Télécharger le support</a>
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <div className="flex justify-between items-start mb-8">
                                        <div>
                                            <h2 className="text-3xl font-inter font-bold mb-2">{lessonData.title}</h2>
                                            <p className="text-white/50">{lessonData.duration} • Niveau Avancé</p>
                                        </div>
                                        <button
                                            onClick={() => handleProgress(true)}
                                            className="px-6 py-2 border border-gold/40 text-gold text-xs font-bold uppercase tracking-widest rounded-full hover:bg-gold hover:text-dark transition-all"
                                        >
                                            Marquer comme terminé
                                        </button>
                                    </div>
                                    {lessonData.content_text && (
                                        <div className="prose prose-invert max-w-none prose-p:text-white/60" dangerouslySetInnerHTML={{ __html: lessonData.content_text }} />
                                    )}
                                </div>
                            </>
                        )}
                    </div>
                </main>

                {/* Sidebar syllabus */}
                <AnimatePresence>
                    {sidebarOpen && (
                        <motion.aside
                            initial={{ width: 0, opacity: 0 }}
                            animate={{ width: 400, opacity: 1 }}
                            exit={{ width: 0, opacity: 0 }}
                            className="bg-dark-lighter border-l border-white/5 overflow-y-auto hidden md:block"
                        >
                            <div className="p-6">
                                <h3 className="text-lg font-inter font-bold mb-6">Sommaire</h3>
                                <div className="space-y-6">
                                    {modules.map((module, mIdx) => (
                                        <div key={module.id} className="space-y-3">
                                            <p className="text-[10px] text-white/30 uppercase font-black tracking-widest mb-4">Module {mIdx + 1} : {module.title}</p>
                                            {module.lessons.map(lesson => (
                                                <button
                                                    key={lesson.id}
                                                    onClick={() => setCurrentLessonId(lesson.id)}
                                                    className={`w-full text-left p-4 rounded-xl transition-all flex items-center justify-between group ${currentLessonId === lesson.id
                                                            ? 'bg-gold/10 border border-gold/20'
                                                            : 'hover:bg-white/5 border border-transparent'
                                                        }`}
                                                >
                                                    <div className="flex items-center gap-4">
                                                        <div className={`w-8 h-8 rounded-full flex items-center justify-center text-sm ${lesson.is_completed ? 'bg-accent/20 text-accent' : 'bg-white/5 text-white/30'
                                                            }`}>
                                                            {lesson.is_completed ? '✓' : (lesson.type === 'video' ? '🎬' : '📄')}
                                                        </div>
                                                        <span className={`text-xs font-bold transition-colors ${currentLessonId === lesson.id ? 'text-gold' : 'text-white/50 group-hover:text-white'
                                                            }`}>
                                                            {lesson.title}
                                                        </span>
                                                    </div>
                                                    <span className="text-[10px] text-white/20 font-mono italic">{lesson.duration}</span>
                                                </button>
                                            ))}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </motion.aside>
                    )}
                </AnimatePresence>
            </div>
        </div>
    );
}
// Note: Layout is NOT used here as we have a custom player interface
