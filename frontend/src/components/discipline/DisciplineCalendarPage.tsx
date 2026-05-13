import { useOutletContext } from "react-router-dom";
import { CalendarSection } from "../calendar/CalendarSection";
import type { DisciplinePageContext } from "../../pages/DisciplinePage";
import { Helmet } from "react-helmet-async";

export function DisciplineCalendarPage() {
    const { data } = useOutletContext<DisciplinePageContext>();

    console.log("data.calendar :", data.calendar);
    return (
        <>
        <Helmet>
                <title>
                    Calendrier des compétitions - BCJ37 | Billard Club de Joué-lès-Tours
                </title>

                <meta
                    name="description"
                    content="Retrouvez toutes les compétitions du BCJ37 : tournois, résultats, événements et vie du Billard Club de Joué-lès-Tours."
                />

                <meta
                    property="og:title"
                    content="Calendrier des compétitions - BCJ37"
                />

                <meta
                    property="og:description"
                    content="Compétitions, résultats, tournois et vie du club du BCJ37."
                />

                <meta property="og:type" content="website" />
        </Helmet>
        <CalendarSection events={data.calendar} />
        </>
    );
}