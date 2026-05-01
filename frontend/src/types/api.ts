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
    nom?: string;
    name?: string;
    image?: string | null;
    image_url?: string | null;
    actif?: boolean;
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

export type Document = {
    id: number;
    title?: string;
    file?: string;
    file_url?: string;
};

export type CalendarEvent = {
    id: number;
    titre?: string;
    lieu?: string;
    club?: string | null;
    date_debut?: string;
    date_fin?: string;
    url?: string | null;
};

export type CueScoreRanking = {
    id: number;
    name: string;
    discipline: string;
    scope: string;
    ranking_type: string;
    team_category?: string | null;
    season?: string | null;
    is_active: boolean;
};

export type DisciplineData = {
    posts: Post[];
    calendar: CalendarEvent[];
    documents: Document[];
    rankings: CueScoreRanking[] | null;
};

export type DisciplineMeta = {
    discipline: string;
    posts_count: number;
    calendar_count: number;
    documents_count: number;
    rankings_count: number | null;
};

export type SiteData = {
    site_settings: Record<string, unknown> | null;
    menus: Menu[];
};