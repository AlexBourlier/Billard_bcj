/**
 * Gestion du consentement aux cookies de mesure d'audience (Matomo).
 *
 * Le choix du visiteur est memorise dans le localStorage avec sa date. Il est
 * considere comme expire au-dela de ~6 mois (recommandation CNIL) : le bandeau
 * est alors re-affiche pour redemander le consentement.
 */
export type ConsentStatus = "accepted" | "refused";

const STORAGE_KEY = "bcj_cookie_consent";
const MAX_AGE_MS = 1000 * 60 * 60 * 24 * 30 * 6; // ~6 mois

/** Evenement emis a chaque changement de choix (accept / refuse). */
export const CONSENT_CHANGE_EVENT = "bcj:consent-change";
/** Evenement pour rouvrir le bandeau (lien « Gerer les cookies » du footer). */
export const OPEN_SETTINGS_EVENT = "bcj:open-cookie-settings";

type StoredConsent = {
    status: ConsentStatus;
    date: string;
};

/**
 * Retourne le choix de l'utilisateur, ou `null` s'il n'a pas encore choisi
 * (ou si son choix a expire) — auquel cas le bandeau doit etre affiche.
 */
export function getConsent(): ConsentStatus | null {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return null;

        const parsed = JSON.parse(raw) as StoredConsent;
        if (parsed?.status !== "accepted" && parsed?.status !== "refused") {
            return null;
        }
        if (!parsed.date || Date.now() - new Date(parsed.date).getTime() > MAX_AGE_MS) {
            return null;
        }
        return parsed.status;
    } catch {
        return null;
    }
}

/** Enregistre le choix et notifie l'application. */
export function setConsent(status: ConsentStatus): void {
    try {
        const value: StoredConsent = { status, date: new Date().toISOString() };
        localStorage.setItem(STORAGE_KEY, JSON.stringify(value));
    } catch {
        // localStorage indisponible (navigation privee stricte) : on ignore.
    }
    window.dispatchEvent(new CustomEvent(CONSENT_CHANGE_EVENT, { detail: status }));
}

/** Ouvre (ou rouvre) le bandeau de gestion des cookies. */
export function openCookieSettings(): void {
    window.dispatchEvent(new CustomEvent(OPEN_SETTINGS_EVENT));
}
