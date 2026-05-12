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

    const hasRankings = rankingsPreview || caramboleRankingFiles.length > 0;

    if (!hasRankings) {
        return <p>Aucun classement disponible pour cette discipline.</p>;
    }

    return (
        <>
            {rankingsPreview && (
                <RankingsPreviewSection
                    rankingsPreview={rankingsPreview}
                    rankingsPreviewMeta={rankingsPreviewMeta}
                />
            )}

            {caramboleRankingFiles.length > 0 && (
                <CaramboleRankingsSection files={caramboleRankingFiles} />
            )}
        </>
    );
}