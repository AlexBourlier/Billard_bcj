import { useEffect, useState } from "react";
import { Outlet, useParams } from "react-router-dom";

import {
    getDiscipline,
    getRankingsPreview,
    getRankingsCarambole,
} from "../api/disciplinesApi";

import { ApiError } from "../api/client";
import { ErrorPage } from "./ErrorPage";
import { NotFoundPage } from "./NotFoundPage";

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
    error: boolean;
    notFound: boolean;
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
        error: false,
        notFound: false,
    });

    useEffect(() => {
        if (!discipline) return;

        let isMounted = true;

        setState((previousState) => ({
            ...previousState,
            loading: true,
            error: false,
            notFound: false,
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
                    error: false,
                    notFound: false,
                });
            })
            .catch((error) => {
                if (!isMounted) return;

                if (error instanceof ApiError && error.status === 404) {
                    setState({
                        data: null,
                        meta: null,
                        rankingsPreview: null,
                        rankingsPreviewMeta: null,
                        caramboleRankingFiles: [],
                        loading: false,
                        error: false,
                        notFound: true,
                    });

                    return;
                }

                setState({
                    data: null,
                    meta: null,
                    rankingsPreview: null,
                    rankingsPreviewMeta: null,
                    caramboleRankingFiles: [],
                    loading: false,
                    error: true,
                    notFound: false,
                });
            });

        return () => {
            isMounted = false;
        };
    }, [discipline]);

    if (!discipline) return <NotFoundPage />;
    if (state.loading) return <p>Chargement...</p>;
    if (state.notFound) return <NotFoundPage />;

    if (state.error) {
        return (
            <ErrorPage
                code="500"
                title="Erreur serveur"
                message="Impossible de charger la discipline demandée."
            />
        );
    }

    if (!state.data || !state.meta) return <NotFoundPage />;

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