import React from 'react';
import LegalLayout from '@/Layouts/LegalLayout';

export default function MentionsLegales() {
    return (
        <LegalLayout title="Mentions Légales">
            <h2>1. Présentation du site</h2>
            <p>
                Le site NativeMeta est édité par la société NativeMeta SAS, au capital de 10 000€, immatriculée au RCS de Paris sous le numéro 123 456 789.
            </p>

            <h2>2. Hébergement</h2>
            <p>
                Le site est hébergé par DigitalOcean, dont le siège social est situé à New York, États-Unis.
            </p>

            <h2>3. Propriété intellectuelle</h2>
            <p>
                L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés.
            </p>
        </LegalLayout>
    );
}
