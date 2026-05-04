import { apiGet } from "./client";
import type { ApiResponse, PaginatedResponse, Post } from "../types/api";

export function getPostBySlug(slug: string) {
    return apiGet<ApiResponse<Post>>(`/posts/slug/${slug}`);
}

export function getAllPosts() {
    return apiGet<PaginatedResponse<Post>>(`/posts`);
}

export function getPostsByPeriod(period: string) {
    return apiGet<PaginatedResponse<Post>>(`/posts/period/${period}`);
}