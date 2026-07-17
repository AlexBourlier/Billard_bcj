export type ClubPeriod = {
    label: string;
    value: string | null;
};

/**
 * Périodes d'archives du club, calculées par décennie autour de l'année
 * courante. Isolé du composant `ClubArchiveNav` pour que ce dernier n'exporte
 * qu'un composant (compatibilité Fast Refresh).
 */
export function getClubPeriods(
    currentYear = new Date().getFullYear(),
): ClubPeriod[] {
    const currentDecade = Math.floor(currentYear / 10) * 10;

    return [
        {
            label: `Depuis ${currentDecade}`,
            value: `depuis_${currentDecade}`,
        },
        {
            label: `${currentDecade - 10} - ${currentDecade - 1}`,
            value: `${currentDecade - 10}_${currentDecade - 1}`,
        },
        {
            label: `${currentDecade - 20} - ${currentDecade - 11}`,
            value: `${currentDecade - 20}_${currentDecade - 11}`,
        },
        {
            label: `Avant ${currentDecade - 20}`,
            value: `avant_${currentDecade - 20}`,
        },
    ];
}
