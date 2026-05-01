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

export type RankingEntry = {
    rank_position?: number | null;
    participant_name?: string | null;
    participant_external_id?: string | null;
    participant_url?: string | null;
    team_name?: string | null;
    team_external_id?: string | null;
    team_url?: string | null;
    points?: string | number | null;
    played?: number | null;
    wins?: number | null;
    losses?: number | null;
    ties?: number | null;
    is_club_team?: boolean;
    licencie?: {
        id: number;
        licence?: string | null;
        nom?: string | null;
        prenom?: string | null;
    } | null;
};

export type RankingPreviewItem = {
    ranking: CueScoreRanking;
    entries: RankingEntry[];
    meta: Record<string, unknown>;
};

export type RankingsPreviewData = Record<string, RankingPreviewItem[]> | null;

export type RankingsPreviewMeta = {
    discipline: string;
    count?: number;
    limit?: number;
    rankings_supported: boolean;
};