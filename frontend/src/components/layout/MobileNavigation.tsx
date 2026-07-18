import { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";

type Props = {
    logoUrl: string | null;
};

const navLinks = [
    { to: "/club", label: "Le club" },
    { to: "/disciplines/blackball", label: "Blackball" },
    { to: "/disciplines/carambole", label: "Carambole" },
    { to: "/disciplines/snooker", label: "Snooker" },
    { to: "/disciplines/americain", label: "Américain" },
    { to: "/calendrier", label: "Calendrier" },
    { to: "/contact", label: "Contact" },
];

export function MobileNavigation({ logoUrl }: Props) {
    const [isOpen, setIsOpen] = useState(false);
    const menuRef = useRef<HTMLDivElement | null>(null);
    const linksRef = useRef<(HTMLAnchorElement | null)[]>([]);

    function closeMenu() {
        setIsOpen(false);
    }

    function toggleMenu() {
        setIsOpen((current) => {
            const next = !current;

            if (!current) {
                window.setTimeout(() => {
                    linksRef.current[0]?.focus();
                }, 0);
            }

            return next;
        });
    }

    useEffect(() => {
        if (!isOpen) return;

        function handleClickOutside(event: MouseEvent) {
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target as Node)
            ) {
                closeMenu();
            }
        }

        function handleKeyDown(event: KeyboardEvent) {
            const links = linksRef.current.filter(Boolean);

            if (event.key === "Escape") {
                closeMenu();
                return;
            }

            if (!links.length) return;

            const currentIndex = links.findIndex(
                (link) => link === document.activeElement
            );

            if (event.key === "ArrowDown") {
                event.preventDefault();

                const nextIndex =
                    currentIndex < links.length - 1 ? currentIndex + 1 : 0;

                links[nextIndex]?.focus();
            }

            if (event.key === "ArrowUp") {
                event.preventDefault();

                const previousIndex =
                    currentIndex > 0 ? currentIndex - 1 : links.length - 1;

                links[previousIndex]?.focus();
            }
        }

        document.addEventListener("mousedown", handleClickOutside);
        document.addEventListener("keydown", handleKeyDown);

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
            document.removeEventListener("keydown", handleKeyDown);
        };
    }, [isOpen]);

    return (
        <div className="mobile-nav-wrapper" ref={menuRef}>
            <div className="container mobile-nav-header">
                <h1 className="site-logo">
                    <Link to="/" onClick={closeMenu}>
                        {logoUrl ? (
                            <img
                                src={logoUrl}
                                alt="BCJ37 - Billard Club de Joué-lès-Tours"
                                className="site-logo__image"
                                width={706}
                                height={349}
                                decoding="async"
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
                    onClick={toggleMenu}
                >
                    <span className="burger" aria-hidden="true">
                        <span />
                        <span />
                        <span />
                    </span>
                </button>
            </div>

            <nav
                id="mobile-navigation"
                className={`mobile-nav ${isOpen ? "is-open" : ""}`}
                aria-label="Navigation principale mobile"
            >
                {navLinks.map((link, index) => (
                    <Link
                        key={link.to}
                        to={link.to}
                        onClick={closeMenu}
                        ref={(element) => {
                            linksRef.current[index] = element;
                        }}
                    >
                        {link.label}
                    </Link>
                ))}
            </nav>
        </div>
    );
}