import { ArticleCard } from "../ui/Card";
import { ArticleTitle } from "../ui/Title";
import ContactForm from "./ContactForm";

type ContactInfosProps = {
    introMessage: string | null;
};

export default function ContactInfos({
    introMessage,
}: ContactInfosProps) {
    if (!introMessage) {
        return null;
    }

    return (
        <div>
            <ArticleTitle>Contact</ArticleTitle>
            <ArticleCard className="contact-intro">
                <p style={{ whiteSpace: "pre-line" }}>
                    {introMessage}
                </p>
                <ContactForm/>
            </ArticleCard>
        </div>
    );
}