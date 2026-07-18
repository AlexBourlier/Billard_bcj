import ContactInfos from "./ContactInfos";

// La page de contact affiche les coordonnées du club et le formulaire.
// Elle ne récupère plus la liste des messages reçus (donnée privée, réservée à
// l'administration) : le formulaire est désormais toujours affiché.
export default function Contact() {
    return (
        <section className="contact-page">
            <ContactInfos />
        </section>
    );
}
