import { useState, useRef, useEffect } from "react";
import { Link } from "react-router-dom";

type Props = {
    logoUrl: string | null;
};

export function MobileNavigation({ logoUrl }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const menuRef = useRef<HTMLDivElement | null>(null);

    useEffect(() => {
        function handleKeyDown(e: KeyboardEvent) {
            if (e.key === "Escape") {
                setIsOpen(false);
            }
        }

        if (isOpen) {
            document.addEventListener("keydown", handleKeyDown);
        }

        return () => {
            document.removeEventListener("keydown", handleKeyDown);
        };
    }, [isOpen]);

    useEffect(() => {
        function handleClickOutside(event: MouseEvent) {
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target as Node)
            ) {
                setIsOpen(false);
            }
        }

        if (isOpen) {
            document.addEventListener("mousedown", handleClickOutside);
        }

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [isOpen]);

    return (
        <div className="mobile-nav-wrapper" ref={menuRef}>
            <div className="container mobile-nav-header">
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

                <button
                    type="button"
                    className={`mobile-nav-toggle ${isOpen ? "is-open" : ""}`}
                    aria-expanded={isOpen}
                    aria-controls="mobile-navigation"
                    aria-label={isOpen ? "Fermer le menu" : "Ouvrir le menu"}
                    onClick={() => setIsOpen((current) => !current)}
                >
                    <span className="burger" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <nav
                id="mobile-navigation"
                className={`mobile-nav ${isOpen ? "is-open" : ""}`}
                aria-label="Navigation principale mobile"
            >
                <Link to="/club" onClick={() => setIsOpen(false)}>
                    Le club
                </Link>

                <Link to="/disciplines/blackball" onClick={() => setIsOpen(false)}>
                    Blackball
                </Link>

                <Link to="/disciplines/carambole" onClick={() => setIsOpen(false)}>
                    Carambole
                </Link>

                <Link to="/disciplines/snooker" onClick={() => setIsOpen(false)}>
                    Snooker
                </Link>

                <Link to="/disciplines/americain" onClick={() => setIsOpen(false)}>
                    Américain
                </Link>

                <Link to="/calendrier" onClick={() => setIsOpen(false)}>
                    Calendrier
                </Link>

                <Link to="/contact" onClick={() => setIsOpen(false)}>
                    Contact
                </Link>
            </nav>
        </div>
    );
}