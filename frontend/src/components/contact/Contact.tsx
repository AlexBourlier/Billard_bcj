import { useEffect, useState } from "react";
import { getContacts } from "../../api/contactApi";
import ContactInfos from "./ContactInfos";
import AlertToast from "../ui/AlertToast";
import { Helmet } from "react-helmet-async";

export default function Contact() {
    const [introMessage, setIntroMessage] = useState<string | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        getContacts()
            .then((response) => {
                setIntroMessage(response.data[0]?.message ?? null);
            })
            .catch((error) => {
                console.error(error);
                setError("Impossible de charger les informations de contact.");
            })
            .finally(() => {
                setLoading(false);
            });
    }, []);

    if (loading) {
        return <p>Chargement...</p>;
    }

    return (
        <>
        <Helmet>
            <title>BCJ37 - Billard Club de Joué-lès-Tours - Contact</title>

            <meta
                name="description"
                content="Contactez le BCJ37, club de billard à Joué-lès-Tours. Obtenez les coordonnées, horaires et informations pour rejoindre notre communauté passionnée de billard."
            />
        </Helmet>
    
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
        </>
    );
}