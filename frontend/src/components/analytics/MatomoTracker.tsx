import { useEffect } from "react";
import { useLocation } from "react-router-dom";
import { trackPageView } from "../../lib/matomo";

/**
 * Envoie une vue de page a Matomo a chaque changement de route (application
 * SPA). N'a aucun effet tant que le visiteur n'a pas accepte la mesure
 * d'audience (voir lib/matomo). Ne rend rien.
 */
export function MatomoTracker() {
    const location = useLocation();

    useEffect(() => {
        // Le titre est mis a jour par react-helmet ; on laisse un court delai
        // pour capturer le bon titre de page.
        const id = window.setTimeout(() => {
            trackPageView(location.pathname + location.search, document.title);
        }, 0);
        return () => window.clearTimeout(id);
    }, [location]);

    return null;
}
