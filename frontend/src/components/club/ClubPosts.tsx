import { useEffect, useState } from "react";
import {
    useParams,
    useSearchParams,
} from "react-router-dom";
import { Helmet } from "react-helmet-async";

import { getAllPosts, getPostsByPeriod } from "../../api/postsApi";
import { ApiError } from "../../api/client";

import type { PaginationMeta, Post } from "../../types/api";

import ClubArchiveNav, { getClubPeriods } from "./ClubArchiveNav";
import ClubPostList from "./ClubPostList";
import Pagination from "../ui/Pagination";

import { ErrorPage } from "../../pages/ErrorPage";
import { NotFoundPage } from "../../pages/NotFoundPage";

type PageState = {
    posts: Post[];
    meta: PaginationMeta | null;
    loading: boolean;
    error: boolean;
    notFound: boolean;
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
        error: false,
        notFound: false,
    });

    useEffect(() => {
        let isMounted = true;

        setState((current) => ({
            ...current,
            loading: true,
            error: false,
            notFound: false,
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
                    error: false,
                    notFound: false,
                });
            })
            .catch((error) => {
                console.error(error);

                if (!isMounted) return;

                if (error instanceof ApiError && (error.status === 404 || error.status === 400)) {
                    setState({
                        posts: [],
                        meta: null,
                        loading: false,
                        error: false,
                        notFound: true,
                    });

                    return;
                }

                setState({
                    posts: [],
                    meta: null,
                    loading: false,
                    error: true,
                    notFound: false,
                });
            });

        return () => {
            isMounted = false;
        };
    }, [period, currentPage]);

    if (state.loading) {
        return <p>Chargement...</p>;
    }

    if (state.notFound) {
        return <NotFoundPage />;
    }

    if (state.error) {
        return (
            <ErrorPage
                code="500"
                title="Erreur serveur"
                message="Impossible de charger les actualités du club."
            />
        );
    }

    return (
        <>
            <Helmet>
                <title>
                    BCJ37 - Billard Club de Joué-lès-Tours - Actualités
                </title>

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