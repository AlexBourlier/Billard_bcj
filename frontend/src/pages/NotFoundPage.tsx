import { Link } from "react-router-dom";
import "../styles/error-page.css";
import errorImage from "../assets/errors/404-billard.webp";
import { Helmet } from "react-helmet-async";

export function NotFoundPage() {
    return (
        <>
        <Helmet>
            <title>BCJ37 - Billard Club de Joué-lès-Tours</title>

            <meta
                name="description"
                content="Club de billard à Joué-lès-Tours : blackball, carambole, snooker, compétitions et actualités."
            />
        </Helmet>
        <div className="error-page-section">
            <section className="error-page__content">
                <img
                    src={errorImage}
                    alt=""
                    className="error-page__image"
                    aria-hidden="true"
                />

                <p className="error-page__code">404</p>

                <h2 id="not-found-title" className="error-page__title">
                    Page introuvable
                </h2>

                <p className="error-page__text">
                    La page demandée n’existe pas ou a été déplacée.
                </p>

                <div className="error-page__actions">
                    <Link to="/" className="error-page__button">
                        Retour à l’accueil
                    </Link>

                    <Link to="/club" className="error-page__link">
                        Voir les actualités
                    </Link>
                </div>
            </section>
        </div>
        </>
    );
}