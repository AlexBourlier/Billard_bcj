import { useOutletContext } from "react-router-dom";
import { PostsSection } from "../posts/PostsSection";
import type { DisciplinePageContext } from "../../pages/DisciplinePage";
import { Helmet } from "react-helmet-async";

export function DisciplinePostsPage() {
    const { data, meta } = useOutletContext<DisciplinePageContext>();

    return (
        <>
            <Helmet>
                <title>
                    Actualités du club - BCJ37 | Billard Club de Joué-lès-Tours
                </title>

                <meta
                    name="description"
                    content="Retrouvez toutes les actualités du BCJ37 : compétitions, tournois, résultats, événements et vie du Billard Club de Joué-lès-Tours."
                />

                <meta
                    property="og:title"
                    content="Actualités du club - BCJ37"
                />

                <meta
                    property="og:description"
                    content="Compétitions, résultats, tournois et vie du club du BCJ37."
                />

                <meta property="og:type" content="website" />
            </Helmet>
            <PostsSection posts={data.posts} discipline={meta.discipline} />
        </>
    );
}