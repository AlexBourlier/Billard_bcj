import { NavLink } from "react-router-dom";

type DisciplineSubMenuProps = {
    rankingsEnabled: boolean;
};

export function DisciplineSubMenu({ rankingsEnabled }: DisciplineSubMenuProps) {
    return (
        <nav className="discipline-submenu" aria-label="Navigation discipline">
            <NavLink to="articles">Articles</NavLink>
            <NavLink to="calendrier">Calendrier</NavLink>

            {rankingsEnabled && (
                <NavLink to="classements">Classements</NavLink>
            )}

            <NavLink to="documents">Documents</NavLink>
        </nav>
    );
}