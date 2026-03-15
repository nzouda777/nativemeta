import React from 'react';
import { Link } from '@inertiajs/react';

export default function CourseCard({ course }) {
    return (
        <div
            className="glass-card !p-0 overflow-hidden flex flex-col h-full border-white/5 border hover:border-gold/30 hover:-translate-y-2 transition-all duration-500 group"
        >
            <div className="relative aspect-video overflow-hidden">
                <img
                    src={course.thumbnail}
                    alt={course.title}
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <div className="absolute top-4 left-4">
                    <span className="px-3 py-1 bg-dark/80 backdrop-blur-md border border-white/10 rounded-full text-[10px] uppercase tracking-[0.2em] font-bold text-gold">
                        {course.category?.name || 'Formation'}
                    </span>
                </div>
            </div>

            <div className="p-8 flex flex-col flex-grow relative">
                {/* Internal Card Glow */}
                <div className="absolute top-0 right-0 w-24 h-24 bg-gold/5 blur-3xl -z-10 group-hover:bg-gold/10 transition-colors"></div>

                <div className="flex justify-between items-center mb-6">
                    <span className="text-[10px] text-white/30 flex items-center gap-1 font-clash font-bold uppercase tracking-widest bg-white/5 px-2 py-1 rounded">
                        <svg className="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {course.duration_seconds ? Math.floor(course.duration_seconds / 3600) + 'h+' : 'Accès illimité'}
                    </span>
                    <span className="text-[10px] text-white/30 flex items-center gap-1 font-clash font-bold uppercase tracking-widest">
                        <svg className="w-3 h-3 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        {course.enrollments_count || 0}
                    </span>
                </div>

                <h3 className="text-2xl font-clash font-bold mb-4 line-clamp-2 group-hover:text-gold transition-colors duration-500">
                    {course.title}
                </h3>

                <p className="text-white/30 text-sm font-inter font-light line-clamp-2 mb-8 leading-relaxed">
                    {course.description}
                </p>

                <div className="mt-auto pt-8 border-t border-white/5 flex items-center justify-between">
                    <div>
                        {course.sale_price && (
                            <span className="text-[10px] text-white/60 line-through block mb-1">
                                {course.price}€
                            </span>
                        )}
                        <span className="text-3xl font-clash font-extrabold text-gold">
                            {course.sale_price || course.price}€
                        </span>
                    </div>
                    <Link
                        href={route('courses.show', course.slug)}
                        className="w-14 h-14 bg-white/5 text-white border border-white/10 rounded-full flex items-center justify-center group-hover:bg-gold group-hover:text-black group-hover:border-gold group-hover:scale-110 transition-all duration-700 shadow-2xl"
                    >
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={3} d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </Link>
                </div>
            </div>
        </div>
    );
}
