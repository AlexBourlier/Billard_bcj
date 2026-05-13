import { useEffect, useState } from "react";
import { Outlet, useParams } from "react-router-dom";

import {
    getDiscipline,
    getRankingsPreview,
    getRankingsCarambole,
} from "../api/disciplinesApi";

import { DisciplineSubMenu } from "../components/DisciplineSubMenu";

import type {
    DisciplineData,
    DisciplineMeta,
    RankingsPreviewData,
    RankingsPreviewMeta,
    CaramboleRankingFile,
} from "../types/api";

export type DisciplinePageContext = {
    data: DisciplineData;
    meta: DisciplineMeta;
    rankingsPreview: RankingsPreviewData | null;
    rankingsPreviewMeta: RankingsPreviewMeta | null;
    caramboleRankingFiles: CaramboleRankingFile[];
};

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

        setState((previousState) => ({
            ...previousState,
            loading: true,
            error: null,
        }));

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

                const caramboleRankingFiles = Array.isArray(caramboleResponse?.data)
                    ? caramboleResponse.data
                    : disciplineResponse.data.carambole_ranking_files ?? [];

                setState({
                    data: disciplineResponse.data,
                    meta: disciplineResponse.meta,
                    rankingsPreview: rankingsResponse?.data ?? null,
                    rankingsPreviewMeta: rankingsResponse?.meta ?? null,
                    caramboleRankingFiles,
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

    const hasCueScoreRankings =
        Array.isArray(state.data.rankings) && state.data.rankings.length > 0;

    const hasCaramboleRankings = state.caramboleRankingFiles.length > 0;

    const rankingsEnabled = hasCueScoreRankings || hasCaramboleRankings;

    return (
        <div>
            <DisciplineSubMenu rankingsEnabled={rankingsEnabled} />

            <Outlet
                context={{
                    data: state.data,
                    meta: state.meta,
                    rankingsPreview: state.rankingsPreview,
                    rankingsPreviewMeta: state.rankingsPreviewMeta,
                    caramboleRankingFiles: state.caramboleRankingFiles,
                }}
            />
        </div>
    );
}