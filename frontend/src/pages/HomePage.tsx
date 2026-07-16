import { useEffect, useState } from "react";
import { getHome } from "../api/publicApi";
import { Link } from "react-router-dom";
import type { HomeData } from "../types/api";
import { ClubMap } from "../components/map/ClubMap";
import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";
import { PartnersCarousel } from "../components/partners/PartnersCarousel";
import { Helmet } from "react-helmet-async";
import { ErrorPage } from "./ErrorPage";

export function HomePage() {
    const [home, setHome] = useState<HomeData | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(false);

    useEffect(() => {
        getHome()
            .then((response) => setHome(response.data))
            .catch(() => setError(true))
            .finally(() => setLoading(false));
    }, []);

    if (loading) return <p>Chargement...</p>;
    if (error) {
        return (
            <ErrorPage
                code="500"
                title="Erreur de chargement"
                message="Impossible de récupérer les données de la page d’accueil."
            />
        );
    }

    return (
        <>
            <Helmet>
                <title>BCJ37 - Billard Club de Joué-lès-Tours</title>

                <meta
                    name="description"
                    content="Club de billard à Joué-lès-Tours : blackball, carambole, snooker, compétitions et actualités."
                />
            </Helmet>

        <div className="hero">
            {home?.site_settings?.banniere_url && (
                <img
                    src={home.site_settings.banniere_url}
                    alt="Bienvenue au BCJ37"
                    className="hero-banner"
                />
            )}
        </div>
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

                                {home.featured_post.video_url ? (
                                    <iframe
                                        width="100%"
                                        height="600"
                                        src={home.featured_post.video_url}
                                        title={home.featured_post.title ?? home.featured_post.titre}
                                        frameBorder="0"
                                        allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowFullScreen
                                    />
                                ) : home.featured_post.image_url ? (
                                    <img
                                        src={home.featured_post.image_url}
                                        alt={home.featured_post.title ?? home.featured_post.titre}
                                        style={{ maxWidth: "100%" }}
                                    />
                                ) : null}

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
                        <PartnersCarousel partners={home.partners} />
                    ) : (
                        <p>Aucun partenaire</p>
                    )}
                </ArticleCard>
            </section>
        </div>
        </>
    );
}