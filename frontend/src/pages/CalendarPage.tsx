import { Helmet } from "react-helmet-async";
import { useState } from "react";

import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";

import { ErrorPage } from "./ErrorPage";

export function CalendarPage() {
    const [iframeError, setIframeError] = useState(false);

    /*
     * Erreur chargement iframe
     */
    if (iframeError) {
        return (
            <ErrorPage
                code="503"
                title="Calendrier indisponible"
                message="Le calendrier ne peut pas être affiché pour le moment."
            />
        );
    }

    return (
        <>
            <Helmet>
                <title>
                    BCJ37 - Billard Club de Joué-lès-Tours - Calendrier
                </title>

                <meta
                    name="description"
                    content="Découvrez le calendrier du BCJ37, club de billard à Joué-lès-Tours. Suivez les événements et compétitions à venir."
                />
            </Helmet>

            <section className="calendar-section">
                <ArticleTitle>
                    Calendrier
                </ArticleTitle>

                <ArticleCard>
                    <div className="calendar-wrapper">
                        <iframe
                            loading="lazy"
                            src="https://calendar.google.com/calendar/embed?height=800&wkst=2&ctz=Europe%2FParis&mode=WEEK&showTz=0&showPrint=0&title=Agenda%20Billard%20Club%20de%20Jou%C3%A9-L%C3%A8s-Tours&showCalendars=0&src=YmNqMzcuc3BvcnRlYXN5QGdtYWlsLmNvbQ&color=%2333b679"
                            title="Agenda du Billard Club de Joué-lès-Tours - vue semaine"
                            width="100%"
                            height="800"
                            style={{ border: 0 }}
                            scrolling="no"
                            onError={() => setIframeError(true)}
                        />
                    </div>
                </ArticleCard>
            </section>
        </>
    );
}