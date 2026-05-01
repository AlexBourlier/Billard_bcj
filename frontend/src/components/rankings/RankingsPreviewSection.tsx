import type { RankingsPreviewData, RankingsPreviewMeta } from "../../types/api";

type RankingsPreviewSectionProps = {
    rankingsPreview: RankingsPreviewData | null;
    rankingsPreviewMeta: RankingsPreviewMeta | null;
};

/**
 * Affiche le preview des classements CueScore d'une discipline.
 *
 * Responsabilités :
 * - respecter l'ordre métier des scopes
 * - gérer le cas des disciplines sans classement
 * - afficher les classements sous forme de tableaux
 *
 * Ordre d'affichage :
 * 1. National
 * 2. Régional
 * 3. Départemental
 */
export function RankingsPreviewSection({
    rankingsPreview,
    rankingsPreviewMeta,
}: RankingsPreviewSectionProps) {
    const normalizeScope = (value: string) =>
        value
            .normalize("NFD")
            .replace(/\p{Diacritic}/gu, "")
            .toLowerCase();

    const scopeLabels: Record<string, string> = {
        national: "National",
        regional: "Régional",
        departemental: "Départemental",
    };

    const orderedScopes = ["national", "regional", "departemental"];

    const rankingsByNormalizedScope = Object.fromEntries(
        Object.entries(rankingsPreview ?? {}).map(([scope, items]) => [
            normalizeScope(scope),
            items,
        ])
    );

    if (rankingsPreviewMeta?.rankings_supported === false) {
        return (
            <section id="rankings">
                <h2>Classements</h2>
                <p>Les classements CueScore ne sont pas supportés pour cette discipline.</p>
            </section>
        );
    }

    if (!rankingsPreview || Object.keys(rankingsPreview).length === 0) {
        return (
            <section id="rankings">
                <h2>Classements</h2>
                <p>Aucun classement disponible.</p>
            </section>
        );
    }

    return (
        <section id="rankings">
            <h2>Classements</h2>

            {orderedScopes.map((scopeKey) => {
                const items = rankingsByNormalizedScope[scopeKey];

                if (!items || items.length === 0) {
                    return null;
                }

                return (
                    <div key={scopeKey}>
                        <h3>{scopeLabels[scopeKey]}</h3>

                        {items.map((item) => (
                            <article key={item.ranking.id}>
                                <h4>{item.ranking.name}</h4>

                                {item.entries.length > 0 ? (
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Position</th>
                                                <th>Nom</th>
                                                <th>Points</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            {item.entries.map((entry, index) => {
                                                const position = entry.rank_position ?? index + 1;

                                                return (
                                                    <tr key={`${item.ranking.id}-${index}`}>
                                                        <td>
                                                            <strong>
                                                                {position === 1
                                                                    ? `${position} er`
                                                                    : `${position} ème`}
                                                            </strong>
                                                        </td>

                                                        <td>
                                                            {entry.participant_name ??
                                                                entry.team_name ??
                                                                "Nom indisponible"}
                                                        </td>

                                                        <td>
                                                            {entry.points !== null &&
                                                                entry.points !== undefined
                                                                ? Math.round(Number(entry.points))
                                                                : "-"}
                                                                {" points"}
                                                        </td>
                                                    </tr>
                                                );
                                            })}
                                        </tbody>
                                    </table>
                                ) : (
                                    <p>Aucune entrée disponible.</p>
                                )}
                            </article>
                        ))}
                    </div>
                );
            })}
        </section>
    );
}