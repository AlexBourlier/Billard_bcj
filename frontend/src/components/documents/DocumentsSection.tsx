import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import type { Document } from "../../types/api";
import { ArticleCard } from "../ui/Card";
import { ArticleTitle } from "../ui/Title";

import {
    faFilePdf, 
} from "@awesome.me/kit-c0df283285/icons/classic/solid";

type DocumentsSectionProps = {
    documents: Document[];
};

/**
 * Section affichant les documents liés à une discipline.
 *
 * Responsabilités :
 * - afficher la liste des documents disponibles
 * - créer un lien vers le fichier quand une URL est fournie
 * - gérer le cas où aucun document n'est disponible
 */
export function DocumentsSection({ documents }: DocumentsSectionProps) {
    return (
        <section id="documents" className="documents-section">
            <ArticleTitle>Documents</ArticleTitle>
            <ArticleCard>
            {documents.length > 0 ? (
                <ul>
                    {documents.map((document) => (
                        <li key={document.id}>
                            {document.file_url ? (
                                <>
                                    <FontAwesomeIcon icon={faFilePdf} aria-hidden="true" />
                                    <span className="sr-only">Document PDF</span>
                                    <a
                                        href={document.file_url}
                                        target="_blank"
                                        rel="noreferrer"
                                    >
                                    {document.title}
                                </a>
                                </>
                            ) : (
                                document.title
                            )}
                        </li>
                    ))}
                </ul>
            ) : (
                <p>Aucun document disponible.</p>
            )}
        </ArticleCard>
        </section>
    );
}