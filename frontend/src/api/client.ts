const API_URL = import.meta.env.VITE_API_URL;

export class ApiError extends Error {
    status: number;
    body: unknown;

    constructor(message: string, status: number, body: unknown = null) {
        super(message);
        this.name = "ApiError";
        this.status = status;
        this.body = body;
    }
}

async function parseResponseBody(response: Response): Promise<unknown> {
    const contentType = response.headers.get("content-type");

    if (contentType?.includes("application/json")) {
        return response.json();
    }

    return response.text();
}

export async function apiGet<T>(endpoint: string): Promise<T> {
    const url = `${API_URL}${endpoint}`;

    const response = await fetch(url, {
        headers: {
            Accept: "application/json",
        },
    });

    const body = await parseResponseBody(response);

    if (!response.ok) {
        console.error("API ERROR", {
            url,
            status: response.status,
            body,
        });

        throw new ApiError(
            `API error ${response.status}`,
            response.status,
            body,
        );
    }

    return body as T;
}

export async function apiPost<T>(endpoint: string, body: unknown): Promise<T> {
    const url = `${API_URL}${endpoint}`;

    const response = await fetch(url, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
    });

    const responseBody = await parseResponseBody(response);

    if (!response.ok) {
        throw new ApiError(
            `API error ${response.status}`,
            response.status,
            responseBody,
        );
    }

    return responseBody as T;
}