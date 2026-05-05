const API_URL = import.meta.env.VITE_API_URL;

export async function apiGet<T>(endpoint: string): Promise<T> {
    const url = `${API_URL}${endpoint}`;

    const response = await fetch(url, {
        headers: {
            Accept: "application/json",
        },
    });

    if (!response.ok) {
        const body = await response.text();

        console.error("API ERROR", {
            url,
            status: response.status,
            body,
        });

        throw new Error(`API error ${response.status}`);
    }

    return response.json() as Promise<T>;
}

export async function apiPost<T>(endpoint: string, body: unknown): Promise<T> {
    const response = await fetch(`${API_URL}${endpoint}`, {
        method: "POST",
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
    });

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    return data;
}