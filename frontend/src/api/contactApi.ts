import { apiPost } from "./client";
import type { ApiResponse, ContactFormPayload } from "../types/api";

export function sendContact(payload: ContactFormPayload) {
    return apiPost<ApiResponse<{ message: string }>>("/contact", payload);
}