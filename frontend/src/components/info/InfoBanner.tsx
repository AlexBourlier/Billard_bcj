import type { InfoBlock } from "../../types/api";
import "../../styles/infoBanner.css";

const LEVEL_CLASS: Record<string, string> = {
    info: "info-banner--info",
    important: "info-banner--important",
    urgent: "info-banner--urgent",
};

type InfoBannerProps = {
    blocks: InfoBlock[];
};

/**
 * Blocs d'information importants (fermeture, tournoi, info urgente...) affiches
 * en haut de la page d'accueil. Le niveau pilote la couleur ; les blocs urgents
 * sont annonces aux lecteurs d'ecran (role="alert").
 */
export function InfoBanner({ blocks }: InfoBannerProps) {
    if (!blocks?.length) {
        return null;
    }

    return (
        <section className="info-banners" aria-label="Informations importantes">
            {blocks.map((block) => {
                const isExternal = Boolean(block.lien && /^https?:\/\//i.test(block.lien));

                return (
                    <div
                        key={block.id}
                        className={`info-banner ${LEVEL_CLASS[block.niveau] ?? LEVEL_CLASS.info}`}
                        role={block.niveau === "urgent" ? "alert" : "status"}
                    >
                        <div className="info-banner__body">
                            <strong className="info-banner__title">{block.titre}</strong>
                            {block.resume && (
                                <span className="info-banner__text">{block.resume}</span>
                            )}
                        </div>

                        {block.lien && (
                            <a
                                className="info-banner__link"
                                href={block.lien}
                                {...(isExternal
                                    ? { target: "_blank", rel: "noopener noreferrer" }
                                    : {})}
                            >
                                En savoir plus
                            </a>
                        )}
                    </div>
                );
            })}
        </section>
    );
}
