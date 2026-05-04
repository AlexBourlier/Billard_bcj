import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import { getAllPosts, getPostsByPeriod } from "../api/postsApi";
import type { PaginationMeta, Post } from "../types/api";

type PageState = {
    posts: Post[];
    meta: PaginationMeta | null;
    loading: boolean;
    error: string | null;
};

type ClubPeriod = {
    label: string;
    value: string | null;
};

/**
 * Génère dynamiquement les périodes d'archives du club.
 *
 * Exemple en 2026 :
 * - Depuis 2020
 * - 2010 - 2019
 * - 2000 - 2009
 * - Avant 2000
 *
 * Exemple en 2030 :
 * - Depuis 2030
 * - 2020 - 2029
 * - 2010 - 2019
 * - Avant 2010
 */
function getClubPeriods(currentYear = new Date().getFullYear()): ClubPeriod[] {
    const currentDecade = Math.floor(currentYear / 10) * 10;

    return [
        {
            label: "Tous",
            value: null,
        },
        {
            label: `Depuis ${currentDecade}`,
            value: `depuis_${currentDecade}`,
        },
        {
            label: `${currentDecade - 10} - ${currentDecade - 1}`,
            value: `${currentDecade - 10}_${currentDecade - 1}`,
        },
        {
            label: `${currentDecade - 20} - ${currentDecade - 11}`,
            value: `${currentDecade - 20}_${currentDecade - 11}`,
        },
        {
            label: `Avant ${currentDecade - 20}`,
            value: `avant_${currentDecade - 20}`,
        },
    ];
}

/**
 * Page club.
 *
 * Responsabilités :
 * - afficher tous les posts du site
 * - filtrer les posts par période via le paramètre d'URL
 * - afficher le sous-menu dynamique des décennies
 */
export function ClubPostsPage() {
    const { period } = useParams();

    const periods = getClubPeriods();

    const [state, setState] = useState<PageState>({
        posts: [],
        meta: null,
        loading: true,
        error: null,
    });

    useEffect(() => {
        let isMounted = true;

        const request = period ? getPostsByPeriod(period) : getAllPosts();

        request
            .then((response) => {
                if (!isMounted) return;

                setState({
                    posts: response.data,
                    meta: response.meta,
                    loading: false,
                    error: null,
                });
            })
            .catch(() => {
                if (!isMounted) return;

                setState({
                    posts: [],
                    meta: null,
                    loading: false,
                    error: "Impossible de charger les articles du club.",
                });
            });

        return () => {
            isMounted = false;
        };
    }, [period]);

    if (state.loading) return <p>Chargement...</p>;
    if (state.error) return <p>{state.error}</p>;

    return (
        <main>
            {/* <h1>Club</h1> */}

            <nav aria-label="Archives du club">
                {periods.map((item) => {
                    const isActive = item.value === (period ?? null);

                    return (
                        <Link
                            key={item.value ?? "all"}
                            to={item.value ? `/club/annee/${item.value}` : "/club"}
                            aria-current={isActive ? "page" : undefined}
                        >
                            {isActive ? "• " : ""}
                            {item.label}{" "}
                        </Link>
                    );
                })}
            </nav>

            <section>
                {/* <h2>Articles</h2> */}

                {state.meta && (
                    <p>
                        {state.meta.total} article
                        {state.meta.total > 1 ? "s" : ""}
                    </p>
                )}

                {state.posts.length > 0 ? (
                    state.posts.map((post) => (
                        <article key={post.id}>
                            {post.image_url && (
                                <img
                                    src={post.image_url}
                                    alt={post.title ?? post.titre ?? "Image de l’article"}
                                    style={{ maxWidth: "200px" }}
                                />
                            )}

                            <h3>
                                {post.slug ? (
                                    <Link
                                        to={`/posts/${post.slug}`}
                                        state={{
                                            from: period
                                                ? `/club/annee/${period}`
                                                : "/club",
                                        }}
                                    >
                                        {post.title ?? post.titre}
                                    </Link>
                                ) : (
                                    post.title ?? post.titre
                                )}
                            </h3>

                            {post.year && <p>Année : {post.year}</p>}

                            {post.excerpt && 
                            <div
                                dangerouslySetInnerHTML={{
                                    __html: post.excerpt ?? "",
                                }}
                            />
                            }
                        </article>
                    ))
                ) : (
                    <p>Aucun article disponible.</p>
                )}
            </section>
        </main>
    );
}