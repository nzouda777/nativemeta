import React, { useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import toast from 'react-hot-toast';

export default function FlashMessages() {
    const { flash } = usePage().props;

    useEffect(() => {
        if (flash.success) {
            toast.success(flash.success);
        }
        if (flash.error) {
            toast.error(flash.error);
        }
        if (flash.info) {
            toast.custom((t) => (
                <div className={`${t.visible ? 'animate-enter' : 'animate-leave'} max-w-md w-full bg-[#121214] border border-white/5 shadow-2xl rounded-2xl pointer-events-auto flex p-4 ring-1 ring-black ring-opacity-5`}>
                    <div className="flex-1 w-0 p-1">
                        <p className="text-sm font-medium text-white">
                            {flash.info}
                        </p>
                    </div>
                </div>
            ));
        }
    }, [flash]);

    return null;
}
