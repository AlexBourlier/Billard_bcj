import { useEffect, useState } from "react";
import { getSite } from "../api/publicApi";
import { Link } from "react-router-dom";
import type { Menu } from "../types/api";

export function MainLayout({ children }: { children: React.ReactNode }) {
    const [menus, setMenus] = useState<Menu[]>([]);

    useEffect(() => {
        getSite().then((res) => {
            setMenus(res.data.menus);
        });
    }, []);

    return (
        <>
            <header>
                <h1>
                    <Link to="/">BCJ37</Link>
                </h1>

                <nav>
                    {menus.map((menu) => {
                        const slug = menu.name?.toLowerCase();

                        const path =
                            slug === "club"
                                ? "/club"
                                : `/disciplines/${slug}`;

                        return (
                            <Link key={menu.id} to={path}>
                                {menu.name}{' '}
                            </Link>
                        );
                    })}
                </nav>
            </header>

            <main>{children}</main>

            <footer>© BCJ37</footer>
        </>
    );
}