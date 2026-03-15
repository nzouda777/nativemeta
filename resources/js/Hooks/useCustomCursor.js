import { useEffect, useState } from 'react';
import { gsap } from 'gsap';

export const useCustomCursor = () => {
    useEffect(() => {
        const cursor = document.getElementById('custom-cursor');
        const follower = document.getElementById('custom-cursor-follower');

        if (!cursor || !follower) return;

        const onMouseMove = (e) => {
            gsap.to(cursor, {
                x: e.clientX,
                y: e.clientY,
                duration: 0.1,
            });
            gsap.to(follower, {
                x: e.clientX,
                y: e.clientY,
                duration: 0.3,
            });
        };

        const onMouseDown = () => {
            gsap.to([cursor, follower], { scale: 0.8, duration: 0.2 });
        };

        const onMouseUp = () => {
            gsap.to([cursor, follower], { scale: 1, duration: 0.2 });
        };

        const onMouseEnterLink = () => {
            gsap.to(cursor, { scale: 2, backgroundColor: 'rgba(245, 158, 11, 0.5)', duration: 0.3 });
            gsap.to(follower, { scale: 1.5, borderColor: 'rgba(245, 158, 11, 1)', duration: 0.3 });
        };

        const onMouseLeaveLink = () => {
            gsap.to(cursor, { scale: 1, backgroundColor: '#F59E0B', duration: 0.3 });
            gsap.to(follower, { scale: 1, borderColor: 'rgba(245, 158, 11, 0.3)', duration: 0.3 });
        };

        window.addEventListener('mousemove', onMouseMove);
        window.addEventListener('mousedown', onMouseDown);
        window.addEventListener('mouseup', onMouseUp);

        const links = document.querySelectorAll('a, button, .clickable');
        links.forEach(link => {
            link.addEventListener('mouseenter', onMouseEnterLink);
            link.addEventListener('mouseleave', onMouseLeaveLink);
        });

        return () => {
            window.removeEventListener('mousemove', onMouseMove);
            window.removeEventListener('mousedown', onMouseDown);
            window.removeEventListener('mouseup', onMouseUp);
            links.forEach(link => {
                link.removeEventListener('mouseenter', onMouseEnterLink);
                link.removeEventListener('mouseleave', onMouseLeaveLink);
            });
        };
    }, []);
};
