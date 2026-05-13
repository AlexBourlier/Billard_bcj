import { useOutletContext } from "react-router-dom";
import { DocumentsSection } from "../documents/DocumentsSection";
import type { DisciplinePageContext } from "../../pages/DisciplinePage";

export function DisciplineDocumentsPage() {
    const { data } = useOutletContext<DisciplinePageContext>();

    return (
            <DocumentsSection documents={data.documents} />
    );
}