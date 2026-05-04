import type { Document } from "../../types/api";

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
        <section id="documents">
            <h2>Documents</h2>

            {documents.length > 0 ? (
                <ul>
                    {documents.map((document) => (
                        <li key={document.id}>
                            {document.file_url ? (
                                <a
                                    href={document.file_url}
                                    target="_blank"
                                    rel="noreferrer"
                                >
                                    {document.title}
                                </a>
                            ) : (
                                document.title
                            )}
                        </li>
                    ))}
                </ul>
            ) : (
                <p>Aucun document disponible.</p>
            )}
        </section>
    );
}