import { apiGet } from "./client";
import type { ApiResponse, Post } from "../types/api";

export function getPostBySlug(slug: string) {
    return apiGet<ApiResponse<Post>>(`/posts/slug/${slug}`);
}