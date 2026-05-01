import { apiGet } from "./client";
import type { ApiResponse, DisciplineData, DisciplineMeta } from "../types/api";

export function getDiscipline(slug: string) {
    return apiGet<ApiResponse<DisciplineData, DisciplineMeta>>(
        `/disciplines/${slug}`
    );
}