import { Link, useLocation } from "react-router-dom";
import type { PaginationMeta } from "../../types/api";

type PaginationProps = {
    meta: PaginationMeta;
};

export default function Pagination({ meta }: PaginationProps) {
    const location = useLocation();

    function getPageUrl(page: number) {
        const params = new URLSearchParams(location.search);
        params.set("page", String(page));

        return `${location.pathname}?${params.toString()}`;
    }

    return (
        <nav
            className="pagination"
            aria-label="Pagination des articles"
        >
            {meta.current_page > 1 && (
                <Link
                    to={getPageUrl(meta.current_page - 1)}
                    className="pagination__link pagination__link--control"
                >
                    Précédent
                </Link>
            )}

            {Array.from(
                { length: meta.last_page },
                (_, index) => index + 1,
            ).map((page) => (
                <Link
                    key={page}
                    to={getPageUrl(page)}
                    className={
                        page === meta.current_page
                            ? "pagination__link pagination__link--active"
                            : "pagination__link"
                    }
                    aria-current={
                        page === meta.current_page
                            ? "page"
                            : undefined
                    }
                >
                    {page}
                </Link>
            ))}

            {meta.current_page < meta.last_page && (
                <Link
                    to={getPageUrl(meta.current_page + 1)}
                    className="pagination__link pagination__link--control"
                >
                    Suivant
                </Link>
            )}
        </nav>
    );
}