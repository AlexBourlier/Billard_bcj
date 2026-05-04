import { useEffect, useState } from "react";
import { useLocation, useNavigate, useParams } from "react-router-dom";
import { getPostBySlug } from "../api/postsApi";
import type { Post } from "../types/api";

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
        <article>
            <button type="button" onClick={() => navigate(backTarget)}>
                ← Retour
            </button>

            <h1>{post.title ?? post.titre}</h1>

            {post.created_at && (
                <p>
                    Publié le{" "}
                    {new Date(post.created_at).toLocaleDateString("fr-FR")}
                </p>
            )}

            {post.image_url && (
                <img
                    src={post.image_url}
                    alt={post.title ?? post.titre ?? "Image de l’article"}
                    style={{ maxWidth: "200px" }}
                />
            )}

            {post.excerpt && <p>{post.excerpt}</p>}

            {post.content && (
                <div
                    dangerouslySetInnerHTML={{
                        __html: post.content,
                    }}
                />
            )}
        </article>
    );
}