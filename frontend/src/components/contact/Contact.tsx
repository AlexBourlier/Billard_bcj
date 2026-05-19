import { useEffect, useState } from "react";
import { getContacts } from "../../api/contactApi";
import { ApiError } from "../../api/client";
import ContactInfos from "./ContactInfos";
import AlertToast from "../ui/AlertToast";

export default function Contact() {
    const [introMessage, setIntroMessage] = useState<string | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        let isMounted = true;

        getContacts()
            .then((response) => {
                if (!isMounted) return;

                setIntroMessage(response.data[0]?.message ?? null);
            })
            .catch((error) => {
                if (!isMounted) return;

                console.error(error);

                if (error instanceof ApiError && error.status >= 500) {
                    setError(
                        "Les informations de contact ne peuvent pas être chargées pour le moment."
                    );
                    return;
                }

                setError(
                    "Une erreur est survenue pendant le chargement des informations de contact."
                );
            })
            .finally(() => {
                if (!isMounted) return;

                setLoading(false);
            });

        return () => {
            isMounted = false;
        };
    }, []);

    if (loading) {
        return <p>Chargement...</p>;
    }

    return (
        <section className="contact-page">
            {error && (
                <AlertToast
                    message={error}
                    type="error"
                    onClose={() => setError(null)}
                />
            )}

            <ContactInfos introMessage={introMessage} />
        </section>
    );
}