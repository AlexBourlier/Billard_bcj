import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import { getDiscipline, getRankingsPreview, getRankingsCarambole } from "../api/disciplinesApi";
import { DisciplineSubMenu } from "../components/DisciplineSubMenu";
import { RankingsPreviewSection } from "../components/rankings/RankingsPreviewSection";
import { CaramboleRankingsSection } from "../components/rankings/CaramboleRankingsSection";
import { PostsSection } from "../components/posts/PostsSection";
import { CalendarSection } from "../components/calendar/CalendarSection";
import { DocumentsSection } from "../components/documents/DocumentsSection";
import type {
    DisciplineData,
    DisciplineMeta,
    RankingsPreviewData,
    RankingsPreviewMeta,
    CaramboleRankingFile,
} from "../types/api";

type PageState = {
    data: DisciplineData | null;
    meta: DisciplineMeta | null;
    rankingsPreview: RankingsPreviewData | null;
    rankingsPreviewMeta: RankingsPreviewMeta | null;
    caramboleRankingFiles: CaramboleRankingFile[];
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
        caramboleRankingFiles: [],
        loading: true,
        error: null,
    });

    useEffect(() => {
        if (!discipline) return;

        let isMounted = true;

        Promise.all([
            getDiscipline(discipline),

            discipline === "carambole"
                ? getRankingsCarambole().catch(() => null)
                : Promise.resolve(null),

            discipline === "carambole"
                ? Promise.resolve(null)
                : getRankingsPreview(discipline, 5).catch(() => null),
        ])
            .then(([disciplineResponse, caramboleResponse, rankingsResponse]) => {
                if (!isMounted) return;

                setState({
                    data: disciplineResponse.data,
                    meta: disciplineResponse.meta,
                    rankingsPreview: rankingsResponse?.data ?? null,
                    rankingsPreviewMeta: rankingsResponse?.meta ?? null,
                    caramboleRankingFiles: caramboleResponse?.data ?? [],
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
                    caramboleRankingFiles: [],
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

    const hasCueScoreRankings = data.rankings !== null;
    const hasCaramboleRankings = state.caramboleRankingFiles.length > 0;
    const rankingsEnabled = hasCueScoreRankings || hasCaramboleRankings;

    return (
        <main>
            <h1>Discipline : {meta.discipline}</h1>

            <DisciplineSubMenu rankingsEnabled={rankingsEnabled} />

            <PostsSection posts={data.posts} discipline={meta.discipline} />

            <CalendarSection events={data.calendar} />

            <DocumentsSection documents={data.documents} />

            <section id="rankings">
                {state.rankingsPreview && (
                    <RankingsPreviewSection
                        rankingsPreview={state.rankingsPreview}
                        rankingsPreviewMeta={state.rankingsPreviewMeta}
                    />
                )}

                {state.caramboleRankingFiles.length > 0 && (
                    <CaramboleRankingsSection
                        files={state.caramboleRankingFiles}
                    />
                )}
            </section>
        </main>
    );
}