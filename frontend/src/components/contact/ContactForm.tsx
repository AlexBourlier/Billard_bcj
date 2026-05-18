import { type FormEvent, useState } from "react";
import { sendContact } from "../../api/contactApi";
import AlertToast from "../ui/AlertToast";

type FormState = {
    name: string;
    email: string;
    message: string;
};

export default function ContactForm() {
    const [form, setForm] = useState<FormState>({
        name: "",
        email: "",
        message: "",
    });

    const [sending, setSending] = useState(false);
    const [success, setSuccess] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

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
        } catch (error) {
            console.error(error);

            setError(
                "Une erreur est survenue lors de l'envoi du message.",
            );
        } finally {
            setSending(false);
        }
    }

    return (
        <div>
            {success && (
                <AlertToast
                    message={success}
                    type="success"
                    onClose={() => setSuccess(null)}
                />
            )}

            {error && (
                <AlertToast
                    message={error}
                    type="error"
                    onClose={() => setError(null)}
                />
            )}

            <form
                onSubmit={handleSubmit}
                aria-busy={sending}
                className="contact-form"
            >
                <div className="form-group">
                    <label htmlFor="name">Votre nom</label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Jean Dupont"
                        aria-label="Votre nom"
                        value={form.name}
                        onChange={(event) =>
                            updateField("name", event.target.value)
                        }
                        required
                    />
                </div>

                <div className="form-group">
                    <label htmlFor="email">Votre email</label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="jean.dupont@example.com"
                        aria-label="Votre email"
                        value={form.email}
                        onChange={(event) =>
                            updateField("email", event.target.value)
                        }
                        required
                    />
                </div>

                <div className="form-group">
                    <label htmlFor="message">
                        Votre message
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows={5}
                        placeholder="Votre message ici..."
                        aria-label="Votre message"
                        value={form.message}
                        onChange={(event) =>
                            updateField("message", event.target.value)
                        }
                        required
                    />
                </div>

                <button
                    type="submit"
                    aria-label="Envoyer le message"
                    disabled={sending}
                    className="contact-form-submit"
                >
                    {sending
                        ? "Envoi en cours..."
                        : "Envoyer"}
                </button>
            </form>
        </div>
    );
}