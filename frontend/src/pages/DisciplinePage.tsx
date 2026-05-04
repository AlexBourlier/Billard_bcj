import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { getDiscipline, getRankingsPreview } from "../api/disciplinesApi";
import { DisciplineSubMenu } from "../components/DisciplineSubMenu";
import { RankingsPreviewSection } from "../components/rankings/RankingsPreviewSection";
import { PostsSection } from "../components/posts/PostsSection";
import { CalendarSection } from "../components/calendar/CalendarSection";
import { DocumentsSection } from "../components/documents/DocumentsSection";
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

            <PostsSection posts={data.posts} discipline={meta.discipline} />

            <CalendarSection events={data.calendar} />

            <DocumentsSection documents={data.documents} />

            <RankingsPreviewSection
                rankingsPreview={state.rankingsPreview}
                rankingsPreviewMeta={state.rankingsPreviewMeta}
            />
        </main>
    );
}