import { useEffect, useState } from "react";
import { getHome } from "../api/publicApi";
import { Link } from "react-router-dom";
import type { HomeData } from "../types/api";

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
        <main>
            {/* <h1>BCJ37</h1> */}

            {home?.welcome_message?.content && (
                <div
                    dangerouslySetInnerHTML={{
                        __html: home.welcome_message.content,
                    }}
                />
            )}
            {home?.featured_post ? (
                <article>
                    <h2>
                        <Link to={`/posts/${home.featured_post.slug}`}>
                            {home.featured_post.title ?? home.featured_post.titre}
                        </Link>
                    </h2>
                    {home.featured_post.image_url && (
                        <img src={home.featured_post.image_url} alt={home.featured_post.title ?? home.featured_post.titre} style={{ maxWidth: "100%" }} />
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

            {home?.partners.length ? (
                <section>
                    <h2>Partenaires</h2>
                    <ul>
                        {home.partners.map((partner) => (
                            <li key={partner.id}>
                                {partner.name ?? partner.nom}
                                {partner.logo_url && (
                                    <img
                                        src={partner.logo_url}
                                        alt={partner.name ?? partner.nom}
                                        style={{ maxWidth: "100px" }}
                                    />
                                )}
                            </li>
                        ))}
                    </ul>
                </section>
            ) : (
                <p>Aucun partenaire</p>
            )}
        </main>
    );
}