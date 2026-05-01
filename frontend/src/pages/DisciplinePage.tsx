import { useParams } from "react-router-dom";

export function DisciplinePage() {
    const { discipline } = useParams();

    return (
        <main>
            <h1>Discipline : {discipline}</h1>
        </main>
    );
}