import { Link } from "react-router-dom";
import type { Menu } from "../../types/api";
import { getMenuPath } from "./navigationUtils";

type Props = {
    menus: Menu[];
    logoUrl: string | null;
};

export function DesktopNavigation({ menus, logoUrl }: Props) {
    return (
        <div className="desktop-nav-wrapper">
            <div className="container desktop-nav">
                <h1 className="site-logo">
                    <Link to="/">
                        {logoUrl ? (
                            <img
                                src={logoUrl}
                                alt="BCJ37 - Billard Club de Joué-lès-Tours"
                                className="site-logo__image"
                            />
                        ) : (
                            <span>BCJ37</span>
                        )}
                    </Link>
                </h1>

                <nav aria-label="Navigation principale desktop">
                    {menus.map((menu) => (
                        <Link key={menu.id} to={getMenuPath(menu.name)}>
                            {menu.name}{' '}
                        </Link>
                    ))}
                </nav>
            </div>
        </div>
    );
}