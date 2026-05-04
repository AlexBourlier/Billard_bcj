import { Link } from "react-router-dom";
import type { Post } from "../../types/api";

type PostsSectionProps = {
    posts: Post[];
    discipline: string;
};

/**
 * Section affichant la liste des articles d'une discipline.
 *
 * Responsabilités :
 * - afficher les posts
 * - gérer le cas vide
 * - gérer le lien vers le détail
 * - transmettre la page d'origine pour le retour depuis PostPage
 */
export function PostsSection({ posts, discipline }: PostsSectionProps) {
    return (
        <section id="articles">
            <h2>Articles</h2>

            {posts.length > 0 ? (
                posts.map((post) => (
                    <article key={post.id}>
                        <h3>
                            {post.slug ? (
                                <Link
                                    to={`/posts/${post.slug}`}
                                    state={{ from: `/disciplines/${discipline}` }}
                                >
                                    {post.title ?? post.titre}
                                </Link>
                            ) : (
                                post.title ?? post.titre
                            )}
                        </h3>
                        {post.image_url && (
                            <img
                                src={post.image_url}
                                alt={post.title ?? post.titre ?? "Image de l’article"}
                                style={{ maxWidth: "200px" }}
                            />
                        )}

                        {post.excerpt && <p>{post.excerpt}</p>}
                    </article>
                ))
            ) : (
                <p>Aucun article pour cette discipline.</p>
            )}
        </section>
    );
}