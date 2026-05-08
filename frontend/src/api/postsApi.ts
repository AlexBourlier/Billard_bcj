import { apiGet } from "./client";
import type { ApiResponse, PaginatedResponse, Post } from "../types/api";

export function getPostBySlug(slug: string) {
    return apiGet<ApiResponse<Post>>(`/posts/slug/${slug}`);
}

export function getAllPosts(page = 1) {
    return apiGet<PaginatedResponse<Post>>(`/posts?page=${page}`);
}

export function getPostsByPeriod(period: string, page = 1) {
    return apiGet<PaginatedResponse<Post>>(
        `/posts/period/${period}?page=${page}`,
    );
}