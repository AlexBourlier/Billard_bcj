import { useEffect, useState } from "react";
import {
    useParams,
    useSearchParams,
} from "react-router-dom";

import { getAllPosts, getPostsByPeriod } from "../../api/postsApi";
import type { PaginationMeta, Post } from "../../types/api";

import ClubArchiveNav, { getClubPeriods } from "./ClubArchiveNav";
import ClubPostList from "./ClubPostList";
import Pagination from "../ui/Pagination";
import { Helmet } from "react-helmet-async";

type PageState = {
    posts: Post[];
    meta: PaginationMeta | null;
    loading: boolean;
    error: string | null;
};

export default function ClubPosts() {
    const { period } = useParams();
    const [searchParams] = useSearchParams();

    const currentPage = Number(searchParams.get("page") ?? 1);
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
            ? getPostsByPeriod(period, currentPage)
            : getAllPosts(currentPage);

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
    }, [period, currentPage]);

    if (state.loading) {
        return <p>Chargement...</p>;
    }

    if (state.error) {
        return <p role="alert">{state.error}</p>;
    }

    return (
        <>
        <Helmet>
            <title>BCJ37 - Billard Club de Joué-lès-Tours - Actualités</title>

            <meta
                name="description"
                content="Club de billard à Joué-lès-Tours : Suivez les dernières actualités, résultats et événements du BCJ37. Restez informé sur les compétitions, les performances des joueurs et les activités du club."
            />
        </Helmet>

        <div className="club-page">
            <ClubArchiveNav
                periods={periods}
                activePeriod={period ?? null}
            />

            <section className="club-posts-section">
                <ClubPostList
                    posts={state.posts}
                    activePeriod={period ?? null}
                />

                {state.meta && state.meta.last_page > 1 && (
                    <Pagination meta={state.meta} />
                )}
            </section>
        </div>
        </>
    );
}