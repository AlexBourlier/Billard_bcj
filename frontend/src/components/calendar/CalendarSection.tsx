import type { CalendarEvent } from "../../types/api";

type CalendarSectionProps = {
    events: CalendarEvent[];
};

/**
 * Section affichant les événements calendrier d'une discipline.
 *
 * Responsabilités :
 * - afficher les événements liés à la discipline
 * - formater les dates pour l'affichage français
 * - gérer le cas où aucun événement n'est disponible
 */
export function CalendarSection({ events }: CalendarSectionProps) {
    return (
        <section id="calendar">
            <h2>Calendrier</h2>

            {events.length > 0 ? (
                events.map((event) => (
                    <article key={event.id}>
                        <h3>{event.titre}</h3>

                        {event.lieu && <p>{event.lieu}</p>}

                        {event.date_debut && (
                            <p>
                                Date :{" "}
                                {new Date(event.date_debut).toLocaleDateString("fr-FR")}
                            </p>
                        )}

                        {event.club && <p>Club : {event.club}</p>}

                        {event.url && (
                            <a href={event.url} target="_blank" rel="noreferrer">
                                Voir l’événement
                            </a>
                        )}
                    </article>
                ))
            ) : (
                <p>Aucun événement à afficher.</p>
            )}
        </section>
    );
}