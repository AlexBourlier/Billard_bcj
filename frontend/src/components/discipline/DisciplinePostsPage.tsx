import { useOutletContext } from "react-router-dom";
import { PostsSection } from "../posts/PostsSection";
import type { DisciplinePageContext } from "../../pages/DisciplinePage";

export function DisciplinePostsPage() {
    const { data, meta } = useOutletContext<DisciplinePageContext>();

    return <PostsSection posts={data.posts} discipline={meta.discipline} />;
}