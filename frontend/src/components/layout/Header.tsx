import { useEffect, useState } from "react";
import { getSite } from "../../api/publicApi";
import type { Menu, SiteSettings } from "../../types/api";
import { DesktopNavigation } from "./DesktopNavigation";
import { MobileNavigation } from "./MobileNavigation";

export function Header() {
    const [siteSettings, setSiteSettings] = useState<SiteSettings | null>(null);
    const [menus, setMenus] = useState<Menu[]>([]);

    useEffect(() => {
        getSite()
            .then((res) => {
                setMenus(res.data.menus ?? []);
                setSiteSettings(res.data.site_settings ?? null);
            })
            .catch((err) => {
                console.error("Erreur chargement site:", err);
            });
    }, []);

    const logoUrl = siteSettings?.logo_url ?? null;

    return (
        <header className="site-header">
            <DesktopNavigation menus={menus} logoUrl={logoUrl} />
            <MobileNavigation menus={menus} logoUrl={logoUrl} />
        </header>
    );
}