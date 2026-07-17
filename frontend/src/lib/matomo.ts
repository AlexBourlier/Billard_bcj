/**
 * Integration Matomo, conditionnee au consentement (opt-in).
 *
 * Le script Matomo n'est charge et aucune vue n'est envoyee tant que le
 * visiteur n'a pas clique « Accepter ». L'URL et l'identifiant du site sont
 * fournis au build par les variables VITE_MATOMO_URL et VITE_MATOMO_SITE_ID :
 * si elles sont absentes, la mesure d'audience est simplement desactivee.
 */
import { getConsent } from "./consent";

const MATOMO_URL = import.meta.env.VITE_MATOMO_URL as string | undefined;
const MATOMO_SITE_ID = import.meta.env.VITE_MATOMO_SITE_ID as string | undefined;

declare global {
    interface Window {
        _paq?: unknown[][];
    }
}

let scriptLoaded = false;

function isConfigured(): boolean {
    return Boolean(MATOMO_URL && MATOMO_SITE_ID);
}

function baseUrl(): string {
    const url = MATOMO_URL as string;
    return url.endsWith("/") ? url : `${url}/`;
}

/**
 * Charge le script Matomo une seule fois (apres consentement).
 * IP anonymisee cote serveur Matomo recommandee ; ici on active le suivi des
 * liens et on desactive volontairement les cookies persistants inutiles.
 */
export function initMatomo(): void {
    if (scriptLoaded || !isConfigured() || typeof window === "undefined") return;

    const url = baseUrl();
    window._paq = window._paq || [];
    window._paq.push(["enableLinkTracking"]);
    window._paq.push(["setTrackerUrl", `${url}matomo.php`]);
    window._paq.push(["setSiteId", MATOMO_SITE_ID as string]);

    const script = document.createElement("script");
    script.async = true;
    script.src = `${url}matomo.js`;
    document.head.appendChild(script);

    scriptLoaded = true;
}

/**
 * Envoie une vue de page a Matomo — uniquement si le consentement est donne.
 * A appeler a chaque changement de route.
 */
export function trackPageView(path: string, title?: string): void {
    if (getConsent() !== "accepted" || !isConfigured() || typeof window === "undefined") {
        return;
    }

    initMatomo();
    window._paq = window._paq || [];
    if (title) {
        window._paq.push(["setDocumentTitle", title]);
    }
    window._paq.push(["setCustomUrl", path]);
    window._paq.push(["trackPageView"]);
}
