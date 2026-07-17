import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { getConsent, setConsent, OPEN_SETTINGS_EVENT } from "../../lib/consent";
import { initMatomo, trackPageView } from "../../lib/matomo";
import "../../styles/cookieBanner.css";

/**
 * Bandeau de consentement aux cookies de mesure d'audience (opt-in).
 *
 * - Affiche uniquement si aucun choix valide n'est memorise.
 * - « Accepter » active Matomo immediatement et enregistre la vue courante.
 * - « Refuser » n'active rien.
 * - Reouvrable depuis le pied de page (« Gerer les cookies »).
 */
export function CookieBanner() {
    // Etat initial calcule une fois (lecture du choix memorise) : le bandeau
    // s'affiche si aucun choix valide n'est enregistre.
    const [visible, setVisible] = useState(() => getConsent() === null);

    useEffect(() => {
        const open = () => setVisible(true);
        window.addEventListener(OPEN_SETTINGS_EVENT, open);
        return () => window.removeEventListener(OPEN_SETTINGS_EVENT, open);
    }, []);

    if (!visible) return null;

    const accept = () => {
        setConsent("accepted");
        initMatomo();
        trackPageView(window.location.pathname + window.location.search, document.title);
        setVisible(false);
    };

    const refuse = () => {
        setConsent("refused");
        setVisible(false);
    };

    return (
        <div
            className="cookie-banner"
            role="dialog"
            aria-label="Gestion des cookies"
            aria-live="polite"
        >
            <div className="cookie-banner__text">
                <strong className="cookie-banner__title">Cookies &amp; mesure d'audience</strong>
                <p>
                    Nous utilisons Matomo pour mesurer l'audience du site, dans le respect de
                    votre vie privée. Aucun cookie de mesure n'est déposé sans votre accord.
                    Détails dans notre{" "}
                    <Link to="/politique-confidentialite" className="cookie-banner__link">
                        politique de confidentialité
                    </Link>
                    .
                </p>
            </div>
            <div className="cookie-banner__actions">
                <button
                    type="button"
                    className="cookie-banner__btn cookie-banner__btn--refuse"
                    onClick={refuse}
                >
                    Refuser
                </button>
                <button
                    type="button"
                    className="cookie-banner__btn cookie-banner__btn--accept"
                    onClick={accept}
                >
                    Accepter
                </button>
            </div>
        </div>
    );
}
