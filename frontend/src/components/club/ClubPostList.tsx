import type { Post } from "../../types/api";
import { ArticleCard } from "../ui/Card";
import { ArticleTitle } from "../ui/Title";
import ClubPostCard from "./ClubPostCard";

type ClubPostListProps = {
    posts: Post[];
    activePeriod: string | null;
    priority?: boolean;
};

export default function ClubPostList({
    posts,
    activePeriod,
}: ClubPostListProps) {
    if (posts.length === 0) {
        return <p>Aucun article disponible.</p>;
    }

    return (
        <>
        <section className="club-post-list">
        <ArticleTitle>Le club</ArticleTitle>
        <ArticleCard>
        
            {posts.map((post, index) => (
                <ClubPostCard
                    key={post.id}
                    post={post}
                    activePeriod={activePeriod}
                    priority={index === 0}
                />
            ))}

        </ArticleCard>
        </section>
        </>
    );
}