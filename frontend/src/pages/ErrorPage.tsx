import { Link } from "react-router-dom";
import "../styles/error-page.css";
import errorImage from "../assets/errors/404-billard.webp";

interface ErrorPageProps {
    code?: string;
    title?: string;
    message?: string;
}

export function ErrorPage({
    code = "500",
    title = "Erreur",
    message = "Une erreur est survenue.",
}: ErrorPageProps) {
    return (
        <div className="error-page-section">
            <section className="error-page__content">

                <img
                    src={errorImage}
                    alt=""
                    className="error-page__image"
                    aria-hidden="true"
                    decoding="async"
                />

                <p className="error-page__code">
                    {code}
                </p>

                <h1 className="error-page__title">
                    {title}
                </h1>

                <p className="error-page__text">
                    {message}
                </p>

                <div className="error-page__actions">
                    <Link
                        to="/"
                        className="error-page__button"
                    >
                        Retour à l’accueil
                    </Link>
                </div>
            </section>
        </div>
    );
}