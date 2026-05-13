import { NavLink } from "react-router-dom";

type DisciplineSubMenuProps = {
    rankingsEnabled: boolean;
};

type DisciplineMenuItem = {
    label: string;
    path: string;
    enabled?: boolean;
};

export function DisciplineSubMenu({ rankingsEnabled }: DisciplineSubMenuProps) {
    const items: DisciplineMenuItem[] = [
        {
            label: "Articles",
            path: "articles",
        },
        {
            label: "Calendrier",
            path: "calendrier",
        },
        {
            label: "Classements",
            path: "classements",
            enabled: rankingsEnabled,
        },
        {
            label: "Documents",
            path: "documents",
        },
    ];

    return (
        <nav
            className="discipline-submenu club-archive-nav"
            aria-label="Navigation discipline"
        >
            <div className="club-archive-nav__inner">
                {items
                    .filter((item) => item.enabled !== false)
                    .map((item) => (
                        <NavLink
                            key={item.path}
                            to={item.path}
                            className={({ isActive }) =>
                                isActive
                                    ? "club-archive-nav__link club-archive-nav__link--active"
                                    : "club-archive-nav__link"
                            }
                            aria-label={item.label}
                        >
                            {item.label}
                        </NavLink>
                    ))}
            </div>
        </nav>
    );
}