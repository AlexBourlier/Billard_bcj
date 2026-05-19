import { Helmet } from "react-helmet-async";
import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";

export function CGUPage() {
    return (
        <>
            <Helmet>
                <title>BCJ37 - Conditions générales d'utilisation</title>
                <meta
                    name="description"
                    content="Conditions générales d'utilisation du site du Billard Club de Joué-lès-Tours."
                />
            </Helmet>

            <div className="cgu-layout">
                <section className="cgu" aria-labelledby="cgu-title">
                    <ArticleTitle id="cgu-title">
                        Conditions générales d'utilisation
                    </ArticleTitle>

                    <ArticleCard className="cgu-section">
                        <h2>En vigueur au 01/06/2025</h2>

                        <p>
                            Les présentes conditions générales d'utilisation, dites{" "}
                            <strong>CGU</strong>, ont pour objet l'encadrement juridique
                            des modalités de mise à disposition du site et des services,
                            ainsi que la définition des conditions d'accès et d'utilisation
                            des services par <strong>l'Utilisateur</strong>.
                        </p>

                        <p>
                            Les présentes CGU sont accessibles sur le site à la rubrique{" "}
                            <strong>CGU</strong>.
                        </p>

                        <h3>Article 1 : Mentions légales</h3>

                        <p>
                            L'édition et la direction de la publication du site{" "}
                            <a href="https://bcj37.fr">https://bcj37.fr</a> sont assurées
                            par Alexandre Bourlier, domicilié 13 Allée de la Douzillère,
                            37300 Joué-lès-Tours.
                            <br />
                            Numéro de téléphone : 06 79 05 25 06.
                            <br />
                            Adresse e-mail : contact@bcj37.fr.
                        </p>

                        <p>
                            L'hébergeur du site{" "}
                            <a href="https://bcj37.fr">https://bcj37.fr</a> est la société
                            O2switch, dont le siège social est situé Chemin des Pardiaux,
                            63000 Clermont-Ferrand. Téléphone : 04 44 44 60 40.
                        </p>

                        <h3>Article 2 : Accès au site</h3>

                        <p>
                            Le site <a href="https://bcj37.fr">https://bcj37.fr</a> permet
                            à l'Utilisateur un accès gratuit aux services suivants :
                        </p>

                        <ul>
                            <li>consultation des actualités du club ;</li>
                            <li>consultation des calendriers et événements ;</li>
                            <li>consultation des résultats et classements ;</li>
                            <li>accès aux documents officiels ;</li>
                            <li>présentation du club et des disciplines proposées ;</li>
                            <li>accès aux informations pratiques et au formulaire de contact.</li>
                        </ul>

                        <p>
                            Le site est accessible gratuitement à tout Utilisateur disposant
                            d'un accès à Internet. Les frais liés à l'accès au service
                            restent à la charge de l'Utilisateur.
                        </p>

                        <h3>Article 3 : Collecte des données</h3>

                        <p>
                            Le site assure à l'Utilisateur une collecte et un traitement des
                            données personnelles dans le respect de la vie privée,
                            conformément à la loi Informatique et Libertés et au RGPD.
                        </p>

                        <p>
                            L'Utilisateur dispose d'un droit d'accès, de rectification,
                            de suppression et d'opposition concernant ses données personnelles.
                            Il peut exercer ce droit :
                        </p>

                        <ul>
                            <li>par e-mail à l'adresse contact@bcj37.fr ;</li>
                            <li>via le formulaire de contact du site.</li>
                        </ul>

                        <h3>Article 4 : Propriété intellectuelle</h3>

                        <p>
                            Les marques, logos, signes ainsi que tous les contenus du site
                            font l'objet d'une protection par le Code de la propriété
                            intellectuelle.
                        </p>

                        <p>
                            Toute reproduction, publication ou copie des contenus du site
                            nécessite une autorisation préalable. Toute utilisation commerciale
                            ou publicitaire est interdite sans accord préalable.
                        </p>

                        <h3>Article 5 : Responsabilité</h3>

                        <p>
                            Les informations diffusées sur le site sont réputées fiables,
                            mais le site ne garantit pas qu'elles soient exemptes d'erreurs,
                            d'omissions ou de défauts.
                        </p>

                        <p>
                            Le site ne peut être tenu responsable de l'utilisation ou de
                            l'interprétation des informations publiées, ni d'éventuels dommages
                            liés à l'accès ou à l'utilisation du site.
                        </p>

                        <h3>Article 6 : Liens hypertextes</h3>

                        <p>
                            Des liens hypertextes peuvent être présents sur le site.
                            L'Utilisateur est informé qu'en cliquant sur ces liens, il peut
                            quitter le site <a href="https://bcj37.fr">https://bcj37.fr</a>.
                            Le site ne peut être tenu responsable du contenu des pages externes.
                        </p>

                        <h3>Article 7 : Cookies</h3>

                        <p>
                            Lors de la navigation sur le site, des cookies peuvent être déposés
                            sur le navigateur de l'Utilisateur afin d'améliorer l'expérience
                            de navigation.
                        </p>

                        <p>
                            L'Utilisateur peut accepter, refuser ou désactiver les cookies
                            depuis les paramètres de son navigateur. Certaines fonctionnalités
                            du site peuvent être limitées en cas de refus.
                        </p>

                        <h3>Article 8 : Droit applicable</h3>

                        <p>
                            La législation française s'applique aux présentes CGU. En cas de
                            litige, et à défaut de résolution amiable, les tribunaux français
                            seront seuls compétents.
                        </p>

                        <p>
                            Pour toute question relative aux présentes CGU, l'Utilisateur peut
                            contacter l'éditeur aux coordonnées indiquées à l'article 1.
                        </p>

                        <p className="legal">
                            CGU réalisées à partir d'un modèle proposé par LegalPlace.
                        </p>
                    </ArticleCard>
                </section>
            </div>
        </>
    );
}