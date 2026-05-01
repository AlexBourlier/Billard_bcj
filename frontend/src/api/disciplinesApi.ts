import { apiGet } from "./client";
import type { ApiResponse } from "../types/api";

export function getDisciplines(slug: string) {
    return apiGet<ApiResponse<unknown>>(`disciplines/${slug}`);
}

export function getRankingsPreview(slug: string, limit = 5) {
    return apiGet<ApiResponse<unknown>>(
        `disciplines/${slug}/rankings?limit=${limit}`
    );
}