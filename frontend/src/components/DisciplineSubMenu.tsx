type DisciplineSubMenuProps = {
    rankingsEnabled: boolean;
};

export function DisciplineSubMenu({ rankingsEnabled }: DisciplineSubMenuProps) {
    return (
        <nav>
            <a href="#articles">Articles</a>{" "}
            {rankingsEnabled && <a href="#rankings">Classements</a>}{" "}
            <a href="#calendar">Calendrier</a>{" "}
            <a href="#documents">Documents</a>{" "}
        </nav>
    );
}