import React from 'react';

export const SectionHeading = ({ subtitle, title, description, centered = false }) => {
    return (
        <div className={`md:mb-16 mb-8 ${centered ? 'text-center' : ''} animate-in`}>
            <span className="text-white/20 font-inter text-xs uppercase tracking-[0.4em] font-bold block mb-6">
                {subtitle}
            </span>
            <h2 className="text-2xl md:text-4xl font-inter font-bold mb-4 leading-[1.1] tracking-tighter">
                {title}
            </h2>
            {description && (
                <p className="text-white/40 text-base max-w-2xl font-inter font-light leading-relaxed">
                    {description}
                </p>
            )}
        </div>
    );
};
