import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";

export function CalendarPage() {
    return (
        <section className="calendar-section">
            <ArticleTitle>Calendrier</ArticleTitle>
            <ArticleCard>
                <iframe
                    loading="lazy"
                    src="https://calendar.google.com/calendar/embed?height=800&wkst=2&ctz=Europe%2FParis&mode=WEEK&showTz=0&showPrint=0&title=Agenda%20Billard%20Club%20de%20Jou%C3%A9-L%C3%A8s-Tours&showCalendars=0&src=YmNqMzcuc3BvcnRlYXN5QGdtYWlsLmNvbQ&color=%2333b679"
                    title="Agenda du Billard Club de Joué-lès-Tours - vue semaine"
                    width="100%"
                    frameBorder="0"
                    scrolling="no"
                />
            </ArticleCard>
        </section>
    );
}