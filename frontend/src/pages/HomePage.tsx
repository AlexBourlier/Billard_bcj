import { useEffect, useState } from "react";
import { getHome } from "../api/publicApi";
import { Link } from "react-router-dom";
import type { HomeData } from "../types/api";
import { ClubMap } from "../components/map/ClubMap";
import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";

export function HomePage() {
    const [home, setHome] = useState<HomeData | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        getHome()
            .then((response) => setHome(response.data))
            .catch(() => setError("Erreur lors du chargement"))
            .finally(() => setLoading(false));
    }, []);

    if (loading) return <p>Chargement...</p>;
    if (error) return <p>{error}</p>;

    return (
        <>

        <section className="hero">
            {home?.site_settings?.banniere_url && (
                <img
                    src={home.site_settings.banniere_url}
                    alt="Bienvenue au BCJ37"
                    className="hero-banner"
                />
            )}
        </section>
        <div className="home-layout">
            <section className="accueil" aria-labelledby="accueil-title">
                <ArticleTitle>Accueil</ArticleTitle>
                {home?.welcome_message?.content && (
                    <ArticleCard className="home-section home-welcome">
                        <div
                            className="rich-text"
                            dangerouslySetInnerHTML={{
                                __html: home.welcome_message.content,
                            }}
                        />
                    </ArticleCard>
                )}
            </section>

            <section className="maps" aria-labelledby="maps-title">
                <ArticleTitle id="maps-title">Nous trouver</ArticleTitle>
                <ArticleCard className="home-section home-map">
                    <ClubMap />
                </ArticleCard>
            </section>

            <section className="news" aria-labelledby="news-title">
                <ArticleTitle>Actualité</ArticleTitle>
                <ArticleCard className="home-section home-featured-post">
                    {home?.featured_post ? (
                        <article>
                            <h3>
                                <Link to={`/posts/${home.featured_post.slug}`}>
                                    {home.featured_post.title ?? home.featured_post.titre}
                                </Link>
                            </h3>

                            {home.featured_post.image_url && (
                                <img
                                    src={home.featured_post.image_url}
                                    alt={home.featured_post.title ?? home.featured_post.titre}
                                    style={{ maxWidth: "100%" }}
                                />
                            )}

                            {home.featured_post.content && (
                                <div
                                    dangerouslySetInnerHTML={{
                                        __html: home.featured_post.content,
                                    }}
                                />
                            )}
                        </article>
                    ) : (
                        <p>Aucun article mis en avant</p>
                    )}
                </ArticleCard>
            </section>
            <section className="partners" aria-labelledby="partners-title">
                <ArticleTitle>Partenaires</ArticleTitle>
                <ArticleCard className="home-section home-partners">

                    {home?.partners.length ? (
                            <div className="partners-grid">
                                {home.partners.map((partner) => (
                                    <a
                                        key={partner.id}
                                        href={partner.website_url ?? "#"}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="partner-card"
                                    >
                                        {partner.logo_url && (
                                            <img
                                                src={partner.logo_url}
                                                alt={partner.name ?? partner.nom ?? "Partenaire"}
                                                className="partner-logo"
                                            />
                                        )}
                                    </a>
                                ))}
                            </div>
                    ) : (
                        <p>Aucun partenaire</p>
                    )}
                </ArticleCard>
            </section>
        </div>
        </>
    );
}