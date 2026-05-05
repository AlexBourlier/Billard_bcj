import { apiGet, apiPost } from "./client";
import type { ApiResponse, Contact, ContactFormPayload } from "../types/api";

export function getContacts() {
    return apiGet<ApiResponse<Contact[]>>("/contact");
}

export function sendContact(payload: ContactFormPayload) {
    return apiPost<ApiResponse<{ message: string }>>("/contact", payload);
}