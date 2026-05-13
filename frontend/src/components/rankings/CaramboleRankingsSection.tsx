import { useState } from "react";
import type { CaramboleRankingFile } from "../../types/api";
import { ArticleCard } from "../ui/Card";

type Props = {
    files: CaramboleRankingFile[];
};

export function CaramboleRankingsSection({ files }: Props) {
    const [selectedFile, setSelectedFile] = useState<CaramboleRankingFile | null>(
        files[0] ?? null
    );

    if (files.length === 0) {
        return null;
    }

    return (
        <section className="ranking-carambole-section">

            <nav className="ranking-carambole-nav">
                {files.map((file) => (
                    <button
                        key={file.filename}
                        type="button"
                        onClick={() => setSelectedFile(file)}
                    >
                        {file.name}
                    </button>
                ))}
            </nav>

            {selectedFile && (
                <ArticleCard className="carambole-ranking-card">
                <iframe
                    src={selectedFile.url}
                    title={selectedFile.name}
                    width="100%"
                    height="800"
                />
                </ArticleCard>
            )}
        </section>
    );
}