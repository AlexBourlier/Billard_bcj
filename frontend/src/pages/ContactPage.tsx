import { type FormEvent, useEffect, useState } from "react";
import { getContacts, sendContact } from "../api/contactApi";

type FormState = {
    name: string;
    email: string;
    message: string;
};

export function ContactPage() {
    const [introMessage, setIntroMessage] = useState<string | null>(null);
    const [form, setForm] = useState<FormState>({
        name: "",
        email: "",
        message: "",
    });
    const [loading, setLoading] = useState(true);
    const [sending, setSending] = useState(false);
    const [success, setSuccess] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        getContacts()
            .then((response) => {
                setIntroMessage(response.data[0]?.message ?? null);
            })
            .catch(() => {
                setError("Impossible de charger les informations de contact.");
            })
            .finally(() => {
                setLoading(false);
            });
    }, []);

    function updateField(field: keyof FormState, value: string) {
        setForm((current) => ({
            ...current,
            [field]: value,
        }));
    }

    async function handleSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        setSending(true);
        setSuccess(null);
        setError(null);

        try {
            const response = await sendContact(form);

            setSuccess(response.data.message);
            setForm({
                name: "",
                email: "",
                message: "",
            });
        } catch {
            setError("Une erreur est survenue lors de l'envoi du message.");
        } finally {
            setSending(false);
        }
    }

    if (loading) {
        return <p>Chargement...</p>;
    }

    return (
        <main>
            <h1>Nous contacter</h1>

            {introMessage && (
                <p style={{ whiteSpace: "pre-line" }}>
                    {introMessage}
                </p>
            )}

            {success && <p role="status">{success}</p>}
            {error && <p role="alert">{error}</p>}

            <form onSubmit={handleSubmit}>
                <div>
                    <label htmlFor="name">Votre nom</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value={form.name}
                        onChange={(event) => updateField("name", event.target.value)}
                        required
                    />
                </div>

                <div>
                    <label htmlFor="email">Votre email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value={form.email}
                        onChange={(event) => updateField("email", event.target.value)}
                        required
                    />
                </div>

                <div>
                    <label htmlFor="message">Votre message</label>
                    <textarea
                        id="message"
                        name="message"
                        rows={5}
                        value={form.message}
                        onChange={(event) => updateField("message", event.target.value)}
                        required
                    />
                </div>

                <button type="submit" disabled={sending}>
                    {sending ? "Envoi en cours..." : "Envoyer"}
                </button>
            </form>
        </main>
    );
}