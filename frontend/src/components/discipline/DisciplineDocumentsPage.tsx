import { useOutletContext } from "react-router-dom";
import { DocumentsSection } from "../documents/DocumentsSection";
import type { DisciplinePageContext } from "../../pages/DisciplinePage";
import { Helmet } from "react-helmet-async";

export function DisciplineDocumentsPage() {
    const { data } = useOutletContext<DisciplinePageContext>();

    return (
        <>
            <Helmet>
                <title>
                    Documents du club - BCJ37 | Billard Club de Joué-lès-Tours
                </title>

                <meta
                    name="description"
                    content="Retrouvez tous les documents du BCJ37 : règlements, résultats, événements et vie du Billard Club de Joué-lès-Tours."
                />

                <meta
                    property="og:title"
                    content="Documents du club - BCJ37"
                />

                <meta
                    property="og:description"
                    content="Règlements, résultats, événements et vie du club du BCJ37."
                />

                <meta property="og:type" content="website" />
            </Helmet>
            <DocumentsSection documents={data.documents} />
        </>
    );
}