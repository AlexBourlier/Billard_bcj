import { useOutletContext } from "react-router-dom";
import { CalendarSection } from "../calendar/CalendarSection";
import type { DisciplinePageContext } from "../../pages/DisciplinePage";

export function DisciplineCalendarPage() {
    const { data } = useOutletContext<DisciplinePageContext>();

    return <CalendarSection events={data.calendar} />;
}