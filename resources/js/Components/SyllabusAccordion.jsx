import React, { useState } from 'react';

export default function SyllabusAccordion({ modules }) {
    const [openModule, setOpenModule] = useState(0);

    return (
        <div className="space-y-6">
            {modules.map((module, idx) => (
                <div key={module.id} className="bg-white/[0.02] border border-white/5 rounded-[2rem] overflow-hidden transition-all duration-500 hover:bg-white/[0.04]">
                    <button
                        onClick={() => setOpenModule(openModule === idx ? null : idx)}
                        className="w-full px-4 py-4 flex items-center justify-between text-left transition-colors"
                    >
                        <div className="flex items-start gap-6">
                            <span className="text-white/10 font-inter text-xl font-bold">{(idx + 1).toString().padStart(2, '0')}</span>
                            <div>
                                <h3 className="text-lg md:text-xl font-inter font-bold tracking-tight">{module.title}</h3>
                                <p className="text-[10px] uppercase tracking-widest text-white/30 font-bold mt-2">{module.lessons.length} LEÇONS D'EXPERTISE</p>
                            </div>
                        </div>
                        <div className={`md:w-12 md:h-12 w-10 h-10 rounded-full border border-white/10 inline-flex items-center justify-center transition-all duration-500 ${openModule === idx ? 'bg-white text-dark rotate-180' : ''}`}>
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>

                    <div className={`transition-all duration-500 ease-in-out overflow-hidden ${openModule === idx ? 'max-h-[1000px] opacity-100' : 'max-h-0 opacity-0'}`}>
                        <div className="px-10 pb-10 space-y-4">
                            {module.lessons.map(lesson => (
                                <div key={lesson.id} className="flex items-center justify-between py-5 border-t border-white/5 group">
                                    <div className="flex items-center gap-6">
                                        <div className="md:w-10 md:h-10 w-6 h-6 rounded-xl bg-white/5 flex items-center justify-center text-lg">
                                            {lesson.type === 'video' ? '🎬' : '📄'}
                                        </div>
                                        <div>
                                            <span className="text-sm font-inter font-bold text-white/60 group-hover:text-white transition-colors block">
                                                {lesson.title}
                                            </span>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-6">
                                        <span className="text-[10px] text-white/20 font-bold uppercase tracking-widest">{lesson.duration}</span>
                                        {lesson.is_preview && (
                                            <span className="px-3 py-1 bg-white text-dark text-[8px] font-bold uppercase tracking-widest rounded-full">Aperçu</span>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}
