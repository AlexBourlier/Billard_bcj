import { Helmet } from "react-helmet-async";
import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";

export function MentionsPage() {
    return (
        <>
            <Helmet>
                <title>BCJ37 - Mentions légales</title>
                <meta
                    name="description"
                    content="Mentions légales du site du Billard Club de Joué-lès-Tours."
                />
            </Helmet>

            <div className="mentions-layout">
                <section className="mentions" aria-labelledby="mentions-title">
                    <ArticleTitle id="mentions-title">Mentions légales</ArticleTitle>

                    <ArticleCard className="mentions-section">
                        <h2>En vigueur au 01/06/2025</h2>

                        <p>
                            Conformément aux dispositions de la loi n°2004-575 du 21 juin
                            2004 pour la confiance en l'économie numérique, il est porté à la
                            connaissance des utilisateurs et visiteurs, ci-après{" "}
                            <strong>l'Utilisateur</strong>, du site{" "}
                            <a href="https://bcj37.fr">https://bcj37.fr</a>, ci-après le{" "}
                            <strong>Site</strong>, les présentes mentions légales.
                        </p>

                        <p>
                            La connexion et la navigation sur le Site par l'Utilisateur
                            impliquent l'acceptation intégrale et sans réserve des présentes
                            mentions légales.
                        </p>

                        <p>
                            Ces dernières sont accessibles sur le Site à la rubrique{" "}
                            <strong>Mentions légales</strong>.
                        </p>

                        <h3>Édition du site</h3>

                        <p>
                            L'édition et la direction de la publication du Site sont assurées
                            par Alexandre Bourlier, domicilié 13 Allée de la Douzillère,
                            37300 Joué-lès-Tours.
                            <br />
                            Téléphone : 06 79 05 25 06.
                            <br />
                            Adresse e-mail : adminweb@bcj37.fr.
                        </p>

                        <h3>Hébergeur</h3>

                        <p>
                            L'hébergeur du Site est la société O2switch, dont le siège social
                            est situé Chemin des Pardiaux, 63000 Clermont-Ferrand.
                            <br />
                            Téléphone : 04 44 44 60 40.
                        </p>

                        <h3>Accès au site</h3>

                        <p>
                            Le Site est normalement accessible à tout moment à l'Utilisateur.
                            Toutefois, l'Éditeur pourra suspendre, limiter ou interrompre
                            l'accès au Site afin de procéder notamment à des mises à jour ou à
                            des modifications de son contenu.
                        </p>

                        <p>
                            L'Éditeur ne pourra être tenu responsable des conséquences
                            éventuelles de cette indisponibilité sur les activités de
                            l'Utilisateur.
                        </p>

                        <h3>Collecte des données</h3>

                        <p>
                            Le Site assure à l'Utilisateur une collecte et un traitement des
                            données personnelles dans le respect de la vie privée,
                            conformément à la loi n°78-17 du 6 janvier 1978 relative à
                            l'informatique, aux fichiers et aux libertés, ainsi qu'au règlement
                            européen 2016/679 du 27 avril 2016, dit RGPD.
                        </p>

                        <p>
                            L'Utilisateur dispose d'un droit d'accès, de rectification, de
                            suppression et d'opposition concernant ses données personnelles.
                            Il peut exercer ce droit :
                        </p>

                        <ul>
                            <li>par e-mail à l'adresse contact@bcj37.fr ;</li>
                            <li>depuis le formulaire de contact du site.</li>
                        </ul>

                        <h3>Propriété intellectuelle</h3>

                        <p>
                            Toute utilisation, reproduction, diffusion, commercialisation ou
                            modification de tout ou partie du Site, sans autorisation expresse
                            de l'Éditeur, est interdite et pourra entraîner des actions et
                            poursuites judiciaires conformément à la réglementation en vigueur.
                        </p>

                        <p className="legal">
                            Rédigé à partir d'un modèle proposé par LegalPlace.
                        </p>
                    </ArticleCard>
                </section>
            </div>
        </>
    );
}