import { Link } from "react-router-dom";

export type ClubPeriod = {
    label: string;
    value: string | null;
};

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

type ClubArchiveNavProps = {
    periods: ClubPeriod[];
    activePeriod: string | null;
};

export default function ClubArchiveNav({
    periods,
    activePeriod,
}: ClubArchiveNavProps) {
    return (
        <nav
            className="club-archive-nav"
            aria-label="Archives du club"
        >
            <div className="club-archive-nav__inner">
                {periods.map((item) => {
                    const isActive = item.value === activePeriod;

                    return (
                        <Link
                            key={item.value}
                            to={`/club/annee/${item.value}`}
                            className={
                                isActive
                                    ? "club-archive-nav__link club-archive-nav__link--active"
                                    : "club-archive-nav__link"
                            }
                            aria-current={isActive ? "page" : undefined}
                        >
                            {item.label}
                        </Link>
                    );
                })}
            </div>
        </nav>
    );
}