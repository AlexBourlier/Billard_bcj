import { Helmet } from "react-helmet-async";

import ClubPosts from "../components/club/ClubPosts";

export function ClubPostsPage() {
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

            <ClubPosts />
        </>
    );
}