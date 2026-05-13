import { useOutletContext } from "react-router-dom";

import { RankingsPreviewSection } from "../rankings/RankingsPreviewSection";
import { CaramboleRankingsSection } from "../rankings/CaramboleRankingsSection";

import type { DisciplinePageContext } from "../../pages/DisciplinePage";

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