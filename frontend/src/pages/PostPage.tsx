import { useEffect, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import { getPostBySlug } from "../api/postsApi";
import type { Post } from "../types/api";
import { ArticleCard } from "../components/ui/Card";
import { Return } from "../components/ui/Return";
import { ArticleTitle } from "../components/ui/Title";
import { Signets } from "../components/ui/Signets";

type PageState = {
    post: Post | null;
    loading: boolean;
    error: string | null;
};

type LocationState = {
    from?: string;
};

export function PostPage() {
    const { slug } = useParams();
    const navigate = useNavigate();
    const location = useLocation();

    const locationState = location.state as LocationState | null;
    const backTarget = locationState?.from ?? "/";

    const [state, setState] = useState<PageState>({
        post: null,
        loading: true,
        error: null,
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
                    error: null,
                });
            })
            .catch(() => {
                if (!isMounted) return;

                setState({
                    post: null,
                    loading: false,
                    error: "Impossible de charger l’article.",
                });
            });

        return () => {
            isMounted = false;
        };
    }, [slug]);

    if (!slug) return <p>Article introuvable.</p>;
    if (state.loading) return <p>Chargement...</p>;
    if (state.error) return <p>{state.error}</p>;
    if (!state.post) return <p>Aucun article disponible.</p>;

    const { post } = state;

    return (
        <section className="post-page">
            <ArticleTitle>Actualité</ArticleTitle>
            <ArticleCard className="post-card">
                <div className="post-image">
                    {post.video_url ? (
                        <iframe
                            width="100%"
                            height="600"
                            src={post.video_url}
                            title={post.title ?? post.titre ?? "Vidéo de l’article"}
                            frameBorder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowFullScreen
                        />
                    ) : post.image_url ? (
                        <img
                            src={post.image_url}
                            alt={post.title ?? post.titre ?? "Image de l’article"}
                            style={{ maxWidth: "800px" }}
                        />
                    ) : (null)}
                </div>
                <div className="post-content">
                    <h2>{post.title ?? post.titre}</h2>

                    {post.year && (
                        <Signets id={`year-${post.year}-${post.id}`}>
                            {post.year}
                        </Signets>
                    )}

                    {post.content && (
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
    );
}