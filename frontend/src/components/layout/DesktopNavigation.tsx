import { NavLink } from "react-router-dom";
import type { Menu } from "../../types/api";
import { getMenuPath } from "./navigationUtils";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import {
    faFacebook,
    faYoutube,
} from "@awesome.me/kit-c0df283285/icons/classic/brands";

import { 
    faCalendarAlt, 
    faEnvelope, 
} from "@awesome.me/kit-c0df283285/icons/classic/solid";

type Props = {
    menus: Menu[];
    logoUrl: string | null;
};

export function DesktopNavigation({ menus, logoUrl }: Props) {
    return (
        <div className="desktop-nav-wrapper">
            <div className="container desktop-nav">
                <NavLink end to="/">
                    {logoUrl ? (
                        <img
                            src={logoUrl}
                            alt="BCJ37 - Billard Club de Joué-lès-Tours"
                            className="site-logo-desktop site-logo__image"
                        />
                    ) : (
                        <span>BCJ37</span>
                    )}
                </NavLink>
                <nav aria-label="navigation-principale-desktop">
                    {menus.map((menu) => (
                        <NavLink
                            key={menu.id}
                            to={getMenuPath(menu.name)}
                            className={({ isActive }) =>
                                isActive ? "is-active" : ""
                            }
                        >
                            {menu.image_url ? (
                                <img
                                    src={menu.image_url}
                                    alt={menu.name}
                                    className="menu-image"
                                />
                            ) : (
                                menu.name
                            )}
                        </NavLink>
                    ))}
                </nav>
                <div className="social-links">
                    <NavLink to="/calendrier" rel="noopener noreferrer">
                        <FontAwesomeIcon icon={faCalendarAlt} aria-hidden="true" />
                        <span className="sr-only">Calendrier</span>
                    </NavLink>

                    <a
                        href="https://www.facebook.com/people/Billard-Club-de-Joué-Lès-Tours/61573797213739/?locale=fr_FR"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <FontAwesomeIcon icon={faFacebook} aria-hidden="true" />
                        <span className="sr-only">Facebook</span>
                    </a>

                    <a 
                        href="https://www.youtube.com/@BCJ37" 
                        target="_blank" 
                        rel="noopener noreferrer">

                        <FontAwesomeIcon icon={faYoutube} aria-hidden="true" />
                        <span className="sr-only">YouTube</span>
                    </a>

                    <NavLink to="/contact" rel="noopener noreferrer">
                        <FontAwesomeIcon icon={faEnvelope} aria-hidden="true" />
                        <span className="sr-only">Contact</span>
                    </NavLink>
                </div>
            </div>
        </div>
    );
}