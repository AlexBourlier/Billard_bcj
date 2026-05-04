import { apiGet } from "./client";
import type { 
    ApiResponse, 
    DisciplineData, 
    DisciplineMeta,
    RankingsPreviewData,
    RankingsPreviewMeta, 
    CaramboleRankingFile
} from "../types/api";

export function getDiscipline(slug: string) {
    return apiGet<ApiResponse<DisciplineData, DisciplineMeta>>(
        `/disciplines/${slug}`
    );
}

export function getRankingsCarambole() {
    return apiGet<ApiResponse<{ files: CaramboleRankingFile[] }>>(
        `/disciplines/carambole/classement`
    );
}

export function getRankingsPreview(slug: string, limit = 5) {
    return apiGet<ApiResponse<RankingsPreviewData, RankingsPreviewMeta>>(
        `/disciplines/${slug}/rankings-preview?limit=${limit}`
    );
}