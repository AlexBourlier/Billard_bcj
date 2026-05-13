import { Helmet } from "react-helmet-async";
import Contact from "../components/contact/Contact";


export function ContactPage() {
    return (
        <>
            <Helmet>
                <title>Contact - BCJ37 | Billard Club de Joué-lès-Tours</title>

                <meta
                    name="description"
                    content="Contactez le Billard Club de Joué-lès-Tours pour toute question ou information."
                />

                <meta
                    property="og:title"
                    content="Contact - BCJ37"
                />

                <meta
                    property="og:description"
                    content="Contactez le Billard Club de Joué-lès-Tours pour toute question ou information."
                />

                <meta property="og:type" content="website" />
            </Helmet>

            <Contact />
        </>
    );
}