import React from 'react';

export const GlassCard = ({ children, className = '', hover = true }) => {
    return (
        <div
            className={`glass-card p-8 group transition-all duration-500 ${hover ? 'hover:-translate-y-2 hover:bg-white/[0.05]' : ''} ${className}`}
        >
            {children}
        </div>
    );
};
