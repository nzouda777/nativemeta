import React, { useRef, useEffect } from 'react';
import { gsap } from 'gsap';

export const MagneticButton = ({ children, className = '', ...props }) => {
    const magneticRef = useRef(null);
    const innerRef = useRef(null);

    useEffect(() => {
        const ctx = gsap.context(() => {
            const xTo = gsap.quickTo(magneticRef.current, "x", { duration: 1, ease: "elastic.out(1, 0.3)" });
            const yTo = gsap.quickTo(magneticRef.current, "y", { duration: 1, ease: "elastic.out(1, 0.3)" });
            const xInnerTo = gsap.quickTo(innerRef.current, "x", { duration: 1, ease: "power3.out" });
            const yInnerTo = gsap.quickTo(innerRef.current, "y", { duration: 1, ease: "power3.out" });

            const mouseMove = (e) => {
                const { clientX, clientY } = e;
                const { width, height, left, top } = magneticRef.current.getBoundingClientRect();
                const x = clientX - (left + width / 2);
                const y = clientY - (top + height / 2);

                xTo(x * 0.3);
                yTo(y * 0.3);
                xInnerTo(x * 0.15);
                yInnerTo(y * 0.15);
            };

            const mouseLeave = () => {
                xTo(0);
                yTo(0);
                xInnerTo(0);
                yInnerTo(0);
            };

            const button = magneticRef.current;
            button.addEventListener("mousemove", mouseMove);
            button.addEventListener("mouseleave", mouseLeave);

            return () => {
                button.removeEventListener("mousemove", mouseMove);
                button.removeEventListener("mouseleave", mouseLeave);
            };
        });

        return () => ctx.revert();
    }, []);

    return (
        <div ref={magneticRef} className="inline-block cursor-pointer">
            <div ref={innerRef} className={`${className}`} {...props}>
                {children}
            </div>
        </div>
    );
};
