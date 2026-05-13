
import type { Post } from "../../types/api";
import { ArticleTitle } from "../ui/Title";
import { ArticleCard } from "../ui/Card";
import ClubPostCard from "../club/ClubPostCard";

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
        <section id="articles" className="club-posts-section">
            <div className="club-post-list">
            <ArticleTitle>Actualités</ArticleTitle>
            <ArticleCard>
            {posts.length > 0 ? (
                posts.map((post) => (
                    <ClubPostCard
                        key={post.id}
                        post={post}
                        activePeriod={null}
                        from={`/disciplines/${discipline}/articles`}
                    />

                ))
            ) : (
                <p>Aucun article pour cette discipline.</p>
            )}
            </ArticleCard>
            </div>
        </section>
    );
}