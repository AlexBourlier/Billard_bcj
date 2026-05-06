import { useEffect, useState } from "react";
import { getSite } from "../../api/publicApi";
import type { Menu, SiteSettings } from "../../types/api";
import { DesktopFooter } from "./DesktopFooter";
import { MobileFooter } from "./MobileFooter";

export function Footer() {
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
        <footer className="site-footer">
            <DesktopFooter menus={menus} logoUrl={logoUrl} />
            <MobileFooter logoUrl={logoUrl} />
        </footer>
    );
}