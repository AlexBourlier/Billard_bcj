import { apiGet } from './client';
import type { ApiResponse, HomeData, SiteData } from '../types/api';


export function getHome() {
    return apiGet<ApiResponse<HomeData>>("/public/home");
}

export function getSite() {
    return apiGet<ApiResponse<SiteData>>("/public/site");
}




