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