import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { getDiscipline, getRankingsPreview } from "../api/disciplinesApi";
import { DisciplineSubMenu } from "../components/DisciplineSubMenu";
import { RankingsPreviewSection } from "../components/rankings/RankingsPreviewSection";
import { PostsSection } from "../components/posts/PostsSection";
import type {
    DisciplineData,
    DisciplineMeta,
    RankingsPreviewData,
    RankingsPreviewMeta,
} from "../types/api";

type PageState = {
    data: DisciplineData | null;
    meta: DisciplineMeta | null;
    rankingsPreview: RankingsPreviewData | null;
    rankingsPreviewMeta: RankingsPreviewMeta | null;
    loading: boolean;
    error: string | null;
};

export function DisciplinePage() {
    const { discipline } = useParams();

    const [state, setState] = useState<PageState>({
        data: null,
        meta: null,
        rankingsPreview: null,
        rankingsPreviewMeta: null,
        loading: true,
        error: null,
    });

    useEffect(() => {
        if (!discipline) return;

        let isMounted = true;

        Promise.all([
            getDiscipline(discipline),
            getRankingsPreview(discipline, 5),
        ])
            .then(([disciplineResponse, rankingsResponse]) => {
                if (!isMounted) return;

                setState({
                    data: disciplineResponse.data,
                    meta: disciplineResponse.meta,
                    rankingsPreview: rankingsResponse.data,
                    rankingsPreviewMeta: rankingsResponse.meta,
                    loading: false,
                    error: null,
                });
            })
            .catch(() => {
                if (!isMounted) return;

                setState({
                    data: null,
                    meta: null,
                    rankingsPreview: null,
                    rankingsPreviewMeta: null,
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

            <PostsSection posts={data.posts} />

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

            <RankingsPreviewSection
                rankingsPreview={state.rankingsPreview}
                rankingsPreviewMeta={state.rankingsPreviewMeta}
            />
        </main>
    );
}