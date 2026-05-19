import { Helmet } from "react-helmet-async";
import { ArticleCard } from "../components/ui/Card";
import { ArticleTitle } from "../components/ui/Title";

export function PolitiqueConfidentialitePage() {
    return (
        <>
            <Helmet>
                <title>BCJ37 - Politique de confidentialité</title>
                <meta
                    name="description"
                    content="Politique de confidentialité du site du Billard Club de Joué-lès-Tours."
                />
            </Helmet>

            <main className="politique-layout">
                <section className="politique" aria-labelledby="politique-title">
                    <ArticleTitle id="politique-title">
                        Politique de confidentialité
                    </ArticleTitle>

                    <ArticleCard className="politique-section">
                        <h2>En vigueur au 01/06/2025</h2>

                        <h3>Article 1 : Préambule</h3>

                        <p>
                            La présente politique de confidentialité a pour but d'informer
                            les utilisateurs du site sur la manière dont leurs données
                            personnelles sont collectées et traitées.
                        </p>

                        <ul>
                            <li>la nature des données personnelles collectées ;</li>
                            <li>les droits dont disposent les utilisateurs ;</li>
                            <li>le responsable du traitement des données ;</li>
                            <li>les destinataires éventuels de ces données ;</li>
                            <li>la politique du site en matière de cookies.</li>
                        </ul>

                        <p>
                            Cette politique complète les mentions légales et les conditions
                            générales d'utilisation consultables sur le site.
                        </p>

                        <p className="legal-links">
                            <a href="/mentions-legales">Mentions légales</a> |{" "}
                            <a href="/conditions-generales-utilisation">
                                Conditions générales d'utilisation
                            </a>
                        </p>

                        <h3>
                            Article 2 : Principes relatifs à la collecte et au traitement
                            des données personnelles
                        </h3>

                        <p>
                            Conformément à l'article 5 du règlement européen 2016/679,
                            les données à caractère personnel doivent être :
                        </p>

                        <ul>
                            <li>traitées de manière licite, loyale et transparente ;</li>
                            <li>collectées pour des finalités déterminées et légitimes ;</li>
                            <li>limitées à ce qui est nécessaire ;</li>
                            <li>exactes et tenues à jour si nécessaire ;</li>
                            <li>conservées pendant une durée limitée ;</li>
                            <li>protégées par des mesures de sécurité appropriées.</li>
                        </ul>

                        <h3>
                            Article 3 : Données personnelles collectées et traitées
                        </h3>

                        <h4>Article 3.1 : Données collectées</h4>

                        <p>
                            Les données personnelles collectées dans le cadre du site peuvent
                            inclure : nom, prénom, adresse e-mail, numéro de licence sportive
                            et données nécessaires à l'affichage des classements.
                        </p>

                        <p>
                            Ces données sont utilisées pour l'affichage des classements,
                            la gestion des contenus sportifs et le traitement des demandes
                            envoyées via le formulaire de contact.
                        </p>

                        <h4>Article 3.2 : Mode de collecte des données</h4>

                        <p>
                            Lors de l'utilisation du site, certaines données techniques peuvent
                            être collectées automatiquement :
                        </p>

                        <ul>
                            <li>type et version du navigateur utilisé ;</li>
                            <li>système d'exploitation utilisé ;</li>
                            <li>pages consultées ;</li>
                            <li>durée de navigation.</li>
                        </ul>

                        <p>
                            Ces données sont conservées dans des conditions raisonnables de
                            sécurité et uniquement pour la durée nécessaire aux finalités
                            prévues.
                        </p>

                        <h4>Article 3.3 : Hébergement des données</h4>

                        <p>Le site bcj37.fr est hébergé par :</p>

                        <address className="address">
                            O2switch
                            <br />
                            Chemin des Pardiaux, 63000 Clermont-Ferrand
                            <br />
                            Contact : 04 44 44 60 40
                        </address>

                        <h3>
                            Article 4 : Responsable du traitement des données
                        </h3>

                        <p>
                            Le responsable du traitement des données personnelles peut être
                            contacté par e-mail à l'adresse suivante : contact@bcj37.fr.
                        </p>

                        <address className="address">
                            Alexandre Bourlier
                            <br />
                            13 Allée de la Douzillère
                            <br />
                            37300 Joué-lès-Tours
                            <br />
                            adminweb@bcj37.fr
                        </address>

                        <p>
                            Si l'utilisateur estime que ses droits ne sont pas respectés après
                            avoir contacté le responsable du traitement, il peut adresser une
                            réclamation à la CNIL.
                        </p>

                        <h3>
                            Article 5 : Droits de l'utilisateur
                        </h3>

                        <p>
                            Tout utilisateur concerné par le traitement de ses données
                            personnelles dispose des droits suivants :
                        </p>

                        <ul>
                            <li>droit d'accès ;</li>
                            <li>droit de rectification ;</li>
                            <li>droit à l'effacement ;</li>
                            <li>droit à la limitation du traitement ;</li>
                            <li>droit d'opposition ;</li>
                            <li>droit à la portabilité des données ;</li>
                            <li>droit de saisir l'autorité de contrôle compétente.</li>
                        </ul>

                        <p>
                            Pour exercer ses droits, l'utilisateur peut contacter le Billard
                            Club de Joué-lès-Tours par e-mail à contact@bcj37.fr.
                        </p>

                        <p>
                            Le responsable du traitement pourra demander certaines informations
                            afin de vérifier l'identité du demandeur.
                        </p>

                        <p>
                            Plus d'informations sont disponibles sur le site de la CNIL :{" "}
                            <a
                                href="https://www.cnil.fr"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                cnil.fr
                            </a>
                        </p>

                        <h3>
                            Article 6 : Modification de la politique de confidentialité
                        </h3>

                        <p>
                            L'éditeur du site bcj37.fr se réserve le droit de modifier la
                            présente politique à tout moment afin de garantir sa conformité
                            avec le droit en vigueur.
                        </p>

                        <p>
                            L'utilisateur est invité à consulter régulièrement cette politique
                            de confidentialité.
                        </p>

                        <p>La présente politique a été éditée le 30 mai 2025.</p>

                        <p className="legal">
                            Politique de confidentialité réalisée à partir d'un modèle proposé
                            par LegalPlace.
                        </p>
                    </ArticleCard>
                </section>
            </main>
        </>
    );
}