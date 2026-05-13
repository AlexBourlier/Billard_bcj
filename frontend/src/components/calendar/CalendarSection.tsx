import { useState } from "react";

import type { CalendarEvent } from "../../types/api";
import { ArticleCard } from "../ui/Card";
import { ArticleTitle } from "../ui/Title";

type CalendarSectionProps = {
    events: CalendarEvent[];
};

type EventGroup = {
    key: string;
    title: string;
    events: CalendarEvent[];
};

const EVENT_GROUPS: EventGroup[] = [
    {
        key: "international",
        title: "Calendrier compétition Internationale",
        events: [],
    },
    {
        key: "national",
        title: "Calendrier compétition Nationale",
        events: [],
    },
    {
        key: "regional",
        title: "Calendrier compétition Regionale",
        events: [],
    },
    {
        key: "departemental",
        title: "Calendrier compétition Départementale",
        events: [],
    },
];

function normalizeText(value: string): string {
    return value
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "");
}

function formatDate(date: string | null | undefined): string {
    if (!date) return "";

    return new Date(date).toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
}

function getEventTitle(event: CalendarEvent): string {
    const startDate = formatDate(event.date_debut);
    const endDate = formatDate(event.date_fin);

    if (startDate && endDate && startDate !== endDate) {
        return `${event.titre} du ${startDate} au ${endDate}`;
    }

    if (startDate) {
        return `${event.titre} le ${startDate}`;
    }

    return event.titre;
}

function getEventGroupKey(event: CalendarEvent): string | null {
    const calendarEvent = event as CalendarEvent & {
        calendar?: { scope?: string | null };
        category?: { scope?: string | null };
    };

    const scope = normalizeText(
        calendarEvent.scope ??
        calendarEvent.calendar?.scope ??
        calendarEvent.category?.scope ??
        "",
    );

    switch (scope) {
        case "international":
            return "international";

        case "national":
            return "national";

        case "regional":
        case "regionale":
            return "regional";

        case "departemental":
        case "departementale":
            return "departemental";

        default:
            return null;
    }
}

function groupEvents(events: CalendarEvent[]): EventGroup[] {
    return EVENT_GROUPS.map((group) => ({
        ...group,
        events: events.filter((event) => getEventGroupKey(event) === group.key),
    })).filter((group) => group.events.length > 0);
}

export function CalendarSection({ events }: CalendarSectionProps) {
    const [openedEventId, setOpenedEventId] = useState<number | null>(null);

    const groups = groupEvents(events);

    return (
        <section id="calendar" className="calendar-discipline-section">
            {groups.length > 0 ? (
                groups.map((group) => (
                    <div key={group.key} className="calendar-group">
                        <ArticleTitle>{group.title}</ArticleTitle>

                        <ArticleCard className="calendar-events">
                            <div className="calendar-section__list">
                                {group.events.map((event) => {
                                    const isOpen = openedEventId === event.id;

                                    return (
                                        <article
                                            key={event.id}
                                            className={
                                                isOpen
                                                    ? "calendar-event calendar-event--open"
                                                    : "calendar-event"
                                            }
                                        >
                                            <button
                                                type="button"
                                                className="calendar-event__header"
                                                onClick={() =>
                                                    setOpenedEventId(
                                                        isOpen ? null : event.id,
                                                    )
                                                }
                                                aria-expanded={isOpen}
                                            >
                                                {getEventTitle(event)}
                                            </button>

                                            {isOpen && (
                                                <div className="calendar-event__content">
                                                    <p>
                                                        <strong>
                                                            📍 {event.titre}
                                                        </strong>
                                                    </p>

                                                    {event.lieu && (
                                                        <p>
                                                            <strong>🏢 Lieu :</strong>{" "}
                                                            {event.lieu}
                                                        </p>
                                                    )}

                                                    {event.club && (
                                                        <p>
                                                            <strong>
                                                                🎱 Organisateur :
                                                            </strong>{" "}
                                                            {event.club}
                                                        </p>
                                                    )}

                                                    {event.date_debut && (
                                                        <p>
                                                            <strong>
                                                                ⌛ Date de l’événement :
                                                            </strong>{" "}
                                                            {formatDate(event.date_debut)}
                                                            {event.date_fin &&
                                                                event.date_fin !==
                                                                event.date_debut &&
                                                                ` au ${formatDate(
                                                                    event.date_fin,
                                                                )}`}
                                                        </p>
                                                    )}

                                                    {event.url && (
                                                        <a
                                                            href={event.url}
                                                            target="_blank"
                                                            rel="noreferrer"
                                                            className="calendar-event__link"
                                                        >
                                                            Voir l’événement
                                                        </a>
                                                    )}
                                                </div>
                                            )}
                                        </article>
                                    );
                                })}
                            </div>
                        </ArticleCard>
                    </div>
                ))
            ) : (
                <ArticleCard className="calendar-section">
                    <p>Aucun événement à afficher.</p>
                </ArticleCard>
            )}
        </section>
    );
}