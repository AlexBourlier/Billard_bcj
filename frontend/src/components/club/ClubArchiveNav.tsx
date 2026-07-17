import { Link } from "react-router-dom";
import type { ClubPeriod } from "./clubPeriods";

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