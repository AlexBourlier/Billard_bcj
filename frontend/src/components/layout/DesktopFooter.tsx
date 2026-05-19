import { Link, NavLink } from "react-router-dom";
import type { Menu } from "../../types/api";
// import { getMenuPath } from "./navigationUtils";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";

import {
    faFacebook,
} from "@awesome.me/kit-c0df283285/icons/classic/brands";

import {
    faEnvelope,
    faLocationDot,
} from "@awesome.me/kit-c0df283285/icons/classic/solid";

type Props = {
    menus: Menu[];
    logoUrl: string | null;
};

export function DesktopFooter({ logoUrl }: Props) {
    return (
        <div className="desktop-footer-wrapper">
            <div className="container desktop-footer">
                <h3 className="site-logo-desktop">
                    <NavLink end to="/">
                        {logoUrl ? (
                            <img
                                src={logoUrl}
                                alt="BCJ37 - Billard Club de Joué-lès-Tours"
                                className="site-logo__image"
                            />
                        ) : (
                            <span>BCJ37</span>
                        )}
                    </NavLink>
                </h3>

                <div className="social-links-footer">
                    <p className="contact-info">Contact</p>
                    <a
                        href="https://www.facebook.com/people/Billard-Club-de-Joué-Lès-Tours/61573797213739/?locale=fr_FR"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <FontAwesomeIcon icon={faFacebook} aria-hidden="true" />
                        <span className="sr-only">Facebook</span>
                        Billard Club de Joué-lès-Tours
                    </a>

                    <NavLink to="/contact" rel="noopener noreferrer">
                        <FontAwesomeIcon icon={faEnvelope} aria-hidden="true" />
                        <span className="sr-only">Contact</span>
                        contact@bcj37.fr
                    </NavLink>

                    <address className="contact-address">
                        <FontAwesomeIcon icon={faLocationDot} aria-hidden="true" />
                        <span className="sr-only">Adresse</span>
                        28 Rue Joseph Cugnot, 37300 Joué-Lès-Tours
                    </address>  
                    <p className="footer-note">Affilié à la <a
                        href="https://www.ffbillard.com/"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <span className="sr-only">Fédération Française de Billard</span>
                        Fédération Française de Billard
                    </a></p>
                </div>

                <nav aria-label="footer-secondaire-desktop">
                    <Link to="/club">
                        Le club
                    </Link>

                    <Link to="/disciplines/blackball">
                        Blackball
                    </Link>

                    <Link to="/disciplines/carambole">
                        Carambole
                    </Link>

                    <Link to="/disciplines/snooker">
                        Snooker
                    </Link>

                    <Link to="/disciplines/americain">
                        Américain
                    </Link>
                </nav>
            </div>
            <div className="footer-bottom">
                <p>&copy; {new Date().getFullYear()} BCJ37 - Billard Club de Joué-lès-Tours. Tous droits réservés. - <Link to="/cgu" className="footer-link">CGU</Link> - <Link to="/mentions-legales" className="footer-link">Mentions légales</Link> - <Link to="/politique-confidentialite" className="footer-link">Politique de confidentialité</Link></p>
                
            </div>
        </div>
    );
}