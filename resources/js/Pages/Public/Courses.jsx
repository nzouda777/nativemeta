import React, { useState } from 'react';
import Layout from '@/Layouts/Layout';
import { Head, Link } from '@inertiajs/react';
import { SectionHeading } from '@/Components/UI/SectionHeading';
import CourseCard from '@/Components/CourseCard';

export default function Courses({ courses, categories }) {
    const [activeCategory, setActiveCategory] = useState('all');

    return (
        <Layout>
            <Head title="Catalogue des Formations" />

            <section className="py-32 animate-in">
                <div className="max-w-7xl mx-auto px-6">
                    <SectionHeading
                        subtitle="L'Académie"
                        title="DÉCOUVRE TON FUTUR."
                        description="Une sélection de formations d'élite conçues pour ceux qui ne se contentent pas de la moyenne."
                    />

                    {/* Filter Bar */}
                    <div className="flex flex-wrap gap-4 mb-24 reveal-section">
                        <button
                            onClick={() => setActiveCategory('all')}
                            className={`px-8 py-3 rounded-full font-clash font-bold text-xs uppercase tracking-widest transition-all border ${activeCategory === 'all' ? 'bg-white text-dark border-white' : 'bg-transparent text-white/30 border-white/10 hover:border-white/50'
                                }`}
                        >
                            Toutes les formations
                        </button>
                        {categories.map(cat => (
                            <button
                                key={cat.id}
                                onClick={() => setActiveCategory(cat.slug)}
                                className={`px-8 py-3 rounded-full font-clash font-bold text-xs uppercase tracking-widest transition-all border ${activeCategory === cat.slug ? 'bg-white text-dark border-white' : 'bg-transparent text-white/30 border-white/10 hover:border-white/50'
                                    }`}
                            >
                                {cat.name}
                            </button>
                        ))}
                    </div>

                    {/* Grid */}
                    <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-12">
                        {courses.data.map(course => (
                            <div key={course.id} className="reveal-section">
                                <CourseCard course={course} />
                            </div>
                        ))}
                    </div>

                    {/* Pagination */}
                    {courses.meta && courses.meta.last_page > 1 && (
                        <div className="mt-24 flex justify-center gap-4">
                            {/* Pagination items */}
                        </div>
                    )}
                </div>
            </section>
        </Layout>
    );
}
