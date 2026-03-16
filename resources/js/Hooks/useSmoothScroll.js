import { useEffect } from 'react';

export const useSmoothScroll = () => {
    useEffect(() => {
        // Optimized smooth scroll using CSS scroll-behavior
        document.documentElement.style.scrollBehavior = 'smooth';
        
        // Remove any existing Lenis instance if present
        if (window.lenis) {
            window.lenis.destroy();
            delete window.lenis;
        }

        // Add performance optimizations
        const handleScroll = () => {
            requestAnimationFrame(() => {
                // Debounced scroll handling for better performance
                document.body.classList.add('scrolling');
                clearTimeout(document.body.scrollTimeout);
                document.body.scrollTimeout = setTimeout(() => {
                    document.body.classList.remove('scrolling');
                }, 150);
            });
        };

        window.addEventListener('scroll', handleScroll, { passive: true });

        return () => {
            document.documentElement.style.scrollBehavior = '';
            window.removeEventListener('scroll', handleScroll);
            if (document.body.scrollTimeout) {
                clearTimeout(document.body.scrollTimeout);
            }
        };
    }, []);
};
