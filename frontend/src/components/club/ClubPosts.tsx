import { useEffect, useState } from "react";
import { useParams } from "react-router-dom";

import { getAllPosts, getPostsByPeriod } from "../../api/postsApi";
import type { PaginationMeta, Post } from "../../types/api";

import ClubArchiveNav, { getClubPeriods } from "./ClubArchiveNav";
import ClubPostList from "./ClubPostList";

type PageState = {
    posts: Post[];
    meta: PaginationMeta | null;
    loading: boolean;
    error: string | null;
};

export default function ClubPosts() {
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

        setState((current) => ({
            ...current,
            loading: true,
            error: null,
        }));

        const request = period
            ? getPostsByPeriod(period)
            : getAllPosts();

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
            .catch((error) => {
                console.error(error);

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

    if (state.loading) {
        return <p>Chargement...</p>;
    }

    if (state.error) {
        return <p role="alert">{state.error}</p>;
    }

    return (
        <main className="club-page">
            <ClubArchiveNav
                periods={periods}
                activePeriod={period ?? null}
            />

            <section className="club-posts-section">
                {/* {state.meta && (
                    <p className="club-posts-count">
                        {state.meta.total} article
                        {state.meta.total > 1 ? "s" : ""}
                    </p>
                )} */}

                <ClubPostList
                    posts={state.posts}
                    activePeriod={period ?? null}
                />
            </section>
        </main>
    );
}