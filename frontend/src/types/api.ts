export type ApiError = {
    code: string;
    message: string;
};

export type ApiResponse<TData, TMeta = Record<string, unknown>> = {
    data: TData;
    meta: TMeta;
    links: unknown[] | Record<string, unknown>;
    error: ApiError | null;
};

export type PaginatedResponse<TData> = {
    data: TData[];
    meta: PaginationMeta;
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
    error: ApiError | null;
};

export type SiteSettings = {
    id: number;
    logo?: string | null;
    logo_url?: string | null;
    banniere?: string | null;
    banniere_url?: string | null;
    adresse?: string | null;
    telephone?: string | null;
    email?: string | null;
    youtube_page?: string | null;
    facebook_page?: string | null;
    facebook_page_id?: string | null;
    created_at?: string | null;
    updated_at?: string | null;
};

export type Menu = {
    id: number;
    nom?: string;
    name: string;
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

export type WelcomeMessage = {
    content: string | null;
};

export type Post = {
    id: number;
    title?: string;
    titre?: string;
    slug?: string;
    excerpt?: string | null;
    content?: string | null;
    image?: string | null;
    image_url?: string | null;
    video?:string | null;
    video_url?: string | null;
    year?: number | null;
    created_at?: string | null;
};

export type HomeData = {
    site_settings: SiteSettings | null;
    menus: Menu[];
    partners: Partner[];
    featured_post: Post | null;
    welcome_message: WelcomeMessage | null;
};

export type SiteData = {
    site_settings: SiteSettings | null;
    menus: Menu[];
};

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

export type CaramboleRankingFile = {
    name: string;
    filename: string;
    url: string;
};

export type DisciplineData = {
    posts: Post[];
    calendar: CalendarEvent[];
    documents: Document[];
    rankings: CueScoreRanking[] | null;
    carambole_ranking_files: CaramboleRankingFile[];
};

export type DisciplineMeta = {
    discipline: string;
    posts_count: number;
    calendar_count: number;
    documents_count: number;
    rankings_count: number | null;
    carambole_ranking_files_count: number;
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

export type PaginationMeta = {
    count: number;
    current_page: number;
    last_page: number;
    per_page: number;
    from: number | null;
    to: number | null;
    total: number;
    [key: string]: unknown;
};

export type Contact = {
    id: number;
    nom: string;
    email: string;
    telephone: string | null;
    message?: string | null;
};

export type ContactFormPayload = {
    name: string;
    email: string;
    message: string;
};