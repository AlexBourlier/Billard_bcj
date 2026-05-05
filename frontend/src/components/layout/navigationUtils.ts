export function getMenuPath(name: string) {
    const slug = name.toLowerCase();

    return slug === "club"
        ? "/club"
        : `/disciplines/${slug}`;
}