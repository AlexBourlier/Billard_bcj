import { useEffect, useState } from "react";
import { getHome } from "../api/publicApi";
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

            {home?.featured_post ? (
                <article>
                    <h2>
                        {home.featured_post.title ??
                            home.featured_post.titre}
                    </h2>
                    <p>{home.featured_post.excerpt}</p>
                </article>
            ) : (
                <p>Aucun article mis en avant</p>
            )}
        </main>
    );
}