import { Link } from "react-router-dom";
import type { Post } from "../../types/api";

type Props = {
    posts: Post[];
};

/**
 * Section affichant la liste des articles d'une discipline.
 *
 * Responsabilités :
 * - afficher les posts
 * - gérer le cas vide
 * - gérer le lien vers le détail
 */
export function PostsSection({ posts }: Props) {
    return (
        <section id="articles">
            <h2>Articles</h2>

            {posts.length > 0 ? (
                posts.map((post) => (
                    <article key={post.id}>
                        <h3>
                            <Link to={`/posts/${post.slug}`}>
                                {post.title ?? post.titre}
                            </Link>
                        </h3>

                        {post.excerpt && <p>{post.excerpt}</p>}
                    </article>
                ))
            ) : (
                <p>Aucun article pour cette discipline.</p>
            )}
        </section>
    );
}