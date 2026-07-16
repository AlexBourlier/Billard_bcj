import { useEffect, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import { Helmet } from "react-helmet-async";

import { getPostBySlug } from "../api/postsApi";
import type { Post } from "../types/api";

import { ArticleCard } from "../components/ui/Card";
import { Return } from "../components/ui/Return";
import { ArticleTitle } from "../components/ui/Title";
import { Signets } from "../components/ui/Signets";

import { ErrorPage } from "./ErrorPage";
import { NotFoundPage } from "./NotFoundPage";
import { ApiError } from "../api/client";

type PageState = {
    post: Post | null;
    loading: boolean;
    error: boolean;
    notFound: boolean;
};

type LocationState = {
    from?: string;
};

function stripHtml(value: string): string {
    return value.replace(/<[^>]+>/g, "").trim();
}

export function PostPage() {
    const { slug } = useParams();
    const navigate = useNavigate();
    const location = useLocation();

    const locationState = location.state as LocationState | null;
    const backTarget = locationState?.from ?? "/";

    const [state, setState] = useState<PageState>({
        post: null,
        loading: true,
        error: false,
        notFound: false,
    });

    useEffect(() => {
        if (!slug) {
            return;
        }

        let isMounted = true;

        getPostBySlug(slug)
            .then((response) => {
                if (!isMounted) return;

                setState({
                    post: response.data,
                    loading: false,
                    error: false,
                    notFound: false,
                });
            })
            .catch((error) => {
                if (!isMounted) return;

                if (error instanceof ApiError && error.status === 404) {
                    setState({
                        post: null,
                        loading: false,
                        error: false,
                        notFound: true,
                    });

                    return;
                }

                setState({
                    post: null,
                    loading: false,
                    error: true,
                    notFound: false,
                });
            });

        return () => {
            isMounted = false;
        };
    }, [slug]);

    /*
     * URL invalide
     */
    if (!slug) {
        return <NotFoundPage />;
    }

    /*
     * Loading
     */
    if (state.loading) {
        return <p>Chargement...</p>;
    }

    /*
     * Article inexistant
     */
    if (state.notFound) {
        return <NotFoundPage />;
    }

    /*
     * API ou serveur HS
     */
    if (state.error) {
        return (
            <ErrorPage
                code="500"
                title="Erreur serveur"
                message="Impossible de charger l’article demandé."
            />
        );
    }

    /*
     * Sécurité supplémentaire
     */
    if (!state.post) {
        return <NotFoundPage />;
    }

    const { post } = state;

    const postTitle = post.title ?? post.titre ?? "Article";

    const postDescription = post.excerpt
        ? stripHtml(post.excerpt).slice(0, 160)
        : `Actualité du BCJ37 : ${postTitle}.`;

    return (
        <>
            <Helmet title={`${postTitle} - BCJ37`}>
                <meta
                    name="description"
                    content={postDescription}
                />

                <meta
                    property="og:title"
                    content={`${postTitle} - BCJ37`}
                />

                <meta
                    property="og:description"
                    content={postDescription}
                />

                {post.image_url && (
                    <meta
                        property="og:image"
                        content={post.image_url}
                    />
                )}
            </Helmet>

            <section className="post-page">
                <ArticleTitle>
                    Actualité
                </ArticleTitle>

                <ArticleCard className="post-card">
                    <div className="post-image">
                        {post.video_url ? (
                            <iframe
                                width="100%"
                                height="600"
                                src={post.video_url}
                                title={postTitle}
                                frameBorder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowFullScreen
                            />
                        ) : post.image_url ? (
                            <img
                                src={post.image_url}
                                alt={postTitle}
                                style={{ maxWidth: "800px" }}
                            />
                        ) : null}
                    </div>

                    <div className="post-content">
                        <h2>{postTitle}</h2>

                        {post.year && (
                            <Signets id={`year-${post.year}-${post.id}`}>
                                {post.year}
                            </Signets>
                        )}

                        {post.content && (
                            // Rendu HTML brut volontaire : le contenu est nettoye
                            // cote serveur par liste blanche (HtmlSanitizer) avant
                            // d'etre renvoye par l'API, il ne contient donc ni
                            // script ni style dangereux.
                            <div
                                dangerouslySetInnerHTML={{
                                    __html: post.content,
                                }}
                            />
                        )}

                        {post.created_at && (
                            <p className="post-date">
                                Publié le{" "}
                                {new Date(post.created_at).toLocaleDateString()}
                            </p>
                        )}
                    </div>
                </ArticleCard>

                <Return className="post-return">
                    <button
                        type="button"
                        onClick={() => navigate(backTarget)}
                        className="returnButton"
                    >
                        Retour
                    </button>
                </Return>
            </section>
        </>
    );
}