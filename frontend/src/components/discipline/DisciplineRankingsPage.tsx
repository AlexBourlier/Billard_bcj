import { useOutletContext } from "react-router-dom";

import { RankingsPreviewSection } from "../rankings/RankingsPreviewSection";
import { CaramboleRankingsSection } from "../rankings/CaramboleRankingsSection";

import type { DisciplinePageContext } from "../../pages/DisciplinePage";
import { Helmet } from "react-helmet-async";

export function DisciplineRankingsPage() {
    const {
        rankingsPreview,
        rankingsPreviewMeta,
        caramboleRankingFiles,
    } = useOutletContext<DisciplinePageContext>();

    const hasCueScoreRankings = rankingsPreview !== null;
    const hasCaramboleRankings = caramboleRankingFiles.length > 0;

    if (!hasCueScoreRankings && !hasCaramboleRankings) {
        return <p>Aucun classement disponible pour cette discipline.</p>;
    }

    return (
        <>
            <Helmet>
                <title>
                    Classements - BCJ37 | Billard Club de Joué-lès-Tours
                </title>

                <meta
                    name="description"
                    content="Retrouvez tous les classements du BCJ37 : billard carambole et billard à la bande."
                />

                <meta
                    property="og:title"
                    content="Classements - BCJ37"
                />

                <meta
                    property="og:description"
                    content="Classements du club du BCJ37 : billard carambole et billard à la bande."
                />

                <meta property="og:type" content="website" />
            </Helmet>
            {hasCueScoreRankings && (
                <RankingsPreviewSection
                    rankingsPreview={rankingsPreview}
                    rankingsPreviewMeta={rankingsPreviewMeta}
                />
            )}

            {hasCaramboleRankings && (
                <CaramboleRankingsSection files={caramboleRankingFiles} />
            )}
        </>
    );
}