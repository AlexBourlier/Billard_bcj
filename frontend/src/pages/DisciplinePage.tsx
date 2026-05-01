import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { getDiscipline } from "../api/disciplinesApi";
import { DisciplineSubMenu } from "../components/DisciplineSubMenu";
import type { DisciplineData, DisciplineMeta } from "../types/api";

type PageState = {
    data: DisciplineData | null;
    meta: DisciplineMeta | null;
    loading: boolean;
    error: string | null;
};

export function DisciplinePage() {
    const { discipline } = useParams();

    const [state, setState] = useState<PageState>({
        data: null,
        meta: null,
        loading: true,
        error: null,
    });

    useEffect(() => {
        if (!discipline) {
            return;
        }

        let isMounted = true;

        getDiscipline(discipline)
            .then((response) => {
                if (!isMounted) return;

                setState({
                    data: response.data,
                    meta: response.meta,
                    loading: false,
                    error: null,
                });
            })
            .catch(() => {
                if (!isMounted) return;

                setState({
                    data: null,
                    meta: null,
                    loading: false,
                    error: "Impossible de charger la discipline.",
                });
            });

        return () => {
            isMounted = false;
        };
    }, [discipline]);

    if (!discipline) return <p>Discipline introuvable.</p>;
    if (state.loading) return <p>Chargement...</p>;
    if (state.error) return <p>{state.error}</p>;

    if (!state.data || !state.meta) {
        return <p>Aucune donnée disponible.</p>;
    }

    const { data, meta } = state;

    return (
        <main>
            <h1>Discipline : {meta.discipline}</h1>

            <DisciplineSubMenu rankingsEnabled={data.rankings !== null} />

            <section id="articles">
                <h2>Articles</h2>

                {data.posts.length > 0 ? (
                    data.posts.map((post) => (
                        <article key={post.id}>
                            <h3>{post.title ?? post.titre}</h3>
                            {post.excerpt && <p>{post.excerpt}</p>}
                        </article>
                    ))
                ) : (
                    <p>Aucun article pour cette discipline.</p>
                )}
            </section>

            <section id="calendar">
                <h2>Calendrier</h2>

                {data.calendar.length > 0 ? (
                    data.calendar.map((event) => (
                        <article key={event.id}>
                            <h3>{event.titre}</h3>
                            <p>{event.lieu}</p>
                            {event.date_debut && (
                                <p>
                                    Date :{" "}
                                    {new Date(event.date_debut).toLocaleDateString("fr-FR")}
                                </p>
                            )}
                        </article>
                    ))
                ) : (
                    <p>Aucun événement à afficher.</p>
                )}
            </section>

            <section id="documents">
                <h2>Documents</h2>

                {data.documents.length > 0 ? (
                    <ul>
                        {data.documents.map((document) => (
                            <li key={document.id}>
                                {document.file_url ? (
                                    <a href={document.file_url} target="_blank" rel="noreferrer">
                                        {document.title}
                                    </a>
                                ) : (
                                    document.title
                                )}
                            </li>
                        ))}
                    </ul>
                ) : (
                    <p>Aucun document disponible.</p>
                )}
            </section>

            <section id="rankings">
                <h2>Classements</h2>

                {data.rankings && data.rankings.length > 0 ? (
                    <ul>
                        {data.rankings.map((ranking) => (
                            <li key={ranking.id}>
                                {ranking.name} — {ranking.scope} — {ranking.ranking_type}
                            </li>
                        ))}
                    </ul>
                ) : (
                    <p>Aucun classement disponible.</p>
                )}
            </section>
        </main>
    );
}