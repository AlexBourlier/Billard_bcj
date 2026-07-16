import { useEffect, useState } from "react";
import type { Partner } from "../../types/api";
import "../../styles/partnersCarousel.css";

/**
 * En dessous de ce nombre de partenaires, le defilement automatique n'apporte
 * rien (la piste ne remplit pas l'ecran) : on affiche une rangee statique
 * centree. Au-dela, on active le carrousel infini.
 */
const MIN_FOR_SCROLL = 4;

function usePrefersReducedMotion(): boolean {
    // Valeur initiale lue de facon paresseuse : l'effet ne fait que s'abonner
    // aux changements, sans appeler setState de maniere synchrone a l'interieur
    // (ce qui declencherait un rendu en cascade).
    const [reduced, setReduced] = useState(
        () =>
            typeof window !== "undefined" &&
            window.matchMedia("(prefers-reduced-motion: reduce)").matches,
    );

    useEffect(() => {
        const query = window.matchMedia("(prefers-reduced-motion: reduce)");
        const onChange = () => setReduced(query.matches);
        query.addEventListener("change", onChange);
        return () => query.removeEventListener("change", onChange);
    }, []);

    return reduced;
}

type PartnerItemProps = {
    partner: Partner;
    /** Element visuel duplique pour la boucle : masque aux technologies d'assistance. */
    duplicated?: boolean;
};

function PartnerItem({ partner, duplicated = false }: PartnerItemProps) {
    const [imageFailed, setImageFailed] = useState(false);

    const label = partner.alt || partner.name || partner.nom || "Partenaire";
    const showImage = Boolean(partner.logo_url) && !imageFailed;

    const inner = showImage ? (
        <img
            className="partners-carousel__logo"
            src={partner.logo_url as string}
            alt={duplicated ? "" : label}
            loading="lazy"
            onError={() => setImageFailed(true)}
        />
    ) : (
        <span className="partners-carousel__fallback">{label}</span>
    );

    const hasLink = Boolean(partner.website_url);

    return (
        <li className="partners-carousel__item" aria-hidden={duplicated || undefined}>
            {hasLink ? (
                <a
                    className="partners-carousel__link"
                    href={partner.website_url as string}
                    target="_blank"
                    rel="noopener noreferrer"
                    tabIndex={duplicated ? -1 : undefined}
                    aria-label={duplicated ? undefined : `${label} (ouvre un nouvel onglet)`}
                >
                    {inner}
                </a>
            ) : (
                inner
            )}
        </li>
    );
}

type PartnersCarouselProps = {
    partners: Partner[];
};

/**
 * Carrousel infini et accessible des partenaires actifs.
 *
 * - Defilement continu par CSS (aucune dependance JS lourde).
 * - Boucle sans saut : la liste est dupliquee, la copie etant masquee aux
 *   technologies d'assistance (aria-hidden) et non focusable.
 * - Respecte prefers-reduced-motion et le faible nombre de partenaires en
 *   basculant sur une liste statique.
 */
export function PartnersCarousel({ partners }: PartnersCarouselProps) {
    const prefersReducedMotion = usePrefersReducedMotion();

    if (!partners.length) {
        return null;
    }

    const animate = partners.length >= MIN_FOR_SCROLL && !prefersReducedMotion;

    if (!animate) {
        return (
            <ul className="partners-carousel partners-carousel--static">
                {partners.map((partner) => (
                    <PartnerItem key={partner.id} partner={partner} />
                ))}
            </ul>
        );
    }

    return (
        <div className="partners-carousel" role="group" aria-label="Nos partenaires">
            <ul className="partners-carousel__track">
                {partners.map((partner) => (
                    <PartnerItem key={partner.id} partner={partner} />
                ))}
                {partners.map((partner) => (
                    <PartnerItem key={`dup-${partner.id}`} partner={partner} duplicated />
                ))}
            </ul>
        </div>
    );
}
