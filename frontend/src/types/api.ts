export type ApiResponse<TData, TMeta = Record<string, unknown>> = {
    data: TData;
    meta: TMeta;
    links: unknown[] | Record<string, unknown>;
    error: null | {
        code: string;
        message: string;
    };
};

export type Menu = {
    id: number;
    name?: string;
    nom?: string;
    image_url?: string | null;
};

export type Partner = {
    id: number;
    name?: string;
    nom?: string;
    logo_url?: string | null;
    website_url?: string | null;
};

export type Post = {
    id: number;
    title?: string;
    titre?: string;
    slug?: string;
    excerpt?: string | null;
    content?: string | null;
    contenu?: string | null;
    image_url?: string | null;
    created_at?: string | null;
};

export type HomeData = {
    site_settings: Record<string, unknown> | null;
    menus: Menu[];
    partners: Partner[];
    featured_post: Post | null;
}