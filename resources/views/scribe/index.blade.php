<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>BCJ - Billard club de Joué-Lès-Tours API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.9.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.9.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-calendars" class="tocify-header">
                <li class="tocify-item level-1" data-unique="calendars">
                    <a href="#calendars">Calendars</a>
                </li>
                                    <ul id="tocify-subheader-calendars" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="calendars-GETapi-v1-calendrier">
                                <a href="#calendars-GETapi-v1-calendrier">Retourne la liste de tous les calendriers actifs.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="calendars-GETapi-v1-calendrier--discipline-">
                                <a href="#calendars-GETapi-v1-calendrier--discipline-">Retourne les calendriers actifs pour une discipline donnée.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="calendars-GETapi-v1-calendrier--discipline---scope-">
                                <a href="#calendars-GETapi-v1-calendrier--discipline---scope-">Retourne un calendrier spécifique (discipline + scope) avec ses événements.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-disciplines" class="tocify-header">
                <li class="tocify-item level-1" data-unique="disciplines">
                    <a href="#disciplines">Disciplines</a>
                </li>
                                    <ul id="tocify-subheader-disciplines" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="disciplines-GETapi-v1-disciplines--discipline-">
                                <a href="#disciplines-GETapi-v1-disciplines--discipline-">Détail d'une discipline</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="disciplines-GETapi-v1-disciplines--discipline--rankings-preview">
                                <a href="#disciplines-GETapi-v1-disciplines--discipline--rankings-preview">Aperçu des classements CueScore</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-public-site">
                                <a href="#endpoints-GETapi-v1-public-site">Retourne les informations globales du site.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-public-home">
                                <a href="#endpoints-GETapi-v1-public-home">Retourne les données nécessaires à la page d’accueil publique.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-partenaires">
                                <a href="#endpoints-GETapi-v1-partenaires">Retourne la liste de tous les partenaires.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts">
                                <a href="#endpoints-GETapi-v1-posts">Retourne la liste paginée des articles.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts-favoris">
                                <a href="#endpoints-GETapi-v1-posts-favoris">Retourne les articles marqués comme favoris.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts-discipline--discipline-">
                                <a href="#endpoints-GETapi-v1-posts-discipline--discipline-">Retourne les articles d'une discipline donnée.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts-slug--slug-">
                                <a href="#endpoints-GETapi-v1-posts-slug--slug-">Retourne un article via son slug.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts-decade--year-">
                                <a href="#endpoints-GETapi-v1-posts-decade--year-">Retourne les articles d'une décennie donnée.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts-year--year-">
                                <a href="#endpoints-GETapi-v1-posts-year--year-">Retourne les articles d'une année donnée.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts--id-">
                                <a href="#endpoints-GETapi-v1-posts--id-">Retourne un article par son identifiant.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-licencies">
                                <a href="#endpoints-GETapi-v1-licencies">Retourne la liste complète des licenciés.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-licencies-search--name-">
                                <a href="#endpoints-GETapi-v1-licencies-search--name-">Recherche des licenciés par nom, prénom ou numéro de licence.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-license-import-batches">
                                <a href="#endpoints-GETapi-v1-license-import-batches">Retourne la liste paginée des batchs d'import de licences.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-license-import-batches--batch_id-">
                                <a href="#endpoints-GETapi-v1-license-import-batches--batch_id-">Retourne le rapport complet d’un batch d’import.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-license-import-batches--batch_id--report">
                                <a href="#endpoints-GETapi-v1-license-import-batches--batch_id--report">Retourne le rapport complet d’un batch d’import.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-license-import-batches--batch_id--diff">
                                <a href="#endpoints-GETapi-v1-license-import-batches--batch_id--diff">Retourne uniquement le diff de projection d’un batch.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-cuescore-rankings">
                                <a href="#endpoints-GETapi-v1-cuescore-rankings">Liste les classements CueScore avec filtres optionnels.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-cuescore-club">
                                <a href="#endpoints-GETapi-v1-cuescore-club">Vue agrégée des classements club basée sur des filtres query.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-cuescore-rankings--ranking_id-">
                                <a href="#endpoints-GETapi-v1-cuescore-rankings--ranking_id-">Retourne les informations d’un classement avec son fetch actif.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-cuescore-rankings--ranking_id--club">
                                <a href="#endpoints-GETapi-v1-cuescore-rankings--ranking_id--club">Retourne les données club d’un classement (individuel ou équipe).</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-cuescore-rankings--ranking_id--teams">
                                <a href="#endpoints-GETapi-v1-cuescore-rankings--ranking_id--teams">Retourne uniquement les classements équipes pour un ranking.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-cuescore--discipline---scope---rankingType-">
                                <a href="#endpoints-GETapi-v1-cuescore--discipline---scope---rankingType-">Vue agrégée des classements club via paramètres d’URL.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-documents">
                                <a href="#endpoints-GETapi-v1-documents">Retourne la liste de tous les documents.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-documents--discipline-">
                                <a href="#endpoints-GETapi-v1-documents--discipline-">Retourne les documents pour une discipline donnée.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-documents--discipline---id-">
                                <a href="#endpoints-GETapi-v1-documents--discipline---id-">Retourne un document spécifique pour une discipline.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-contact">
                                <a href="#endpoints-GETapi-v1-contact">Retourne la liste des contacts disponibles.</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: April 25, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>This API is not authenticated.</p>

        <h1 id="calendars">Calendars</h1>

    <p>Expose les endpoints publics permettant de :</p>
<ul>
<li>récupérer la liste des calendriers actifs</li>
<li>filtrer par discipline</li>
<li>récupérer un calendrier détaillé avec ses événements</li>
</ul>
<p>Les réponses respectent le format standard :
data / meta / links / error</p>

                                <h2 id="calendars-GETapi-v1-calendrier">Retourne la liste de tous les calendriers actifs.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-calendrier">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/calendrier" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/calendrier"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-calendrier">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 42
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 12,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;departemental&quot;,
            &quot;name&quot;: &quot;Americain - Departemental&quot;,
            &quot;slug&quot;: &quot;americain-departemental&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Americain - Departemental&quot;
        },
        {
            &quot;id&quot;: 9,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;international&quot;,
            &quot;name&quot;: &quot;Americain - International&quot;,
            &quot;slug&quot;: &quot;americain-international&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Americain - International&quot;
        },
        {
            &quot;id&quot;: 10,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Americain - National&quot;,
            &quot;slug&quot;: &quot;americain-national&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Americain - National&quot;
        },
        {
            &quot;id&quot;: 11,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;name&quot;: &quot;Americain - Regional&quot;,
            &quot;slug&quot;: &quot;americain-regional&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Americain - Regional&quot;
        },
        {
            &quot;id&quot;: 16,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;departemental&quot;,
            &quot;name&quot;: &quot;Blackball - Departemental&quot;,
            &quot;slug&quot;: &quot;blackball-departemental&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Blackball - Departemental&quot;
        },
        {
            &quot;id&quot;: 13,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;international&quot;,
            &quot;name&quot;: &quot;Blackball - International&quot;,
            &quot;slug&quot;: &quot;blackball-international&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Blackball - International&quot;
        },
        {
            &quot;id&quot;: 14,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Blackball - National&quot;,
            &quot;slug&quot;: &quot;blackball-national&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Blackball - National&quot;
        },
        {
            &quot;id&quot;: 15,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;name&quot;: &quot;Blackball - Regional&quot;,
            &quot;slug&quot;: &quot;blackball-regional&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Blackball - Regional&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;scope&quot;: &quot;departemental&quot;,
            &quot;name&quot;: &quot;Carambole - Departemental&quot;,
            &quot;slug&quot;: &quot;carambole-departemental&quot;,
            &quot;source_type&quot;: &quot;manual&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Carambole - Departemental&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;scope&quot;: &quot;international&quot;,
            &quot;name&quot;: &quot;Carambole - International&quot;,
            &quot;slug&quot;: &quot;carambole-international&quot;,
            &quot;source_type&quot;: &quot;manual&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Carambole - International&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Carambole - National&quot;,
            &quot;slug&quot;: &quot;carambole-national&quot;,
            &quot;source_type&quot;: &quot;manual&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Carambole - National&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;name&quot;: &quot;Carambole - Regional&quot;,
            &quot;slug&quot;: &quot;carambole-regional&quot;,
            &quot;source_type&quot;: &quot;manual&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Carambole - Regional&quot;
        },
        {
            &quot;id&quot;: 8,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;departemental&quot;,
            &quot;name&quot;: &quot;Snooker - Departemental&quot;,
            &quot;slug&quot;: &quot;snooker-departemental&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Snooker - Departemental&quot;
        },
        {
            &quot;id&quot;: 5,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;international&quot;,
            &quot;name&quot;: &quot;Snooker - International&quot;,
            &quot;slug&quot;: &quot;snooker-international&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Snooker - International&quot;
        },
        {
            &quot;id&quot;: 6,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Snooker - National&quot;,
            &quot;slug&quot;: &quot;snooker-national&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Snooker - National&quot;
        },
        {
            &quot;id&quot;: 7,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;name&quot;: &quot;Snooker - Regional&quot;,
            &quot;slug&quot;: &quot;snooker-regional&quot;,
            &quot;source_type&quot;: &quot;cuescore&quot;,
            &quot;is_active&quot;: true,
            &quot;display_name&quot;: &quot;Snooker - Regional&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 16
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-calendrier" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-calendrier"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-calendrier"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-calendrier" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-calendrier">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-calendrier" data-method="GET"
      data-path="api/v1/calendrier"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-calendrier', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-calendrier"
                    onclick="tryItOut('GETapi-v1-calendrier');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-calendrier"
                    onclick="cancelTryOut('GETapi-v1-calendrier');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-calendrier"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/calendrier</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-calendrier"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-calendrier"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="calendars-GETapi-v1-calendrier--discipline-">Retourne les calendriers actifs pour une discipline donnée.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-calendrier--discipline-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/calendrier/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/calendrier/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-calendrier--discipline-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 41
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Discipline invalide.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-calendrier--discipline-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-calendrier--discipline-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-calendrier--discipline-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-calendrier--discipline-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-calendrier--discipline-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-calendrier--discipline-" data-method="GET"
      data-path="api/v1/calendrier/{discipline}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-calendrier--discipline-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-calendrier--discipline-"
                    onclick="tryItOut('GETapi-v1-calendrier--discipline-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-calendrier--discipline-"
                    onclick="cancelTryOut('GETapi-v1-calendrier--discipline-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-calendrier--discipline-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/calendrier/{discipline}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-calendrier--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-calendrier--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-calendrier--discipline-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="calendars-GETapi-v1-calendrier--discipline---scope-">Retourne un calendrier spécifique (discipline + scope) avec ses événements.</h2>

<p>
</p>

<p>Inclut :</p>
<ul>
<li>les informations du calendrier</li>
<li>la liste des événements associés</li>
<li>les liens liés aux événements</li>
</ul>

<span id="example-requests-GETapi-v1-calendrier--discipline---scope-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/calendrier/architecto/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/calendrier/architecto/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-calendrier--discipline---scope-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 40
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Discipline invalide.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-calendrier--discipline---scope-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-calendrier--discipline---scope-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-calendrier--discipline---scope-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-calendrier--discipline---scope-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-calendrier--discipline---scope-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-calendrier--discipline---scope-" data-method="GET"
      data-path="api/v1/calendrier/{discipline}/{scope}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-calendrier--discipline---scope-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-calendrier--discipline---scope-"
                    onclick="tryItOut('GETapi-v1-calendrier--discipline---scope-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-calendrier--discipline---scope-"
                    onclick="cancelTryOut('GETapi-v1-calendrier--discipline---scope-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-calendrier--discipline---scope-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/calendrier/{discipline}/{scope}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-calendrier--discipline---scope-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-calendrier--discipline---scope-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-calendrier--discipline---scope-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>scope</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scope"                data-endpoint="GETapi-v1-calendrier--discipline---scope-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="disciplines">Disciplines</h1>

    <p>La réponse est mise en cache pendant 10 minutes.</p>

                                <h2 id="disciplines-GETapi-v1-disciplines--discipline-">Détail d&#039;une discipline</h2>

<p>
</p>

<p>Retourne les données publiques nécessaires à la page d’une discipline :
articles, événements calendrier, documents et classements actifs.</p>

<span id="example-requests-GETapi-v1-disciplines--discipline-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/disciplines/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/disciplines/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-disciplines--discipline-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;posts&quot;: [],
        &quot;calendar&quot;: [],
        &quot;documents&quot;: [],
        &quot;rankings&quot;: []
    },
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;posts_count&quot;: 0,
        &quot;calendar_count&quot;: 0,
        &quot;documents_count&quot;: 0,
        &quot;rankings_count&quot;: 0
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: {
        &quot;code&quot;: &quot;discipline_not_found&quot;,
        &quot;message&quot;: &quot;Discipline not found&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-disciplines--discipline-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-disciplines--discipline-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-disciplines--discipline-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-disciplines--discipline-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-disciplines--discipline-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-disciplines--discipline-" data-method="GET"
      data-path="api/v1/disciplines/{discipline}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-disciplines--discipline-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-disciplines--discipline-"
                    onclick="tryItOut('GETapi-v1-disciplines--discipline-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-disciplines--discipline-"
                    onclick="cancelTryOut('GETapi-v1-disciplines--discipline-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-disciplines--discipline-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/disciplines/{discipline}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-disciplines--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-disciplines--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-disciplines--discipline-"
               value="architecto"
               data-component="url">
    <br>
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="disciplines-GETapi-v1-disciplines--discipline--rankings-preview">Aperçu des classements CueScore</h2>

<p>
</p>

<p>Retourne un aperçu des classements CueScore pour une discipline donnée.</p>

<span id="example-requests-GETapi-v1-disciplines--discipline--rankings-preview">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/disciplines/architecto/rankings-preview?limit=16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/disciplines/architecto/rankings-preview"
);

const params = {
    "limit": "16",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-disciplines--discipline--rankings-preview">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;national&quot;: [
            {
                &quot;ranking&quot;: {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Classement national&quot;,
                    &quot;cuescore_id&quot;: &quot;123456&quot;,
                    &quot;url&quot;: &quot;https://cuescore.com/ranking/example&quot;,
                    &quot;source_type&quot;: &quot;ranking&quot;,
                    &quot;discipline&quot;: &quot;blackball&quot;,
                    &quot;scope&quot;: &quot;national&quot;,
                    &quot;ranking_type&quot;: &quot;individual&quot;,
                    &quot;team_category&quot;: null,
                    &quot;season&quot;: &quot;2025-2026&quot;,
                    &quot;is_active&quot;: true,
                    &quot;sort_order&quot;: 1
                },
                &quot;entries&quot;: [
                    {
                        &quot;rank_position&quot;: 1,
                        &quot;participant_name&quot;: &quot;John Doe&quot;,
                        &quot;participant_external_id&quot;: &quot;123456&quot;,
                        &quot;participant_url&quot;: &quot;https://cuescore.com/player/John+Doe/123456&quot;,
                        &quot;points&quot;: &quot;1200.00&quot;,
                        &quot;played&quot;: null,
                        &quot;wins&quot;: null,
                        &quot;losses&quot;: null,
                        &quot;ties&quot;: null,
                        &quot;matching&quot;: {
                            &quot;method&quot;: &quot;exact_normalized&quot;,
                            &quot;confidence_score&quot;: 100,
                            &quot;is_confirmed&quot;: true
                        },
                        &quot;licencie&quot;: {
                            &quot;id&quot;: 1,
                            &quot;licence&quot;: &quot;123456 A&quot;,
                            &quot;nom&quot;: &quot;DOE&quot;,
                            &quot;prenom&quot;: &quot;JOHN&quot;
                        }
                    }
                ],
                &quot;meta&quot;: []
            }
        ]
    },
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;count&quot;: 1,
        &quot;limit&quot;: 5,
        &quot;rankings_supported&quot;: true
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: {
        &quot;code&quot;: &quot;discipline_not_found&quot;,
        &quot;message&quot;: &quot;Discipline not found&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-disciplines--discipline--rankings-preview" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-disciplines--discipline--rankings-preview"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-disciplines--discipline--rankings-preview"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-disciplines--discipline--rankings-preview" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-disciplines--discipline--rankings-preview">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-disciplines--discipline--rankings-preview" data-method="GET"
      data-path="api/v1/disciplines/{discipline}/rankings-preview"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-disciplines--discipline--rankings-preview', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-disciplines--discipline--rankings-preview"
                    onclick="tryItOut('GETapi-v1-disciplines--discipline--rankings-preview');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-disciplines--discipline--rankings-preview"
                    onclick="cancelTryOut('GETapi-v1-disciplines--discipline--rankings-preview');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-disciplines--discipline--rankings-preview"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/disciplines/{discipline}/rankings-preview</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-disciplines--discipline--rankings-preview"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-disciplines--discipline--rankings-preview"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-disciplines--discipline--rankings-preview"
               value="architecto"
               data-component="url">
    <br>
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="limit"                data-endpoint="GETapi-v1-disciplines--discipline--rankings-preview"
               value="16"
               data-component="query">
    <br>
<p>Nombre maximum d’entrées individuelles par classement. Min: 1. Max: 10. Default: 5. Exemple : 5 Example: <code>16</code></p>
            </div>
                </form>

                <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-v1-public-site">Retourne les informations globales du site.</h2>

<p>
</p>

<p>Inclut :</p>
<ul>
<li>paramètres du site</li>
<li>menus actifs</li>
</ul>

<span id="example-requests-GETapi-v1-public-site">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/public/site" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/public/site"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-public-site">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;site_settings&quot;: {
            &quot;id&quot;: 1,
            &quot;logo&quot;: &quot;img/a97fed9810c39a9270caead79d014450.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/img/a97fed9810c39a9270caead79d014450.png&quot;,
            &quot;banniere&quot;: &quot;img/fee04cd87ca0f3ab97bbc5a30d47bafd.png&quot;,
            &quot;banniere_url&quot;: &quot;http://localhost:8000/img/fee04cd87ca0f3ab97bbc5a30d47bafd.png&quot;,
            &quot;adresse&quot;: &quot;28 Rue Joseph Cugnot, 37300 Jou&eacute;-L&egrave;s-Tours&quot;,
            &quot;telephone&quot;: null,
            &quot;email&quot;: &quot;contact@bcj37.fr&quot;,
            &quot;youtube_page&quot;: &quot;https://www.youtube.com/@BCJ37&quot;,
            &quot;facebook_page&quot;: &quot;https://www.facebook.com/profile.php?id=61573797213739&amp;locale=fr_FR&quot;,
            &quot;facebook_page_id&quot;: &quot;554099791128649&quot;,
            &quot;created_at&quot;: &quot;2025-05-13T10:22:11.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-08-21T15:12:15.000000Z&quot;
        },
        &quot;menus&quot;: [
            {
                &quot;id&quot;: 5,
                &quot;name&quot;: &quot;americain&quot;,
                &quot;image&quot;: &quot;menu/Image_Americain_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Americain_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2026-01-10T16:53:05.000000Z&quot;
            },
            {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;blackball&quot;,
                &quot;image&quot;: &quot;menu/Image_Blackball_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Blackball_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-29T17:06:39.000000Z&quot;
            },
            {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;carambole&quot;,
                &quot;image&quot;: &quot;menu/Image_Carambole_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Carambole_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-14T07:36:52.000000Z&quot;
            },
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;club&quot;,
                &quot;image&quot;: &quot;menu/Image_Leclub_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Leclub_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-29T17:06:41.000000Z&quot;
            },
            {
                &quot;id&quot;: 4,
                &quot;name&quot;: &quot;snooker&quot;,
                &quot;image&quot;: &quot;menu/Image_Snooker_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Snooker_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-14T08:18:08.000000Z&quot;
            }
        ]
    },
    &quot;meta&quot;: {
        &quot;menus_count&quot;: 5
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-public-site" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-public-site"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-public-site"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-public-site" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-public-site">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-public-site" data-method="GET"
      data-path="api/v1/public/site"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-public-site', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-public-site"
                    onclick="tryItOut('GETapi-v1-public-site');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-public-site"
                    onclick="cancelTryOut('GETapi-v1-public-site');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-public-site"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/public/site</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-public-site"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-public-site"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-public-home">Retourne les données nécessaires à la page d’accueil publique.</h2>

<p>
</p>

<p>La réponse est mise en cache pendant 10 minutes.</p>
<p>Inclut :</p>
<ul>
<li>paramètres du site</li>
<li>menus actifs</li>
<li>partenaires</li>
<li>article favori ou dernier article publié en fallback</li>
</ul>

<span id="example-requests-GETapi-v1-public-home">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/public/home" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/public/home"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-public-home">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;site_settings&quot;: {
            &quot;id&quot;: 1,
            &quot;logo&quot;: &quot;img/a97fed9810c39a9270caead79d014450.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/img/a97fed9810c39a9270caead79d014450.png&quot;,
            &quot;banniere&quot;: &quot;img/fee04cd87ca0f3ab97bbc5a30d47bafd.png&quot;,
            &quot;banniere_url&quot;: &quot;http://localhost:8000/img/fee04cd87ca0f3ab97bbc5a30d47bafd.png&quot;,
            &quot;adresse&quot;: &quot;28 Rue Joseph Cugnot, 37300 Jou&eacute;-L&egrave;s-Tours&quot;,
            &quot;telephone&quot;: null,
            &quot;email&quot;: &quot;contact@bcj37.fr&quot;,
            &quot;youtube_page&quot;: &quot;https://www.youtube.com/@BCJ37&quot;,
            &quot;facebook_page&quot;: &quot;https://www.facebook.com/profile.php?id=61573797213739&amp;locale=fr_FR&quot;,
            &quot;facebook_page_id&quot;: &quot;554099791128649&quot;,
            &quot;created_at&quot;: &quot;2025-05-13T10:22:11.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-08-21T15:12:15.000000Z&quot;
        },
        &quot;menus&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;club&quot;,
                &quot;image&quot;: &quot;menu/Image_Leclub_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Leclub_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-29T17:06:41.000000Z&quot;
            },
            {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;blackball&quot;,
                &quot;image&quot;: &quot;menu/Image_Blackball_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Blackball_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-29T17:06:39.000000Z&quot;
            },
            {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;carambole&quot;,
                &quot;image&quot;: &quot;menu/Image_Carambole_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Carambole_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-14T07:36:52.000000Z&quot;
            },
            {
                &quot;id&quot;: 4,
                &quot;name&quot;: &quot;snooker&quot;,
                &quot;image&quot;: &quot;menu/Image_Snooker_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Snooker_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-14T08:18:08.000000Z&quot;
            },
            {
                &quot;id&quot;: 5,
                &quot;name&quot;: &quot;americain&quot;,
                &quot;image&quot;: &quot;menu/Image_Americain_color.png&quot;,
                &quot;image_url&quot;: &quot;http://localhost:8000/menu/Image_Americain_color.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2026-01-10T16:53:05.000000Z&quot;
            }
        ],
        &quot;partners&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Tours m&eacute;tropole&quot;,
                &quot;logo&quot;: &quot;partenaires/37b4f8ae3d9d972bd5bf0d208c9b6db4.png&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/37b4f8ae3d9d972bd5bf0d208c9b6db4.png&quot;,
                &quot;website_url&quot;: &quot;https://www.tours-metropole.fr&quot;,
                &quot;created_at&quot;: &quot;2025-05-26T14:21:37.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:52:52.000000Z&quot;
            },
            {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;Jou&eacute; les Tours&quot;,
                &quot;logo&quot;: &quot;partenaires/fcdc86350be85448befa128dd97cb678.jpg&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/fcdc86350be85448befa128dd97cb678.jpg&quot;,
                &quot;website_url&quot;: &quot;https://www.jouelestours.fr&quot;,
                &quot;created_at&quot;: &quot;2025-05-26T14:31:35.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:55:45.000000Z&quot;
            },
            {
                &quot;id&quot;: 4,
                &quot;name&quot;: &quot;FF billard LBCVL&quot;,
                &quot;logo&quot;: &quot;partenaires/2dc778dbe66b410464d2028a657a27a8.png&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/2dc778dbe66b410464d2028a657a27a8.png&quot;,
                &quot;website_url&quot;: &quot;https://ligue-billard-centre-val-de-loire.fr&quot;,
                &quot;created_at&quot;: &quot;2025-05-26T14:32:09.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:56:44.000000Z&quot;
            },
            {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;Handi sport&quot;,
                &quot;logo&quot;: &quot;partenaires/161322e89b4e12b0baed38f5f875fbe4.png&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/161322e89b4e12b0baed38f5f875fbe4.png&quot;,
                &quot;website_url&quot;: &quot;https://www.handisport.org&quot;,
                &quot;created_at&quot;: &quot;2025-05-26T14:32:44.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:57:10.000000Z&quot;
            },
            {
                &quot;id&quot;: 11,
                &quot;name&quot;: &quot;FFSA&quot;,
                &quot;logo&quot;: &quot;partenaires/75a23ee4123c3eddd989e2b64e391239.png&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/75a23ee4123c3eddd989e2b64e391239.png&quot;,
                &quot;website_url&quot;: &quot;https://sportadapte.fr&quot;,
                &quot;created_at&quot;: &quot;2025-08-19T11:30:46.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:58:53.000000Z&quot;
            },
            {
                &quot;id&quot;: 12,
                &quot;name&quot;: &quot;Bulldog-billard&quot;,
                &quot;logo&quot;: &quot;partenaires/12e2647c9ac9a25f6f34d4d0217c63ce.png&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/12e2647c9ac9a25f6f34d4d0217c63ce.png&quot;,
                &quot;website_url&quot;: &quot;https://bulldog-billard.com&quot;,
                &quot;created_at&quot;: &quot;2025-08-19T11:31:12.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:59:21.000000Z&quot;
            },
            {
                &quot;id&quot;: 16,
                &quot;name&quot;: &quot;Brit Hotel Jou&eacute;-l&egrave;s-Tours&quot;,
                &quot;logo&quot;: &quot;partenaires/12a05680-30c8-403e-bc59-085f4b8a3d81.webp&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/12a05680-30c8-403e-bc59-085f4b8a3d81.webp&quot;,
                &quot;website_url&quot;: &quot;https://hotel-tours.brithotel.fr&quot;,
                &quot;created_at&quot;: &quot;2025-12-05T09:59:48.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T21:00:00.000000Z&quot;
            },
            {
                &quot;id&quot;: 17,
                &quot;name&quot;: &quot;Kyriad Jou&eacute;-L&egrave;s-Tours&quot;,
                &quot;logo&quot;: &quot;partenaires/87d7527e-b0cc-43a8-ab2c-f9b17e9f950a.webp&quot;,
                &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/87d7527e-b0cc-43a8-ab2c-f9b17e9f950a.webp&quot;,
                &quot;website_url&quot;: &quot;https://tours-joue-les-tours.kyriad.com/fr-fr/?sr=SEO_GOOGLE&quot;,
                &quot;created_at&quot;: &quot;2025-12-05T10:01:52.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T21:00:47.000000Z&quot;
            }
        ],
        &quot;featured_post&quot;: {
            &quot;id&quot;: 112,
            &quot;title&quot;: &quot;2026 - Tournoi National n&deg;4 - Villeneuve-sur-Lot&quot;,
            &quot;slug&quot;: &quot;2026-tournoi-national-n4-villeneuve-sur-lot&quot;,
            &quot;excerpt&quot;: &quot;&amp;nbsp;Du 31 janvier au 1er f&eacute;vrier 2026Un week-end intense pour nos joueurs, avec de tr&egrave;s beaux r&eacute;sultats &agrave; la cl&eacute;.&amp;nbsp;Blackball MasterVictoire d&rsquo;Al...&quot;,
            &quot;content&quot;: &quot;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t7e/1/16/1f4c5.png\&quot; alt=\&quot;📅\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;em&gt;Du 31 janvier au 1er f&eacute;vrier 2026&lt;/em&gt;&lt;/strong&gt;&lt;/h5&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Un week-end intense pour nos joueurs, avec de tr&egrave;s beaux r&eacute;sultats &agrave; la cl&eacute;.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tbe/1/16/1f3c6.png\&quot; alt=\&quot;🏆\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Blackball Master&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Victoire d&rsquo;Alex Buscetti&amp;nbsp;&mdash;&amp;nbsp;&lt;em&gt;sa premi&egrave;re, et avec la mani&egrave;re !&lt;/em&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Score final :&amp;nbsp;8&ndash;4, un match parfaitement ma&icirc;tris&eacute;. Gr&acirc;ce &agrave; cette performance,&amp;nbsp;Alex grimpe &agrave; la 3ᵉ place du classement g&eacute;n&eacute;ral.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&amp;nbsp;Christophe Lambert&amp;nbsp;s&rsquo;arr&ecirc;te en&amp;nbsp;1/2 finale&lt;/li&gt;&lt;li&gt;&amp;nbsp;&Eacute;lie Christidis&amp;nbsp;atteint les&amp;nbsp;1/4 de finale&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/te9/1/16/2640.png\&quot; alt=\&quot;♀️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Cat&eacute;gorie F&eacute;minine&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Oph&eacute;lie Laval&amp;nbsp;r&eacute;alise un tr&egrave;s beau parcours et atteint la&amp;nbsp;finale&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Un jeu solide tout au long du tournoi, mais la victoire lui &eacute;chappe.&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Elle se positionne d&eacute;sormais&amp;nbsp;5ᵉ au classement g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8d/1/16/1f500.png\&quot; alt=\&quot;🔀\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Cat&eacute;gorie Mixte&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;J&eacute;r&ocirc;me L&#039;Anthoen&amp;nbsp;atteint les&amp;nbsp;1/4 de finale&amp;nbsp;apr&egrave;s&amp;nbsp;4 matchs remport&eacute;s.&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Malgr&eacute; une d&eacute;faite au 1er tour du tournoi v&eacute;t&eacute;ran, il r&eacute;alise une tr&egrave;s belle op&eacute;ration au mixte.&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;4ᵉ place au classement g&eacute;n&eacute;ral&amp;nbsp;!&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8b/1/16/1f465.png\&quot; alt=\&quot;👥\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Comp&eacute;tition &Eacute;quipes &ndash; DN1&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Jou&eacute; 1&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;3 victoires en 3 matchs&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Dernier match remport&eacute; avec le&amp;nbsp;point offensif&amp;nbsp;et&amp;nbsp;5 fermes&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Jou&eacute; 1 conserve sa 2ᵉ place au classement&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Jou&eacute; 2&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Victoire lors du premier match&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;D&eacute;faites sur les deux suivants, avec&amp;nbsp;1 point d&eacute;fensif&amp;nbsp;r&eacute;cup&eacute;r&eacute;&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Une journ&eacute;e compliqu&eacute;e qui les fait descendre &agrave; la&amp;nbsp;4ᵉ place&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;&lt;em&gt;Mais rien n&rsquo;est jou&eacute; : la saison continue !&lt;/em&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;F&eacute;licitations &agrave; l&rsquo;ensemble des joueurs pour leur engagement et leurs performances&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;year&quot;: 2026,
            &quot;favoris&quot;: true,
            &quot;image&quot;: null,
            &quot;image_url&quot;: null,
            &quot;video&quot;: &quot;https://sportenfrance.com/videos/x9yzs4s&quot;,
            &quot;created_at&quot;: &quot;2026-02-02T08:11:13.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-02-02T08:31:49.000000Z&quot;
        }
    },
    &quot;meta&quot;: {
        &quot;menus_count&quot;: 5,
        &quot;partners_count&quot;: 8,
        &quot;has_featured_post&quot;: true
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-public-home" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-public-home"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-public-home"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-public-home" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-public-home">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-public-home" data-method="GET"
      data-path="api/v1/public/home"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-public-home', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-public-home"
                    onclick="tryItOut('GETapi-v1-public-home');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-public-home"
                    onclick="cancelTryOut('GETapi-v1-public-home');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-public-home"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/public/home</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-public-home"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-public-home"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-partenaires">Retourne la liste de tous les partenaires.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-partenaires">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/partenaires" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/partenaires"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-partenaires">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 57
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Tours m&eacute;tropole&quot;,
            &quot;logo&quot;: &quot;partenaires/37b4f8ae3d9d972bd5bf0d208c9b6db4.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/37b4f8ae3d9d972bd5bf0d208c9b6db4.png&quot;,
            &quot;website_url&quot;: &quot;https://www.tours-metropole.fr&quot;,
            &quot;created_at&quot;: &quot;2025-05-26T14:21:37.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:52:52.000000Z&quot;
        },
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;Jou&eacute; les Tours&quot;,
            &quot;logo&quot;: &quot;partenaires/fcdc86350be85448befa128dd97cb678.jpg&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/fcdc86350be85448befa128dd97cb678.jpg&quot;,
            &quot;website_url&quot;: &quot;https://www.jouelestours.fr&quot;,
            &quot;created_at&quot;: &quot;2025-05-26T14:31:35.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:55:45.000000Z&quot;
        },
        {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;FF billard LBCVL&quot;,
            &quot;logo&quot;: &quot;partenaires/2dc778dbe66b410464d2028a657a27a8.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/2dc778dbe66b410464d2028a657a27a8.png&quot;,
            &quot;website_url&quot;: &quot;https://ligue-billard-centre-val-de-loire.fr&quot;,
            &quot;created_at&quot;: &quot;2025-05-26T14:32:09.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:56:44.000000Z&quot;
        },
        {
            &quot;id&quot;: 6,
            &quot;name&quot;: &quot;Handi sport&quot;,
            &quot;logo&quot;: &quot;partenaires/161322e89b4e12b0baed38f5f875fbe4.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/161322e89b4e12b0baed38f5f875fbe4.png&quot;,
            &quot;website_url&quot;: &quot;https://www.handisport.org&quot;,
            &quot;created_at&quot;: &quot;2025-05-26T14:32:44.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:57:10.000000Z&quot;
        },
        {
            &quot;id&quot;: 11,
            &quot;name&quot;: &quot;FFSA&quot;,
            &quot;logo&quot;: &quot;partenaires/75a23ee4123c3eddd989e2b64e391239.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/75a23ee4123c3eddd989e2b64e391239.png&quot;,
            &quot;website_url&quot;: &quot;https://sportadapte.fr&quot;,
            &quot;created_at&quot;: &quot;2025-08-19T11:30:46.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:58:53.000000Z&quot;
        },
        {
            &quot;id&quot;: 12,
            &quot;name&quot;: &quot;Bulldog-billard&quot;,
            &quot;logo&quot;: &quot;partenaires/12e2647c9ac9a25f6f34d4d0217c63ce.png&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/12e2647c9ac9a25f6f34d4d0217c63ce.png&quot;,
            &quot;website_url&quot;: &quot;https://bulldog-billard.com&quot;,
            &quot;created_at&quot;: &quot;2025-08-19T11:31:12.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:59:21.000000Z&quot;
        },
        {
            &quot;id&quot;: 16,
            &quot;name&quot;: &quot;Brit Hotel Jou&eacute;-l&egrave;s-Tours&quot;,
            &quot;logo&quot;: &quot;partenaires/12a05680-30c8-403e-bc59-085f4b8a3d81.webp&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/12a05680-30c8-403e-bc59-085f4b8a3d81.webp&quot;,
            &quot;website_url&quot;: &quot;https://hotel-tours.brithotel.fr&quot;,
            &quot;created_at&quot;: &quot;2025-12-05T09:59:48.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T21:00:00.000000Z&quot;
        },
        {
            &quot;id&quot;: 17,
            &quot;name&quot;: &quot;Kyriad Jou&eacute;-L&egrave;s-Tours&quot;,
            &quot;logo&quot;: &quot;partenaires/87d7527e-b0cc-43a8-ab2c-f9b17e9f950a.webp&quot;,
            &quot;logo_url&quot;: &quot;http://localhost:8000/partenaires/87d7527e-b0cc-43a8-ab2c-f9b17e9f950a.webp&quot;,
            &quot;website_url&quot;: &quot;https://tours-joue-les-tours.kyriad.com/fr-fr/?sr=SEO_GOOGLE&quot;,
            &quot;created_at&quot;: &quot;2025-12-05T10:01:52.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T21:00:47.000000Z&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 8
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-partenaires" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-partenaires"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-partenaires"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-partenaires" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-partenaires">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-partenaires" data-method="GET"
      data-path="api/v1/partenaires"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-partenaires', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-partenaires"
                    onclick="tryItOut('GETapi-v1-partenaires');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-partenaires"
                    onclick="cancelTryOut('GETapi-v1-partenaires');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-partenaires"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/partenaires</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-partenaires"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-partenaires"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-posts">Retourne la liste paginée des articles.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 56
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 112,
            &quot;title&quot;: &quot;2026 - Tournoi National n&deg;4 - Villeneuve-sur-Lot&quot;,
            &quot;slug&quot;: &quot;2026-tournoi-national-n4-villeneuve-sur-lot&quot;,
            &quot;excerpt&quot;: &quot;&amp;nbsp;Du 31 janvier au 1er f&eacute;vrier 2026Un week-end intense pour nos joueurs, avec de tr&egrave;s beaux r&eacute;sultats &agrave; la cl&eacute;.&amp;nbsp;Blackball MasterVictoire d&rsquo;Al...&quot;,
            &quot;content&quot;: &quot;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t7e/1/16/1f4c5.png\&quot; alt=\&quot;📅\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;em&gt;Du 31 janvier au 1er f&eacute;vrier 2026&lt;/em&gt;&lt;/strong&gt;&lt;/h5&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Un week-end intense pour nos joueurs, avec de tr&egrave;s beaux r&eacute;sultats &agrave; la cl&eacute;.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tbe/1/16/1f3c6.png\&quot; alt=\&quot;🏆\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Blackball Master&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Victoire d&rsquo;Alex Buscetti&amp;nbsp;&mdash;&amp;nbsp;&lt;em&gt;sa premi&egrave;re, et avec la mani&egrave;re !&lt;/em&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Score final :&amp;nbsp;8&ndash;4, un match parfaitement ma&icirc;tris&eacute;. Gr&acirc;ce &agrave; cette performance,&amp;nbsp;Alex grimpe &agrave; la 3ᵉ place du classement g&eacute;n&eacute;ral.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&amp;nbsp;Christophe Lambert&amp;nbsp;s&rsquo;arr&ecirc;te en&amp;nbsp;1/2 finale&lt;/li&gt;&lt;li&gt;&amp;nbsp;&Eacute;lie Christidis&amp;nbsp;atteint les&amp;nbsp;1/4 de finale&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/te9/1/16/2640.png\&quot; alt=\&quot;♀️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Cat&eacute;gorie F&eacute;minine&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Oph&eacute;lie Laval&amp;nbsp;r&eacute;alise un tr&egrave;s beau parcours et atteint la&amp;nbsp;finale&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Un jeu solide tout au long du tournoi, mais la victoire lui &eacute;chappe.&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Elle se positionne d&eacute;sormais&amp;nbsp;5ᵉ au classement g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8d/1/16/1f500.png\&quot; alt=\&quot;🔀\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Cat&eacute;gorie Mixte&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;J&eacute;r&ocirc;me L&#039;Anthoen&amp;nbsp;atteint les&amp;nbsp;1/4 de finale&amp;nbsp;apr&egrave;s&amp;nbsp;4 matchs remport&eacute;s.&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Malgr&eacute; une d&eacute;faite au 1er tour du tournoi v&eacute;t&eacute;ran, il r&eacute;alise une tr&egrave;s belle op&eacute;ration au mixte.&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;4ᵉ place au classement g&eacute;n&eacute;ral&amp;nbsp;!&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8b/1/16/1f465.png\&quot; alt=\&quot;👥\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Comp&eacute;tition &Eacute;quipes &ndash; DN1&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Jou&eacute; 1&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;3 victoires en 3 matchs&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Dernier match remport&eacute; avec le&amp;nbsp;point offensif&amp;nbsp;et&amp;nbsp;5 fermes&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Jou&eacute; 1 conserve sa 2ᵉ place au classement&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Jou&eacute; 2&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Victoire lors du premier match&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;D&eacute;faites sur les deux suivants, avec&amp;nbsp;1 point d&eacute;fensif&amp;nbsp;r&eacute;cup&eacute;r&eacute;&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Une journ&eacute;e compliqu&eacute;e qui les fait descendre &agrave; la&amp;nbsp;4ᵉ place&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;&lt;em&gt;Mais rien n&rsquo;est jou&eacute; : la saison continue !&lt;/em&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;F&eacute;licitations &agrave; l&rsquo;ensemble des joueurs pour leur engagement et leurs performances&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;year&quot;: 2026,
            &quot;favoris&quot;: true,
            &quot;image&quot;: null,
            &quot;image_url&quot;: null,
            &quot;video&quot;: &quot;https://sportenfrance.com/videos/x9yzs4s&quot;,
            &quot;created_at&quot;: &quot;2026-02-02T08:11:13.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-02-02T08:31:49.000000Z&quot;
        },
        {
            &quot;id&quot;: 111,
            &quot;title&quot;: &quot;4e Tournoi International de Para-Billard&quot;,
            &quot;slug&quot;: &quot;4e-tournoi-international-de-para-billard&quot;,
            &quot;excerpt&quot;: &quot;Venez vivre un &eacute;v&eacute;nement sportif d&rsquo;exception o&ugrave; des joueurs et joueuses de para-billard, venus de toute la France et de l&rsquo;international, s&rsquo;affrontent...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;Venez vivre un &eacute;v&eacute;nement sportif d&rsquo;exception o&ugrave; des joueurs et joueuses de para-billard, venus de toute la France et de l&rsquo;international, s&rsquo;affrontent au plus haut niveau.&lt;/p&gt;&lt;p&gt;Pendant plusieurs jours, pr&eacute;cision, ma&icirc;trise, strat&eacute;gie et mental seront au c&oelig;ur de matchs intenses, en individuel et par &eacute;quipes. Ici, la performance prime, le handicap s&rsquo;efface, et le billard devient un v&eacute;ritable spectacle de haut niveau.&lt;/p&gt;&lt;h3&gt;&lt;br&gt;&lt;/h3&gt;&lt;h3&gt;&lt;strong&gt;Pourquoi venir ?&lt;/strong&gt;&lt;/h3&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Un sport spectaculaire et exigeant&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;D&eacute;couvrez le para-billard comme vous ne l&rsquo;avez jamais vu : des parties engag&eacute;es, une concentration extr&ecirc;me et un niveau de jeu impressionnant.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Des athl&egrave;tes inspirants&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Rencontrez des comp&eacute;titeurs qui repoussent leurs limites &agrave; chaque coup, port&eacute;s par la passion, la rigueur et l&rsquo;envie de se d&eacute;passer.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Une comp&eacute;tition internationale&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Assistez &agrave; des confrontations entre joueurs venus de diff&eacute;rents horizons, dans une ambiance &agrave; la fois sportive, conviviale et respectueuse.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Un moment fort en &eacute;motions&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Que vous soyez amateur de billard ou simple curieux, ce tournoi promet des instants intenses, authentiques et m&eacute;morables.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;👉 &lt;strong&gt;Entr&eacute;e gratuite &ndash; Tout public bienvenu&lt;/strong&gt;&lt;/p&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Venez soutenir le para-billard et partager une vision inclusive et exigeante du sport.&lt;/p&gt;&lt;h3&gt;&lt;br&gt;&lt;/h3&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;strong&gt;English version&lt;/strong&gt;&lt;/p&gt;&lt;h2&gt;&lt;strong&gt;4th International Para-Billiards Tournament&lt;/strong&gt;&lt;/h2&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Come and experience an exceptional sporting event where para-billiards players from across France and beyond compete at the highest level.&lt;/p&gt;&lt;p&gt;For several days, precision, strategy, focus and mental strength will define intense individual and team matches. Here, performance comes first, disability fades away, and billiards becomes a true high-level sporting spectacle.&lt;/p&gt;&lt;h3&gt;&lt;br&gt;&lt;/h3&gt;&lt;h3&gt;&lt;strong&gt;Why attend?&lt;/strong&gt;&lt;/h3&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;A demanding and spectacular sport&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Discover para-billiards in a new light, with high-intensity matches and remarkable technical skill.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Inspiring athletes&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Meet competitors who push their limits at every shot, driven by passion, discipline and determination.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;An international competition&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Enjoy exciting match-ups between players from different countries in a respectful and welcoming atmosphere.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;A powerful emotional experience&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Whether you are a billiards enthusiast or simply curious, this tournament promises unforgettable moments.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;👉 &lt;strong&gt;Free admission &ndash; Open to all&lt;/strong&gt;&lt;/p&gt;&quot;,
            &quot;discipline&quot;: null,
            &quot;discipline_id&quot;: null,
            &quot;year&quot;: 2026,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/2426c8a6d74304637b1bce1649ae7113.png&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/2426c8a6d74304637b1bce1649ae7113.png&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2026-01-15T14:48:08.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-01-15T16:40:34.000000Z&quot;
        },
        {
            &quot;id&quot;: 110,
            &quot;title&quot;: &quot;Soir&eacute;e d&eacute;couverte - Billard au f&eacute;minin&quot;,
            &quot;slug&quot;: &quot;soiree-decouverte-billard-au-feminin&quot;,
            &quot;excerpt&quot;: &quot; Soir&eacute;e d&eacute;couverte billard &ndash; Sp&eacute;ciale femmes&amp;nbsp;Le Billard Club de Jou&eacute;-L&egrave;s-Tours ouvre ses portes pour une soir&eacute;e d&eacute;couverte r&eacute;serv&eacute;e aux femmes no...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;&lt;span style=\&quot;background-color: rgb(255, 255, 255);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9a/1/16/1f3b1.png\&quot; alt=\&quot;🎱\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt; &lt;/span&gt;Soir&eacute;e d&eacute;couverte billard &ndash; Sp&eacute;ciale femmes&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9a/1/16/1f3b1.png\&quot; alt=\&quot;🎱\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;Le Billard Club de Jou&eacute;-L&egrave;s-Tours ouvre ses portes pour une soir&eacute;e d&eacute;couverte r&eacute;serv&eacute;e aux femmes non adh&eacute;rentes, plac&eacute;e sous le signe de la convivialit&eacute; et de la bonne humeur.&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t51/1/16/1f449.png\&quot; alt=\&quot;👉\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Envie de passer une soir&eacute;e sympa entre copines ?&lt;/p&gt;&lt;p&gt;Venez d&eacute;couvrir le billard anglais et le billard fran&ccedil;ais, &eacute;changer avec les adh&eacute;rent(e)s du club et participer &agrave; de petites animations accessibles &agrave; toutes, d&eacute;butantes comme curieuses !&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tf4/1/16/2728.png\&quot; alt=\&quot;✨\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Soir&eacute;e gratuite (hors consommations)&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tc0/1/16/1f4e9.png\&quot; alt=\&quot;📩\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;R&eacute;servation obligatoire par mail pour des raisons d&rsquo;organisation : contact@bcj37.fr&lt;/p&gt;&quot;,
            &quot;discipline&quot;: null,
            &quot;discipline_id&quot;: null,
            &quot;year&quot;: 2026,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/64c9506646739859b7ce34856bf71804.png&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/64c9506646739859b7ce34856bf71804.png&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2026-01-05T09:08:12.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-02-02T08:09:58.000000Z&quot;
        },
        {
            &quot;id&quot;: 109,
            &quot;title&quot;: &quot;🎥 TN3 Am&eacute;ricain - Jeu de la 8 - le 05 d&eacute;cembre 2025&quot;,
            &quot;slug&quot;: &quot;tn3-americain-jeu-de-la-8-le-05-decembre-2025&quot;,
            &quot;excerpt&quot;: &quot;📍 Schiltigheim📅 05 d&eacute;cembre 2025🎱 TN3 Am&eacute;ricain &ndash; Jeu de la 8🏆 Organisateur : BC1935Ambiance de grand rendez-vous national &agrave; Schiltigheim pour ce...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;📍 &lt;strong&gt;Schiltigheim&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;📅 &lt;strong&gt;05 d&eacute;cembre 2025&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;🎱 &lt;strong&gt;TN3 Am&eacute;ricain &ndash; Jeu de la 8&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;🏆 &lt;strong&gt;Organisateur : BC1935&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Ambiance de grand rendez-vous national &agrave; Schiltigheim pour ce TN3 Am&eacute;ricain &ndash; Jeu de la 8, avec une journ&eacute;e intense, des matchs accroch&eacute;s et un niveau de jeu remarquable tout au long de la comp&eacute;tition.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;🔥 &lt;strong&gt;Finale au sommet&lt;/strong&gt; :&lt;/p&gt;&lt;p&gt;&lt;strong class=\&quot;ql-size-large\&quot;&gt; 👉 &lt;em&gt;Christophe Lambert 🆚 Aymeric Luneau&lt;/em&gt;&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Une finale spectaculaire, tendue et engag&eacute;e, &agrave; revivre en vid&eacute;o, o&ugrave; chaque bille comptait.&lt;/p&gt;&lt;p&gt;👏 &lt;strong&gt;F&eacute;licitations &agrave; Christophe Lambert&lt;/strong&gt;, qui s&rsquo;impose et remporte le tournoi national !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;📊 &lt;strong&gt;Stat incroyable&lt;/strong&gt; :&lt;/p&gt;&lt;p&gt;➡️ 2 participations sur 3 tournois&lt;/p&gt;&lt;p&gt;➡️ 2 victoires&lt;/p&gt;&lt;p&gt;Une r&eacute;gularit&eacute; et une efficacit&eacute; impressionnantes au plus haut niveau 💪🔥&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: null,
            &quot;image_url&quot;: null,
            &quot;video&quot;: &quot;https://www.youtube.com/live/6BBnr61RyxI&quot;,
            &quot;created_at&quot;: &quot;2025-12-16T11:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-01-05T09:06:27.000000Z&quot;
        },
        {
            &quot;id&quot;: 108,
            &quot;title&quot;: &quot;BILAN CARAMBOLE &ndash; PREMIER SEMESTRE&quot;,
            &quot;slug&quot;: &quot;bilan-carambole-premier-semestre&quot;,
            &quot;excerpt&quot;: &quot;Un d&eacute;but de saison riche, dynamique et plein de belles ambitions pour la section Carambole du BCJ !🔹 1 &ndash; Organisation de la saisonLa r&eacute;union du 1er o...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;Un d&eacute;but de saison riche, dynamique et plein de belles ambitions pour la section Carambole du BCJ !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h2&gt;🔹 &lt;strong&gt;1 &ndash; Organisation de la saison&lt;/strong&gt;&lt;/h2&gt;&lt;p&gt;La r&eacute;union du &lt;strong&gt;1er octobre&lt;/strong&gt; a permis de lancer les bases d&rsquo;une saison structur&eacute;e et motivante :&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Mise en place des comp&eacute;titions internes :&lt;/li&gt;&lt;li&gt;👉 Deux poules en &lt;strong&gt;parties libres&lt;/strong&gt;&lt;/li&gt;&lt;li&gt;👉 Une poule unique en &lt;strong&gt;bande&lt;/strong&gt;, &lt;strong&gt;trois bandes&lt;/strong&gt; et &lt;strong&gt;casin&lt;/strong&gt;&lt;/li&gt;&lt;li&gt;🎯 Handicaps d&eacute;finis selon ceux de la FFB ou adapt&eacute;s pour les nouveaux joueurs.&lt;/li&gt;&lt;li&gt;Mise en place des &lt;strong&gt;challenges&lt;/strong&gt; :&lt;/li&gt;&lt;li&gt;👉 3 &eacute;quipes &lt;strong&gt;V&eacute;t&eacute;rans&lt;/strong&gt;&lt;/li&gt;&lt;li&gt;👉 1 &eacute;quipe &lt;strong&gt;Casin&lt;/strong&gt;&lt;/li&gt;&lt;li&gt;👉 2 &eacute;quipes &lt;strong&gt;Foulon&lt;/strong&gt;&lt;/li&gt;&lt;li&gt;👉 1 &eacute;quipe &lt;strong&gt;Flambeau&lt;/strong&gt;&lt;/li&gt;&lt;li&gt;Avec, pour chaque &eacute;quipe, un responsable d&eacute;di&eacute;.&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h2&gt;🔹 &lt;strong&gt;2 &ndash; R&eacute;sultats partiels des challenges&lt;/strong&gt;&lt;/h2&gt;&lt;p&gt;Quelques belles performances &agrave; ce stade de la saison :&lt;/p&gt;&lt;p&gt;🏅 &lt;strong&gt;V&eacute;t&eacute;rans&lt;/strong&gt; (15 &eacute;quipes engag&eacute;es)&lt;/p&gt;&lt;p&gt;Deux de nos &eacute;quipes pointent &agrave; la &lt;strong&gt;4ᵉ et 5ᵉ place&lt;/strong&gt; apr&egrave;s le 4ᵉ tour. La troisi&egrave;me reste motiv&eacute;e malgr&eacute; un classement plus bas.&lt;/p&gt;&lt;p&gt;🏅 &lt;strong&gt;Casin&lt;/strong&gt; (8 &eacute;quipes engag&eacute;es)&lt;/p&gt;&lt;p&gt;Notre &eacute;quipe se situe actuellement &lt;strong&gt;au milieu du classement&lt;/strong&gt; apr&egrave;s quatre tours.&lt;/p&gt;&lt;p&gt;🏅 &lt;strong&gt;Foulon&lt;/strong&gt; (4 &eacute;quipes engag&eacute;es)&lt;/p&gt;&lt;p&gt;Super d&eacute;but ! Une &eacute;quipe est &lt;strong&gt;1ʳᵉ&lt;/strong&gt;, l&rsquo;autre occupe la &lt;strong&gt;3ᵉ place&lt;/strong&gt; apr&egrave;s deux tours.&lt;/p&gt;&lt;p&gt;🏅 &lt;strong&gt;Flambeau&lt;/strong&gt; (8 &eacute;quipes engag&eacute;es)&lt;/p&gt;&lt;p&gt;Excellente performance : Jou&eacute; est &lt;strong&gt;en t&ecirc;te du classement&lt;/strong&gt; apr&egrave;s quatre tours !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h2&gt;🔹 &lt;strong&gt;3 &ndash; Comp&eacute;titions internes&lt;/strong&gt;&lt;/h2&gt;&lt;p&gt;Les comp&eacute;titions &lt;strong&gt;Libre&lt;/strong&gt; et &lt;strong&gt;Bande&lt;/strong&gt; sont r&eacute;guli&egrave;rement suivies avec une fr&eacute;quentation moyenne de &lt;strong&gt;18 %&lt;/strong&gt;, m&ecirc;me si elle varie selon les joueurs inscrits.&lt;/p&gt;&lt;p&gt;La participation est plus faible pour les autres disciplines, mais l&rsquo;esprit de comp&eacute;tition reste bien pr&eacute;sent !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h2&gt;🔹 &lt;strong&gt;4 &ndash; Comp&eacute;titions individuelles CSDB37&lt;/strong&gt;&lt;/h2&gt;&lt;p&gt;Quatre joueurs repr&eacute;sentent le club dans plusieurs disciplines : &lt;strong&gt;libre&lt;/strong&gt;, &lt;strong&gt;bande&lt;/strong&gt; et &lt;strong&gt;trois bandes&lt;/strong&gt;.&lt;/p&gt;&lt;p&gt;Les r&eacute;sultats du premier tour restent &lt;strong&gt;provisoires&lt;/strong&gt;, mais la motivation est bien l&agrave; !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;👏 &lt;strong&gt;Bravo &agrave; tous les joueurs pour leur engagement, leur bonne humeur et leur esprit sportif !&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Continuons sur cette belle dynamique pour la suite de la saison.&lt;/p&gt;&lt;p&gt;Allez Jou&eacute;-L&egrave;s-Tours !&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/a2753a3bea67bdf240e2e537b5098965.png&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/a2753a3bea67bdf240e2e537b5098965.png&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2025-12-05T11:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-05T10:54:40.000000Z&quot;
        },
        {
            &quot;id&quot;: 107,
            &quot;title&quot;: &quot;🎱 Tournoi R&eacute;gional Blackball n&deg;3 2025 &agrave; Jou&eacute;-L&egrave;s-Tours&quot;,
            &quot;slug&quot;: &quot;tournoi-regional-blackball-n3-2025-a-joue-les-tours&quot;,
            &quot;excerpt&quot;: &quot;&amp;nbsp;Gymnase Matarazzo&amp;nbsp;Du 12 au 14 d&eacute;cembre 2025Ce week-end restera une tr&egrave;s belle &eacute;tape dans la vie du club&amp;nbsp;Il s&rsquo;agissait de la&amp;nbsp;premi...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t2d/1/16/1f4cd.png\&quot; alt=\&quot;📍\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Gymnase Matarazzo&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t7e/1/16/1f4c5.png\&quot; alt=\&quot;📅\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Du 12 au 14 d&eacute;cembre 2025&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Ce week-end restera une tr&egrave;s belle &eacute;tape dans la vie du club&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t6c/1/16/1f499.png\&quot; alt=\&quot;💙\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Il s&rsquo;agissait de la&amp;nbsp;premi&egrave;re organisation d&rsquo;un tournoi r&eacute;gional&amp;nbsp;depuis le changement de pr&eacute;sident et la r&eacute;organisation de l&rsquo;&eacute;quipe dirigeante. Et quelle r&eacute;ussite !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfe/1/16/1f44f.png\&quot; alt=\&quot;👏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Les b&eacute;n&eacute;voles ont r&eacute;pondu pr&eacute;sents, et en masse !&lt;/p&gt;&lt;p&gt;Gr&acirc;ce &agrave; votre mobilisation, l&rsquo;installation &eacute;tait quasiment finalis&eacute;e d&egrave;s le&amp;nbsp;jeudi soir &agrave; 23h15, le d&eacute;montage s&rsquo;est termin&eacute;&amp;nbsp;dimanche &agrave; 20h, permettant aux b&eacute;n&eacute;voles du lundi matin de finaliser le rangement dans le calme et la s&eacute;r&eacute;nit&eacute;.&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t96/1/16/1f37b.png\&quot; alt=\&quot;🍻\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Mention sp&eacute;ciale &agrave; la buvette&amp;nbsp;:&lt;/p&gt;&lt;p&gt;Le roulement parfaitement organis&eacute; par&amp;nbsp;Isabelle&amp;nbsp;a permis d&rsquo;&eacute;quilibrer les t&acirc;ches et de vous accueillir tout le week-end avec le sourire.&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t80/1/16/1f64f.png\&quot; alt=\&quot;🙏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Un immense MERCI &agrave; tous les b&eacute;n&eacute;voles, sans qui rien ne serait possible. Vous &ecirc;tes la force du club&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t6c/1/16/2764.png\&quot; alt=\&quot;❤️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Nous tenions &eacute;galement &agrave; remercier chaleureusement&amp;nbsp;l&rsquo;&eacute;quipe municipale,&amp;nbsp;Mr Osmond&amp;nbsp;et&amp;nbsp;Mr Cravenaud, pour sa pr&eacute;sence lors de la remise des troph&eacute;es.&lt;/p&gt;&lt;p&gt;Merci &agrave;&amp;nbsp;Mme Patry&amp;nbsp;(directrice des sports de Jou&eacute;-L&egrave;s-Tours) d&rsquo;&ecirc;tre pass&eacute;e nous voir le samedi, ainsi qu&rsquo;&agrave;&amp;nbsp;Mr Moulay&amp;nbsp;(vice-pr&eacute;sident de la r&eacute;gion)&amp;nbsp;pour sa venue&amp;nbsp;dimanche, &agrave; l&rsquo;improviste, t&eacute;moignant de l&rsquo;int&eacute;r&ecirc;t port&eacute; &agrave; la vie sportive et associative locale.&lt;/p&gt;&lt;p&gt;Votre pr&eacute;sence et votre soutien sont tr&egrave;s appr&eacute;ci&eacute;s par le club et ses b&eacute;n&eacute;voles&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t80/1/16/1f64f.png\&quot; alt=\&quot;🙏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tbe/1/16/1f3c6.png\&quot; alt=\&quot;🏆\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;R&Eacute;SULTATS SPORTIFS &ndash; BCJ EN HAUSSE !&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;Le BCJ a clairement&amp;nbsp;hauss&eacute; son niveau, avec de tr&egrave;s belles performances qui donnent beaucoup d&rsquo;espoir pour l&rsquo;avenir&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t50/1/16/1f525.png\&quot; alt=\&quot;🔥\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t60/1/16/1f51d.png\&quot; alt=\&quot;🔝\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Top Ligue&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Yvan SKNOEZECK&amp;nbsp;s&rsquo;arr&ecirc;te en&amp;nbsp;1/8e de finale&amp;nbsp;et se classe&amp;nbsp;7e au g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/te9/1/16/2640.png\&quot; alt=\&quot;♀️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/teb/1/16/2642.png\&quot; alt=\&quot;♂️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Mixte&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Darius &amp;amp; Tarik&amp;nbsp;r&eacute;alisent un superbe parcours en atteignant les&amp;nbsp;1/4 de finale&lt;/p&gt;&lt;ul&gt;&lt;li&gt;Darius GANNAT&amp;nbsp;(entr&eacute; au 1er tour) remporte&amp;nbsp;5 victoires&lt;/li&gt;&lt;li&gt;Tarik BOUATTAOUN, &agrave; quelques secondes d&rsquo;&eacute;galiser pour acc&eacute;der aux 1/2 finales, se fait rattraper par le timer&hellip;&lt;/li&gt;&lt;li&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Il se classe tout de m&ecirc;me&amp;nbsp;10e au g&eacute;n&eacute;ral, une tr&egrave;s belle performance&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t6c/1/16/1f4aa.png\&quot; alt=\&quot;💪\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t62/1/16/1f9d1_200d_1f393.png\&quot; alt=\&quot;🧑&zwj;🎓\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;U23&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t15/1/16/1f948.png\&quot; alt=\&quot;🥈\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Victoire de Math&eacute;o BOUTEILLE&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Il se positionne&amp;nbsp;2e au classement g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;Le club nourrit&amp;nbsp;beaucoup d&rsquo;espoirs&amp;nbsp;pour la suite&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/ted/1/16/1f4ab.png\&quot; alt=\&quot;💫\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;h5&gt;&lt;br&gt;&lt;/h5&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tdf/1/16/1f9d2.png\&quot; alt=\&quot;🧒\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;U15&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t94/1/16/1f947.png\&quot; alt=\&quot;🥇\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Encore l&rsquo;or pour Cl&eacute;ment DA COSTA, qui continue de dominer le classement g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Le travail &agrave; l&rsquo;entra&icirc;nement paie, bravo &agrave; toi&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfe/1/16/1f44f.png\&quot; alt=\&quot;👏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfe/1/16/1f44f.png\&quot; alt=\&quot;👏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Bravo &eacute;galement &agrave; Djino WAGON, demi-finaliste, qui se classe&amp;nbsp;5e au g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfc/1/16/267f.png\&quot; alt=\&quot;♿\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Handi-fauteuil&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t94/1/16/1f947.png\&quot; alt=\&quot;🥇\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t15/1/16/1f948.png\&quot; alt=\&quot;🥈\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Finale 100 % jocondienne&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Victoire de&amp;nbsp;Tristan PIERROT&amp;nbsp;face &agrave;&amp;nbsp;Thomas RAINEAU&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t6c/1/16/1f499.png\&quot; alt=\&quot;💙\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t72/1/16/1f9cd.png\&quot; alt=\&quot;🧍\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Handi-debout&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;J&eacute;r&ocirc;me JUSSY&amp;nbsp;s&rsquo;arr&ecirc;te aux portes des&amp;nbsp;1/2 finales&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/ta9/1/16/1f474.png\&quot; alt=\&quot;👴\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;V&eacute;t&eacute;rans&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Darius GANNAT&amp;nbsp;s&rsquo;incline en&amp;nbsp;1/8e de finale, &agrave; tr&egrave;s peu de choses pr&egrave;s&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8b/1/16/1f465.png\&quot; alt=\&quot;👥\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;CHAMPIONNATS PAR &Eacute;QUIPES&lt;/strong&gt;&lt;/h5&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tef/1/16/1f535.png\&quot; alt=\&quot;🔵\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&Eacute;quipe DR2 &ndash; Jou&eacute; 1&lt;/strong&gt;&lt;/h5&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Journ&eacute;e frustrante pour cette&amp;nbsp;premi&egrave;re saison en DR2,&amp;nbsp;le r&eacute;sultat n&#039;est pas repr&eacute;sentatif du niveau de jeu r&eacute;alis&eacute;.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;2 d&eacute;faites &agrave;&amp;nbsp;une partie du point d&eacute;fensif&lt;/li&gt;&lt;li&gt;1 &eacute;galit&eacute; apr&egrave;s avoir men&eacute;&amp;nbsp;8&ndash;6&lt;/li&gt;&lt;li&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfb/1/16/1f5e3.png\&quot; alt=\&quot;🗣️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;em&gt;&laquo; C&rsquo;est rageant, mais encourageant ! &raquo;&lt;/em&gt;&amp;nbsp;(le capitaine)&lt;/li&gt;&lt;li&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;8e position&amp;nbsp;au classement provisoire&lt;/li&gt;&lt;/ul&gt;&lt;h5&gt;&lt;br&gt;&lt;/h5&gt;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tef/1/16/1f535.png\&quot; alt=\&quot;🔵\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&Eacute;quipes DR3&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Jou&eacute; 2&amp;nbsp;: 1 victoire, 1 nul, 1 d&eacute;faite&lt;/li&gt;&lt;li&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;7e position&amp;nbsp;&ndash; tout reste jouable, mais il va falloir serrer le jeu&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t52/1/16/1f527.png\&quot; alt=\&quot;🔧\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/li&gt;&lt;li&gt;Jou&eacute; 4&amp;nbsp;: premi&egrave;re saison en DR3&lt;/li&gt;&lt;li&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;D&eacute;buts difficiles avec 2 d&eacute;faites, mais une&amp;nbsp;belle victoire pour conclure la journ&eacute;e&lt;/li&gt;&lt;li&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9e/1/16/27a1.png\&quot; alt=\&quot;➡️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;8e position&amp;nbsp;provisoire&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t6c/1/16/1f499.png\&quot; alt=\&quot;💙\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Bravo &agrave; tous les joueurs, b&eacute;n&eacute;voles et encadrants&lt;/p&gt;&lt;p&gt;Ce tournoi montre que le BCJ est&amp;nbsp;sur la bonne voie, sportivement et humainement.&lt;/p&gt;&lt;p&gt;L&rsquo;avenir s&rsquo;annonce prometteur&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t9a/1/16/1f3b1.png\&quot; alt=\&quot;🎱\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t50/1/16/1f525.png\&quot; alt=\&quot;🔥\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfe/1/16/1f3e8.png\&quot; alt=\&quot;🏨\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt; partenaires :&lt;/p&gt;&lt;p&gt;- Kyriad Jou&eacute;-l&egrave;s-Tours&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tb3/1/16/1f517.png\&quot; alt=\&quot;🔗\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;a href=\&quot;http://www.kyriad-tours-joue-les-tours.fr/?fbclid=IwZXh0bgNhZW0CMTAAYnJpZBEwbmZ4VjJMa1RMcEk2dDI3cXNydGMGYXBwX2lkEDIyMjAzOTE3ODgyMDA4OTIAAR7-JfGNrJBUt-j9NAM6GnFzlUJr-GwWLjd182KVNl4Q3SrUmh7nqfHbsxQkDQ_aem_MQBpjc2WakouIFyPLhiUEg\&quot; rel=\&quot;noopener noreferrer\&quot; target=\&quot;_blank\&quot; style=\&quot;background-color: transparent; color: rgb(0, 100, 209);\&quot;&gt;www.kyriad-tours-joue-les-tours.fr&lt;/a&gt;&lt;/p&gt;&lt;p&gt;- Brit Hotel Jou&eacute;-l&egrave;s-Tours&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tb3/1/16/1f517.png\&quot; alt=\&quot;🔗\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;a href=\&quot;http://www.hotel-tours.brithotel.fr/?fbclid=IwZXh0bgNhZW0CMTAAYnJpZBEwbmZ4VjJMa1RMcEk2dDI3cXNydGMGYXBwX2lkEDIyMjAzOTE3ODgyMDA4OTIAAR6ZOekG3EzV1DnO5WJUgM45NTYow7UwRSokVz2gUmyLLWj9YlFkwmiQXh1fLg_aem_V_g6Se1DiTswqF8N2k_e4w\&quot; rel=\&quot;noopener noreferrer\&quot; target=\&quot;_blank\&quot; style=\&quot;background-color: transparent; color: rgb(0, 100, 209);\&quot;&gt;www.hotel-tours.brithotel.fr&lt;/a&gt;&lt;/p&gt;&lt;p&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t80/1/16/1f64f.png\&quot; alt=\&quot;🙏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Un grand merci &agrave; Bulldog-Billard pour son soutien et la qualit&eacute; de ses &eacute;quipements&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tfe/1/16/1f44f.png\&quot; alt=\&quot;👏\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tb3/1/16/1f517.png\&quot; alt=\&quot;🔗\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;a href=\&quot;http://www.bulldog-billard.com/?fbclid=IwZXh0bgNhZW0CMTAAYnJpZBEwbmZ4VjJMa1RMcEk2dDI3cXNydGMGYXBwX2lkEDIyMjAzOTE3ODgyMDA4OTIAAR7Mjws2HvxGwrWy1Yo2rIXLuqsCeE79eD8sckAas2fEWFeElhuDRqd7wwlMeA_aem_yIt4rsjXOSnoYLBwwDfR8Q\&quot; rel=\&quot;noopener noreferrer\&quot; target=\&quot;_blank\&quot; style=\&quot;background-color: transparent; color: rgb(0, 100, 209);\&quot;&gt;www.bulldog-billard.com&lt;/a&gt;&amp;nbsp;&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/a30f03dfbc77520c02094cd307716724.jpeg&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/a30f03dfbc77520c02094cd307716724.jpeg&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2025-11-10T11:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-16T22:33:14.000000Z&quot;
        },
        {
            &quot;id&quot;: 106,
            &quot;title&quot;: &quot;🏅 Christophe Lambert s&rsquo;impose au tournoi national am&eacute;ricain &ndash; Jeu de la 10 &ndash; &agrave; Cergy&quot;,
            &quot;slug&quot;: &quot;christophe-lambert-simpose-au-tournoi-national-americain-jeu-de-la-10-a-cergy&quot;,
            &quot;excerpt&quot;: &quot;Les 11 et 12 octobre 2025, le billard club de Cergy accueillait le premier tournoi national de billard am&eacute;ricain &ndash; Jeu de la 10 &ndash; de la saison, r&eacute;unis...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;Les &lt;strong&gt;11 et 12 octobre 2025&lt;/strong&gt;, le &lt;strong&gt;billard club de Cergy&lt;/strong&gt; accueillait le &lt;strong&gt;premier tournoi national de billard am&eacute;ricain &ndash; Jeu de la 10 &ndash;&lt;/strong&gt; de la saison, r&eacute;unissant &lt;strong&gt;48 joueurs&lt;/strong&gt; venus de toute la France.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Habituellement sp&eacute;cialiste du &lt;strong&gt;Blackball&lt;/strong&gt;, &lt;strong&gt;Christophe Lambert&lt;/strong&gt; a une nouvelle fois d&eacute;montr&eacute; toute l&rsquo;&eacute;tendue de son talent en s&rsquo;imposant dans cette discipline voisine. Il a r&eacute;alis&eacute; un &lt;strong&gt;parcours parfait&lt;/strong&gt;, remportant &lt;strong&gt;tous ses matchs&lt;/strong&gt;, dont la &lt;strong&gt;finale sur le score de 7 &agrave; 3&lt;/strong&gt;.&lt;/p&gt;&lt;p&gt;Une victoire &eacute;clatante qui illustre sa polyvalence et son haut niveau de jeu, quelle que soit la table ou la discipline.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;🔗 &lt;strong&gt;R&eacute;sultats complets sur CueScore :&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;&lt;a href=\&quot;https://urls.fr/GKKbAa\&quot; rel=\&quot;noopener noreferrer\&quot; target=\&quot;_blank\&quot;&gt;https://urls.fr/GKKbAa&lt;/a&gt;&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: null,
            &quot;image_url&quot;: null,
            &quot;video&quot;: &quot;https://www.youtube.com/embed/kTjKBXM66rY&quot;,
            &quot;created_at&quot;: &quot;2025-10-12T21:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-11-07T15:18:01.000000Z&quot;
        },
        {
            &quot;id&quot;: 105,
            &quot;title&quot;: &quot;🏆 Oph&eacute;lie Laval en 1/8e de finale du 2025 Asian Open Women Heyball Championship&quot;,
            &quot;slug&quot;: &quot;ophelie-laval-en-18e-de-finale-du-2025-asian-open-women-heyball-championship&quot;,
            &quot;excerpt&quot;: &quot;Du 8 au 11 octobre 2025, le prestigieux Asian Open Women Heyball Championship s&rsquo;est tenu au Mena Tyche Hotel d&rsquo;Amman (Jordanie).Parmi les 29 participa...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;Du &lt;strong&gt;8 au 11 octobre 2025&lt;/strong&gt;, le prestigieux &lt;strong&gt;Asian Open Women Heyball Championship&lt;/strong&gt; s&rsquo;est tenu au &lt;strong&gt;Mena Tyche Hotel d&rsquo;Amman (Jordanie)&lt;/strong&gt;.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Parmi les &lt;strong&gt;29 participantes&lt;/strong&gt;, la France &eacute;tait repr&eacute;sent&eacute;e par &lt;strong&gt;Oph&eacute;lie Laval&lt;/strong&gt;, accompagn&eacute;e de &lt;strong&gt;Marion Jude&lt;/strong&gt;.&lt;/p&gt;&lt;p&gt;Pour sa premi&egrave;re participation &agrave; cette comp&eacute;tition d&rsquo;envergure internationale, Oph&eacute;lie a r&eacute;alis&eacute; un beau parcours. Elle s&rsquo;est impos&eacute;e &agrave; &lt;strong&gt;deux reprises&lt;/strong&gt; avant de s&rsquo;incliner en &lt;strong&gt;1/8e de finale&lt;/strong&gt;, apr&egrave;s un match disput&eacute; face &agrave; une adversaire exp&eacute;riment&eacute;e.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Une performance encourageante qui confirme la progression d&rsquo;Oph&eacute;lie sur la sc&egrave;ne internationale et son engagement &agrave; porter haut les couleurs fran&ccedil;aises dans la discipline du Heyball.&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/59afdf6a7bbc78807c32e6ab56d1d0aa.jpg&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/59afdf6a7bbc78807c32e6ab56d1d0aa.jpg&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2025-10-12T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-12T10:00:00.000000Z&quot;
        },
        {
            &quot;id&quot;: 104,
            &quot;title&quot;: &quot;Challenge V&eacute;t&eacute;rans : un bon d&eacute;part pour le BCJ !&quot;,
            &quot;slug&quot;: &quot;challenge-veterans-un-bon-depart-pour-le-bcj&quot;,
            &quot;excerpt&quot;: &quot;La premi&egrave;re journ&eacute;e du Challenge V&eacute;t&eacute;rans s&rsquo;est d&eacute;roul&eacute;e ce jeudi 2 octobre au club de Jou&eacute;-l&egrave;s-Tours.Nos trois &eacute;quipes &eacute;taient sur le pont pour cette...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;La premi&egrave;re journ&eacute;e du &lt;strong&gt;Challenge V&eacute;t&eacute;rans&lt;/strong&gt; s&rsquo;est d&eacute;roul&eacute;e ce &lt;strong&gt;jeudi 2 octobre&lt;/strong&gt; au club de Jou&eacute;-l&egrave;s-Tours.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Nos trois &eacute;quipes &eacute;taient sur le pont pour cette reprise de la saison.&lt;/p&gt;&lt;p&gt;Le matin, &lt;strong&gt;Jou&eacute; 3&lt;/strong&gt; s&rsquo;est impos&eacute;e avec autorit&eacute; face &agrave; &lt;strong&gt;Loches 3&lt;/strong&gt; sur le score de &lt;strong&gt;12 &agrave; 4&lt;/strong&gt;.&lt;/p&gt;&lt;p&gt;L&rsquo;apr&egrave;s-midi, les &eacute;quipes &lt;strong&gt;Jou&eacute; 1&lt;/strong&gt; et &lt;strong&gt;Jou&eacute; 2&lt;/strong&gt; se sont affront&eacute;es dans une rencontre &eacute;quilibr&eacute;e, qui s&rsquo;est sold&eacute;e par un &lt;strong&gt;match nul 8 &agrave; 8&lt;/strong&gt;.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&Agrave; noter la belle performance de &lt;strong&gt;Jos&eacute;-Manuel Soares&lt;/strong&gt; (deuxi&egrave;me &agrave; partir de la gauche sur la photo) qui r&eacute;alise une &lt;strong&gt;moyenne de 4.286&lt;/strong&gt; !&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;Une premi&egrave;re journ&eacute;e prometteuse pour nos V&eacute;t&eacute;rans, qui lancent de belle mani&egrave;re leur saison.&lt;/p&gt;&lt;p&gt;&lt;em&gt;(Ci-joint : les deux &eacute;quipes de l&rsquo;apr&egrave;s-midi)&lt;/em&gt;&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/fce45db72e6f69ca306f219b5d6bbba2.png&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/fce45db72e6f69ca306f219b5d6bbba2.png&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2025-10-03T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-10-12T21:42:26.000000Z&quot;
        },
        {
            &quot;id&quot;: 103,
            &quot;title&quot;: &quot;Composition des &eacute;quipes carambole pour la saison 2025-2026&quot;,
            &quot;slug&quot;: &quot;composition-des-equipes-carambole-pour-la-saison-2025-2026&quot;,
            &quot;excerpt&quot;: &quot;&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;year&quot;: 2025,
            &quot;favoris&quot;: false,
            &quot;image&quot;: &quot;files/1962cfd2765918d1636b6b6ac26cb7c1.jpg&quot;,
            &quot;image_url&quot;: &quot;http://localhost:8000/files/1962cfd2765918d1636b6b6ac26cb7c1.jpg&quot;,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2025-09-20T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-09-21T10:00:00.000000Z&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 47,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 5,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 10,
        &quot;total&quot;: 47
    },
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/posts?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/posts?page=5&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://localhost:8000/api/v1/posts?page=2&quot;
    },
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts" data-method="GET"
      data-path="api/v1/posts"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts"
                    onclick="tryItOut('GETapi-v1-posts');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts"
                    onclick="cancelTryOut('GETapi-v1-posts');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-posts-favoris">Retourne les articles marqués comme favoris.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts-favoris">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/favoris" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/favoris"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts-favoris">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 55
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 112,
            &quot;title&quot;: &quot;2026 - Tournoi National n&deg;4 - Villeneuve-sur-Lot&quot;,
            &quot;slug&quot;: &quot;2026-tournoi-national-n4-villeneuve-sur-lot&quot;,
            &quot;excerpt&quot;: &quot;&amp;nbsp;Du 31 janvier au 1er f&eacute;vrier 2026Un week-end intense pour nos joueurs, avec de tr&egrave;s beaux r&eacute;sultats &agrave; la cl&eacute;.&amp;nbsp;Blackball MasterVictoire d&rsquo;Al...&quot;,
            &quot;content&quot;: &quot;&lt;h5&gt;&lt;strong style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t7e/1/16/1f4c5.png\&quot; alt=\&quot;📅\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;&lt;em&gt;Du 31 janvier au 1er f&eacute;vrier 2026&lt;/em&gt;&lt;/strong&gt;&lt;/h5&gt;&lt;p class=\&quot;ql-indent-1\&quot;&gt;Un week-end intense pour nos joueurs, avec de tr&egrave;s beaux r&eacute;sultats &agrave; la cl&eacute;.&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/tbe/1/16/1f3c6.png\&quot; alt=\&quot;🏆\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Blackball Master&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Victoire d&rsquo;Alex Buscetti&amp;nbsp;&mdash;&amp;nbsp;&lt;em&gt;sa premi&egrave;re, et avec la mani&egrave;re !&lt;/em&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Score final :&amp;nbsp;8&ndash;4, un match parfaitement ma&icirc;tris&eacute;. Gr&acirc;ce &agrave; cette performance,&amp;nbsp;Alex grimpe &agrave; la 3ᵉ place du classement g&eacute;n&eacute;ral.&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&amp;nbsp;Christophe Lambert&amp;nbsp;s&rsquo;arr&ecirc;te en&amp;nbsp;1/2 finale&lt;/li&gt;&lt;li&gt;&amp;nbsp;&Eacute;lie Christidis&amp;nbsp;atteint les&amp;nbsp;1/4 de finale&lt;/li&gt;&lt;/ul&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/te9/1/16/2640.png\&quot; alt=\&quot;♀️\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Cat&eacute;gorie F&eacute;minine&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;Oph&eacute;lie Laval&amp;nbsp;r&eacute;alise un tr&egrave;s beau parcours et atteint la&amp;nbsp;finale&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Un jeu solide tout au long du tournoi, mais la victoire lui &eacute;chappe.&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Elle se positionne d&eacute;sormais&amp;nbsp;5ᵉ au classement g&eacute;n&eacute;ral&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8d/1/16/1f500.png\&quot; alt=\&quot;🔀\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Cat&eacute;gorie Mixte&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;J&eacute;r&ocirc;me L&#039;Anthoen&amp;nbsp;atteint les&amp;nbsp;1/4 de finale&amp;nbsp;apr&egrave;s&amp;nbsp;4 matchs remport&eacute;s.&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Malgr&eacute; une d&eacute;faite au 1er tour du tournoi v&eacute;t&eacute;ran, il r&eacute;alise une tr&egrave;s belle op&eacute;ration au mixte.&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;4ᵉ place au classement g&eacute;n&eacute;ral&amp;nbsp;!&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;h5&gt;&lt;strong class=\&quot;ql-size-large\&quot; style=\&quot;color: rgb(101, 104, 108);\&quot;&gt;&lt;img src=\&quot;https://static.xx.fbcdn.net/images/emoji.php/v9/t8b/1/16/1f465.png\&quot; alt=\&quot;👥\&quot; height=\&quot;16\&quot; width=\&quot;16\&quot;&gt;&amp;nbsp;Comp&eacute;tition &Eacute;quipes &ndash; DN1&lt;/strong&gt;&lt;/h5&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Jou&eacute; 1&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;3 victoires en 3 matchs&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Dernier match remport&eacute; avec le&amp;nbsp;point offensif&amp;nbsp;et&amp;nbsp;5 fermes&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Jou&eacute; 1 conserve sa 2ᵉ place au classement&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;strong&gt;Jou&eacute; 2&lt;/strong&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Victoire lors du premier match&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;D&eacute;faites sur les deux suivants, avec&amp;nbsp;1 point d&eacute;fensif&amp;nbsp;r&eacute;cup&eacute;r&eacute;&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;Une journ&eacute;e compliqu&eacute;e qui les fait descendre &agrave; la&amp;nbsp;4ᵉ place&lt;/p&gt;&lt;p class=\&quot;ql-indent-2\&quot;&gt;&lt;em&gt;Mais rien n&rsquo;est jou&eacute; : la saison continue !&lt;/em&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;F&eacute;licitations &agrave; l&rsquo;ensemble des joueurs pour leur engagement et leurs performances&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;year&quot;: 2026,
            &quot;favoris&quot;: true,
            &quot;image&quot;: null,
            &quot;image_url&quot;: null,
            &quot;video&quot;: &quot;https://sportenfrance.com/videos/x9yzs4s&quot;,
            &quot;created_at&quot;: &quot;2026-02-02T08:11:13.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-02-02T08:31:49.000000Z&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;favoris&quot;: true,
        &quot;count&quot;: 1,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 1,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 1,
        &quot;total&quot;: 1
    },
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/posts/favoris?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/posts/favoris?page=1&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts-favoris" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts-favoris"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts-favoris"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts-favoris" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts-favoris">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts-favoris" data-method="GET"
      data-path="api/v1/posts/favoris"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts-favoris', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts-favoris"
                    onclick="tryItOut('GETapi-v1-posts-favoris');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts-favoris"
                    onclick="cancelTryOut('GETapi-v1-posts-favoris');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts-favoris"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/favoris</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts-favoris"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts-favoris"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-posts-discipline--discipline-">Retourne les articles d&#039;une discipline donnée.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts-discipline--discipline-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/discipline/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/discipline/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts-discipline--discipline-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 54
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: {
        &quot;code&quot;: &quot;discipline_not_found&quot;,
        &quot;message&quot;: &quot;Discipline not found&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts-discipline--discipline-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts-discipline--discipline-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts-discipline--discipline-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts-discipline--discipline-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts-discipline--discipline-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts-discipline--discipline-" data-method="GET"
      data-path="api/v1/posts/discipline/{discipline}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts-discipline--discipline-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts-discipline--discipline-"
                    onclick="tryItOut('GETapi-v1-posts-discipline--discipline-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts-discipline--discipline-"
                    onclick="cancelTryOut('GETapi-v1-posts-discipline--discipline-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts-discipline--discipline-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/discipline/{discipline}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts-discipline--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts-discipline--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-posts-discipline--discipline-"
               value="architecto"
               data-component="url">
    <br>
<p>The discipline. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-posts-slug--slug-">Retourne un article via son slug.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts-slug--slug-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/slug/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/slug/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts-slug--slug-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 53
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;No query results for model [App\\Models\\Post].&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts-slug--slug-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts-slug--slug-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts-slug--slug-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts-slug--slug-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts-slug--slug-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts-slug--slug-" data-method="GET"
      data-path="api/v1/posts/slug/{slug}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts-slug--slug-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts-slug--slug-"
                    onclick="tryItOut('GETapi-v1-posts-slug--slug-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts-slug--slug-"
                    onclick="cancelTryOut('GETapi-v1-posts-slug--slug-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts-slug--slug-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/slug/{slug}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts-slug--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts-slug--slug-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="GETapi-v1-posts-slug--slug-"
               value="architecto"
               data-component="url">
    <br>
<p>The slug of the slug. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-posts-decade--year-">Retourne les articles d&#039;une décennie donnée.</h2>

<p>
</p>

<p>Exemple :</p>
<ul>
<li>2023 → décennie 2020-2029</li>
</ul>

<span id="example-requests-GETapi-v1-posts-decade--year-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/decade/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/decade/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts-decade--year-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 52
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts-decade--year-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts-decade--year-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts-decade--year-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts-decade--year-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts-decade--year-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts-decade--year-" data-method="GET"
      data-path="api/v1/posts/decade/{year}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts-decade--year-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts-decade--year-"
                    onclick="tryItOut('GETapi-v1-posts-decade--year-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts-decade--year-"
                    onclick="cancelTryOut('GETapi-v1-posts-decade--year-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts-decade--year-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/decade/{year}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts-decade--year-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts-decade--year-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="year"                data-endpoint="GETapi-v1-posts-decade--year-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-posts-year--year-">Retourne les articles d&#039;une année donnée.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts-year--year-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/year/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/year/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts-year--year-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 51
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts-year--year-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts-year--year-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts-year--year-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts-year--year-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts-year--year-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts-year--year-" data-method="GET"
      data-path="api/v1/posts/year/{year}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts-year--year-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts-year--year-"
                    onclick="tryItOut('GETapi-v1-posts-year--year-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts-year--year-"
                    onclick="cancelTryOut('GETapi-v1-posts-year--year-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts-year--year-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/year/{year}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts-year--year-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts-year--year-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>year</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="year"                data-endpoint="GETapi-v1-posts-year--year-"
               value="architecto"
               data-component="url">
    <br>
<p>The year. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-posts--id-">Retourne un article par son identifiant.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/dans-les-coulisses-des-jeunes-bleus-en-stage" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/dans-les-coulisses-des-jeunes-bleus-en-stage"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-posts--id-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The route api/v1/posts/dans-les-coulisses-des-jeunes-bleus-en-stage could not be found.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts--id-" data-method="GET"
      data-path="api/v1/posts/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts--id-"
                    onclick="tryItOut('GETapi-v1-posts--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts--id-"
                    onclick="cancelTryOut('GETapi-v1-posts--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-posts--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-posts--id-"
               value="dans-les-coulisses-des-jeunes-bleus-en-stage"
               data-component="url">
    <br>
<p>The ID of the post. Example: <code>dans-les-coulisses-des-jeunes-bleus-en-stage</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-licencies">Retourne la liste complète des licenciés.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-licencies">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/licencies" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/licencies"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-licencies">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 50
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;licence&quot;: &quot;191100 S&quot;,
            &quot;nom&quot;: &quot;ARROUAS&quot;,
            &quot;prenom&quot;: &quot;ISABELLE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 2,
            &quot;licence&quot;: &quot;190399 F&quot;,
            &quot;nom&quot;: &quot;AUCHART&quot;,
            &quot;prenom&quot;: &quot;THIERRY&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 3,
            &quot;licence&quot;: &quot;018952 Y&quot;,
            &quot;nom&quot;: &quot;AUGER&quot;,
            &quot;prenom&quot;: &quot;WILLIAM&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 4,
            &quot;licence&quot;: &quot;185211 R&quot;,
            &quot;nom&quot;: &quot;BAILLON&quot;,
            &quot;prenom&quot;: &quot;DIDIER&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 5,
            &quot;licence&quot;: &quot;126663 R&quot;,
            &quot;nom&quot;: &quot;BARBIER&quot;,
            &quot;prenom&quot;: &quot;JEAN FRANCOIS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 6,
            &quot;licence&quot;: &quot;165139 Z&quot;,
            &quot;nom&quot;: &quot;BARICAULT&quot;,
            &quot;prenom&quot;: &quot;JEAN CHRISTOPHE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 7,
            &quot;licence&quot;: &quot;173547 N&quot;,
            &quot;nom&quot;: &quot;BARRAS&quot;,
            &quot;prenom&quot;: &quot;MICHEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 8,
            &quot;licence&quot;: &quot;111666 W&quot;,
            &quot;nom&quot;: &quot;BONNET&quot;,
            &quot;prenom&quot;: &quot;MICHEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 9,
            &quot;licence&quot;: &quot;188587 L&quot;,
            &quot;nom&quot;: &quot;BOUATTAOUN&quot;,
            &quot;prenom&quot;: &quot;TARIK&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 10,
            &quot;licence&quot;: &quot;177407 J&quot;,
            &quot;nom&quot;: &quot;BOURLIER&quot;,
            &quot;prenom&quot;: &quot;ALEXANDRE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 11,
            &quot;licence&quot;: &quot;137219 R&quot;,
            &quot;nom&quot;: &quot;BOUTEILLE&quot;,
            &quot;prenom&quot;: &quot;MATHEO&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 12,
            &quot;licence&quot;: &quot;180469 M&quot;,
            &quot;nom&quot;: &quot;BOUYCHOU&quot;,
            &quot;prenom&quot;: &quot;CYRIL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 13,
            &quot;licence&quot;: &quot;148836 D&quot;,
            &quot;nom&quot;: &quot;BRUNEAU&quot;,
            &quot;prenom&quot;: &quot;MARTIN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 14,
            &quot;licence&quot;: &quot;141625 D&quot;,
            &quot;nom&quot;: &quot;BUSCETTI&quot;,
            &quot;prenom&quot;: &quot;ALEXANDRE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 15,
            &quot;licence&quot;: &quot;179777 K&quot;,
            &quot;nom&quot;: &quot;BUZIAUX&quot;,
            &quot;prenom&quot;: &quot;NANCY&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 16,
            &quot;licence&quot;: &quot;157681 S&quot;,
            &quot;nom&quot;: &quot;CADINOT&quot;,
            &quot;prenom&quot;: &quot;GREGORY&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 17,
            &quot;licence&quot;: &quot;174450 V&quot;,
            &quot;nom&quot;: &quot;CARVALHO&quot;,
            &quot;prenom&quot;: &quot;RAUL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 18,
            &quot;licence&quot;: &quot;183753 G&quot;,
            &quot;nom&quot;: &quot;CESVRE&quot;,
            &quot;prenom&quot;: &quot;JEAN LOUIS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 19,
            &quot;licence&quot;: &quot;183253 N&quot;,
            &quot;nom&quot;: &quot;CHAS&quot;,
            &quot;prenom&quot;: &quot;THIERRY&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 20,
            &quot;licence&quot;: &quot;131348 W&quot;,
            &quot;nom&quot;: &quot;CHAUVIN&quot;,
            &quot;prenom&quot;: &quot;ROLAND&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 21,
            &quot;licence&quot;: &quot;111518 E&quot;,
            &quot;nom&quot;: &quot;CHERAMY&quot;,
            &quot;prenom&quot;: &quot;DAVID&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 22,
            &quot;licence&quot;: &quot;141681 H&quot;,
            &quot;nom&quot;: &quot;CHEVE&quot;,
            &quot;prenom&quot;: &quot;BENJAMIN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 23,
            &quot;licence&quot;: &quot;155556 H&quot;,
            &quot;nom&quot;: &quot;CHRISTIDIS&quot;,
            &quot;prenom&quot;: &quot;ELIE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 24,
            &quot;licence&quot;: &quot;157999 N&quot;,
            &quot;nom&quot;: &quot;COLDRICK&quot;,
            &quot;prenom&quot;: &quot;PAUL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 25,
            &quot;licence&quot;: &quot;159105 Q&quot;,
            &quot;nom&quot;: &quot;CONCHON&quot;,
            &quot;prenom&quot;: &quot;JEAN PIERRE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 26,
            &quot;licence&quot;: &quot;104860 C&quot;,
            &quot;nom&quot;: &quot;COSTARD&quot;,
            &quot;prenom&quot;: &quot;ALAIN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 27,
            &quot;licence&quot;: &quot;104861 D&quot;,
            &quot;nom&quot;: &quot;COUTARD&quot;,
            &quot;prenom&quot;: &quot;BRUNO&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 28,
            &quot;licence&quot;: &quot;181887 D&quot;,
            &quot;nom&quot;: &quot;CROUZAT REYNES&quot;,
            &quot;prenom&quot;: &quot;NICOLAS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 29,
            &quot;licence&quot;: &quot;173585 E&quot;,
            &quot;nom&quot;: &quot;DA COSTA&quot;,
            &quot;prenom&quot;: &quot;CLEMENT&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 30,
            &quot;licence&quot;: &quot;173661 M&quot;,
            &quot;nom&quot;: &quot;DA COSTA&quot;,
            &quot;prenom&quot;: &quot;DAVID&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 31,
            &quot;licence&quot;: &quot;185890 E&quot;,
            &quot;nom&quot;: &quot;DA CRUZ PEREIRA&quot;,
            &quot;prenom&quot;: &quot;LUIS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 32,
            &quot;licence&quot;: &quot;193794 W&quot;,
            &quot;nom&quot;: &quot;DA SILVA FERREIRA&quot;,
            &quot;prenom&quot;: &quot;JORGE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 33,
            &quot;licence&quot;: &quot;020779 F&quot;,
            &quot;nom&quot;: &quot;DANEL&quot;,
            &quot;prenom&quot;: &quot;MICHEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 34,
            &quot;licence&quot;: &quot;162426 A&quot;,
            &quot;nom&quot;: &quot;DENIS&quot;,
            &quot;prenom&quot;: &quot;DIDIER&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 35,
            &quot;licence&quot;: &quot;150158 Q&quot;,
            &quot;nom&quot;: &quot;DEROT&quot;,
            &quot;prenom&quot;: &quot;JEAN YVES&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 36,
            &quot;licence&quot;: &quot;124564 Y&quot;,
            &quot;nom&quot;: &quot;DEVAUD&quot;,
            &quot;prenom&quot;: &quot;MARCEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 37,
            &quot;licence&quot;: &quot;192805 W&quot;,
            &quot;nom&quot;: &quot;DHAOUADI&quot;,
            &quot;prenom&quot;: &quot;TAHER&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 38,
            &quot;licence&quot;: &quot;181485 R&quot;,
            &quot;nom&quot;: &quot;DOMMERY&quot;,
            &quot;prenom&quot;: &quot;VITAL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 39,
            &quot;licence&quot;: &quot;151465 L&quot;,
            &quot;nom&quot;: &quot;DOS REIS VENDAS&quot;,
            &quot;prenom&quot;: &quot;JOSE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 40,
            &quot;licence&quot;: &quot;177415 S&quot;,
            &quot;nom&quot;: &quot;DUBAN&quot;,
            &quot;prenom&quot;: &quot;GUYLAINE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 41,
            &quot;licence&quot;: &quot;187565 A&quot;,
            &quot;nom&quot;: &quot;DUSSOUCHAUD&quot;,
            &quot;prenom&quot;: &quot;DAVID&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 42,
            &quot;licence&quot;: &quot;190158 T&quot;,
            &quot;nom&quot;: &quot;EL ATTARI&quot;,
            &quot;prenom&quot;: &quot;ZAKARIA&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 43,
            &quot;licence&quot;: &quot;153043 B&quot;,
            &quot;nom&quot;: &quot;FAGE&quot;,
            &quot;prenom&quot;: &quot;PHILIPPE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 44,
            &quot;licence&quot;: &quot;116369 T&quot;,
            &quot;nom&quot;: &quot;FAURIE&quot;,
            &quot;prenom&quot;: &quot;CHRISTOPHE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 45,
            &quot;licence&quot;: &quot;180354 M&quot;,
            &quot;nom&quot;: &quot;FERASSE&quot;,
            &quot;prenom&quot;: &quot;CHRISTOPHE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 46,
            &quot;licence&quot;: &quot;138473 X&quot;,
            &quot;nom&quot;: &quot;FRAYSSE&quot;,
            &quot;prenom&quot;: &quot;FREDERIC&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 47,
            &quot;licence&quot;: &quot;109197 X&quot;,
            &quot;nom&quot;: &quot;GANNAT&quot;,
            &quot;prenom&quot;: &quot;DARIUS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 48,
            &quot;licence&quot;: &quot;181362 H&quot;,
            &quot;nom&quot;: &quot;GOUAS&quot;,
            &quot;prenom&quot;: &quot;ALINE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 49,
            &quot;licence&quot;: &quot;173548 P&quot;,
            &quot;nom&quot;: &quot;GOUAS&quot;,
            &quot;prenom&quot;: &quot;PATRICK&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 50,
            &quot;licence&quot;: &quot;177408 K&quot;,
            &quot;nom&quot;: &quot;GUEGAN&quot;,
            &quot;prenom&quot;: &quot;JEAN MARIE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 51,
            &quot;licence&quot;: &quot;177932 E&quot;,
            &quot;nom&quot;: &quot;HERISSON&quot;,
            &quot;prenom&quot;: &quot;JEROME&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 52,
            &quot;licence&quot;: &quot;177885 D&quot;,
            &quot;nom&quot;: &quot;HERISSON&quot;,
            &quot;prenom&quot;: &quot;MATHEO&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 53,
            &quot;licence&quot;: &quot;180470 N&quot;,
            &quot;nom&quot;: &quot;HERITIER&quot;,
            &quot;prenom&quot;: &quot;CELINE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 54,
            &quot;licence&quot;: &quot;156524 K&quot;,
            &quot;nom&quot;: &quot;JUSSY&quot;,
            &quot;prenom&quot;: &quot;JEROME&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 55,
            &quot;licence&quot;: &quot;156527 N&quot;,
            &quot;nom&quot;: &quot;L ANTHOEN&quot;,
            &quot;prenom&quot;: &quot;JEROME&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 56,
            &quot;licence&quot;: &quot;121547 X&quot;,
            &quot;nom&quot;: &quot;LAMBERT&quot;,
            &quot;prenom&quot;: &quot;CHRISTOPHE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 57,
            &quot;licence&quot;: &quot;151779 C&quot;,
            &quot;nom&quot;: &quot;LARCHET&quot;,
            &quot;prenom&quot;: &quot;GUY&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 58,
            &quot;licence&quot;: &quot;152188 X&quot;,
            &quot;nom&quot;: &quot;LAVAL&quot;,
            &quot;prenom&quot;: &quot;OPHELIE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 59,
            &quot;licence&quot;: &quot;154187 V&quot;,
            &quot;nom&quot;: &quot;LE NOACH&quot;,
            &quot;prenom&quot;: &quot;PATRICK&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 60,
            &quot;licence&quot;: &quot;182214 J&quot;,
            &quot;nom&quot;: &quot;LEFEBVRE&quot;,
            &quot;prenom&quot;: &quot;ALEXANDRE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 61,
            &quot;licence&quot;: &quot;182217 M&quot;,
            &quot;nom&quot;: &quot;LEFEBVRE&quot;,
            &quot;prenom&quot;: &quot;FABIEN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 62,
            &quot;licence&quot;: &quot;182215 K&quot;,
            &quot;nom&quot;: &quot;LEFEBVRE&quot;,
            &quot;prenom&quot;: &quot;PAUL ANTOINE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 63,
            &quot;licence&quot;: &quot;166523 D&quot;,
            &quot;nom&quot;: &quot;LEGROS&quot;,
            &quot;prenom&quot;: &quot;PHILIPPE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 64,
            &quot;licence&quot;: &quot;192244 L&quot;,
            &quot;nom&quot;: &quot;LEGUY&quot;,
            &quot;prenom&quot;: &quot;PATRICIA&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 65,
            &quot;licence&quot;: &quot;185892 G&quot;,
            &quot;nom&quot;: &quot;LEMOIGNE&quot;,
            &quot;prenom&quot;: &quot;DOMINIQUE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 66,
            &quot;licence&quot;: &quot;103443 P&quot;,
            &quot;nom&quot;: &quot;LEMOIGNE&quot;,
            &quot;prenom&quot;: &quot;GERARD&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 67,
            &quot;licence&quot;: &quot;184162 B&quot;,
            &quot;nom&quot;: &quot;LENEEZ&quot;,
            &quot;prenom&quot;: &quot;QUENTIN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 68,
            &quot;licence&quot;: &quot;141677 D&quot;,
            &quot;nom&quot;: &quot;LEON&quot;,
            &quot;prenom&quot;: &quot;MICHEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 69,
            &quot;licence&quot;: &quot;018276 Y&quot;,
            &quot;nom&quot;: &quot;LEROUX&quot;,
            &quot;prenom&quot;: &quot;ERIC&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 70,
            &quot;licence&quot;: &quot;104868 K&quot;,
            &quot;nom&quot;: &quot;LEROUX&quot;,
            &quot;prenom&quot;: &quot;JULIEN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 71,
            &quot;licence&quot;: &quot;175790 B&quot;,
            &quot;nom&quot;: &quot;LISSY&quot;,
            &quot;prenom&quot;: &quot;MICHEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 72,
            &quot;licence&quot;: &quot;175398 A&quot;,
            &quot;nom&quot;: &quot;LOYAU TULASNE&quot;,
            &quot;prenom&quot;: &quot;THIERRY&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 73,
            &quot;licence&quot;: &quot;182058 P&quot;,
            &quot;nom&quot;: &quot;MAHFOUDI&quot;,
            &quot;prenom&quot;: &quot;ANASS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 74,
            &quot;licence&quot;: &quot;188153 P&quot;,
            &quot;nom&quot;: &quot;MAILLET&quot;,
            &quot;prenom&quot;: &quot;CLAUDE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 75,
            &quot;licence&quot;: &quot;177469 B&quot;,
            &quot;nom&quot;: &quot;MAILLET&quot;,
            &quot;prenom&quot;: &quot;ISABELLE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 76,
            &quot;licence&quot;: &quot;184356 M&quot;,
            &quot;nom&quot;: &quot;MAILLET&quot;,
            &quot;prenom&quot;: &quot;JEAN MARC&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 77,
            &quot;licence&quot;: &quot;170602 M&quot;,
            &quot;nom&quot;: &quot;MAILLET&quot;,
            &quot;prenom&quot;: &quot;MICHAEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 78,
            &quot;licence&quot;: &quot;177470 C&quot;,
            &quot;nom&quot;: &quot;MAILLET&quot;,
            &quot;prenom&quot;: &quot;PATRICE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 79,
            &quot;licence&quot;: &quot;018278 A&quot;,
            &quot;nom&quot;: &quot;MARAIS&quot;,
            &quot;prenom&quot;: &quot;JEAN CLAUDE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 80,
            &quot;licence&quot;: &quot;133947 V&quot;,
            &quot;nom&quot;: &quot;MARILLAUD&quot;,
            &quot;prenom&quot;: &quot;PIERRE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 81,
            &quot;licence&quot;: &quot;119340 A&quot;,
            &quot;nom&quot;: &quot;MERCIER&quot;,
            &quot;prenom&quot;: &quot;JEAN MARIE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 82,
            &quot;licence&quot;: &quot;144532 Y&quot;,
            &quot;nom&quot;: &quot;MICHEZ&quot;,
            &quot;prenom&quot;: &quot;BASTIEN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 83,
            &quot;licence&quot;: &quot;182056 M&quot;,
            &quot;nom&quot;: &quot;MOUSSAMIH&quot;,
            &quot;prenom&quot;: &quot;OMAR&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 84,
            &quot;licence&quot;: &quot;187521 C&quot;,
            &quot;nom&quot;: &quot;NEERMAL&quot;,
            &quot;prenom&quot;: &quot;SABINE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 85,
            &quot;licence&quot;: &quot;177784 T&quot;,
            &quot;nom&quot;: &quot;OLMEDA&quot;,
            &quot;prenom&quot;: &quot;CHRISTIAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 86,
            &quot;licence&quot;: &quot;178096 H&quot;,
            &quot;nom&quot;: &quot;ORTEGA&quot;,
            &quot;prenom&quot;: &quot;BEATRICE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 87,
            &quot;licence&quot;: &quot;168356 W&quot;,
            &quot;nom&quot;: &quot;PALLUS&quot;,
            &quot;prenom&quot;: &quot;CLAUDE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 88,
            &quot;licence&quot;: &quot;131351 Z&quot;,
            &quot;nom&quot;: &quot;PARIS&quot;,
            &quot;prenom&quot;: &quot;MICHEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 89,
            &quot;licence&quot;: &quot;158309 A&quot;,
            &quot;nom&quot;: &quot;PASCUTTO&quot;,
            &quot;prenom&quot;: &quot;FABRICE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 90,
            &quot;licence&quot;: &quot;146692 A&quot;,
            &quot;nom&quot;: &quot;PELLISSIER&quot;,
            &quot;prenom&quot;: &quot;SIMON&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 91,
            &quot;licence&quot;: &quot;013898 O&quot;,
            &quot;nom&quot;: &quot;PERENNES&quot;,
            &quot;prenom&quot;: &quot;JEAN YVES&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 92,
            &quot;licence&quot;: &quot;160792 Z&quot;,
            &quot;nom&quot;: &quot;PIERROT&quot;,
            &quot;prenom&quot;: &quot;TRISTAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 93,
            &quot;licence&quot;: &quot;177414 R&quot;,
            &quot;nom&quot;: &quot;PIRES&quot;,
            &quot;prenom&quot;: &quot;NICOLAS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 94,
            &quot;licence&quot;: &quot;171306 C&quot;,
            &quot;nom&quot;: &quot;PORTEVIN&quot;,
            &quot;prenom&quot;: &quot;HUGO&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 95,
            &quot;licence&quot;: &quot;173909 G&quot;,
            &quot;nom&quot;: &quot;RAINEAU&quot;,
            &quot;prenom&quot;: &quot;THOMAS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 96,
            &quot;licence&quot;: &quot;189149 X&quot;,
            &quot;nom&quot;: &quot;RAVARD&quot;,
            &quot;prenom&quot;: &quot;CHRISTOPHE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 97,
            &quot;licence&quot;: &quot;166335 Z&quot;,
            &quot;nom&quot;: &quot;REFAUVELET&quot;,
            &quot;prenom&quot;: &quot;CHRISTIAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 98,
            &quot;licence&quot;: &quot;192118 Z&quot;,
            &quot;nom&quot;: &quot;REMY&quot;,
            &quot;prenom&quot;: &quot;NICOLAS&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 99,
            &quot;licence&quot;: &quot;189109 D&quot;,
            &quot;nom&quot;: &quot;RETHIERE&quot;,
            &quot;prenom&quot;: &quot;JEAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 100,
            &quot;licence&quot;: &quot;111670 A&quot;,
            &quot;nom&quot;: &quot;RIBERA&quot;,
            &quot;prenom&quot;: &quot;ANDREE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 101,
            &quot;licence&quot;: &quot;122178 E&quot;,
            &quot;nom&quot;: &quot;RIMPOT&quot;,
            &quot;prenom&quot;: &quot;HENRI&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 102,
            &quot;licence&quot;: &quot;179958 G&quot;,
            &quot;nom&quot;: &quot;ROBUCHON&quot;,
            &quot;prenom&quot;: &quot;PHILIPPE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 103,
            &quot;licence&quot;: &quot;182184 B&quot;,
            &quot;nom&quot;: &quot;RUSSEIL&quot;,
            &quot;prenom&quot;: &quot;MORGAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 104,
            &quot;licence&quot;: &quot;178088 Z&quot;,
            &quot;nom&quot;: &quot;SKNOEZECK&quot;,
            &quot;prenom&quot;: &quot;MAE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 105,
            &quot;licence&quot;: &quot;110008 C&quot;,
            &quot;nom&quot;: &quot;SKNOEZECK&quot;,
            &quot;prenom&quot;: &quot;YVAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 106,
            &quot;licence&quot;: &quot;182458 Z&quot;,
            &quot;nom&quot;: &quot;SOARES&quot;,
            &quot;prenom&quot;: &quot;JOSE MANUEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 107,
            &quot;licence&quot;: &quot;178141 G&quot;,
            &quot;nom&quot;: &quot;SOUVERAIN&quot;,
            &quot;prenom&quot;: &quot;MICKAEL&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 108,
            &quot;licence&quot;: &quot;185893 H&quot;,
            &quot;nom&quot;: &quot;SOW&quot;,
            &quot;prenom&quot;: &quot;AMADOU&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 109,
            &quot;licence&quot;: &quot;157854 F&quot;,
            &quot;nom&quot;: &quot;STORME&quot;,
            &quot;prenom&quot;: &quot;ANTOINE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 110,
            &quot;licence&quot;: &quot;179957 F&quot;,
            &quot;nom&quot;: &quot;TOTAL&quot;,
            &quot;prenom&quot;: &quot;GILLES&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 111,
            &quot;licence&quot;: &quot;145093 N&quot;,
            &quot;nom&quot;: &quot;VACHER&quot;,
            &quot;prenom&quot;: &quot;JEAN RENE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 112,
            &quot;licence&quot;: &quot;018282 E&quot;,
            &quot;nom&quot;: &quot;VIET&quot;,
            &quot;prenom&quot;: &quot;CHRISTIAN&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 113,
            &quot;licence&quot;: &quot;192243 K&quot;,
            &quot;nom&quot;: &quot;WADEY&quot;,
            &quot;prenom&quot;: &quot;SOLVEGUE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 114,
            &quot;licence&quot;: &quot;179413 P&quot;,
            &quot;nom&quot;: &quot;WAGON&quot;,
            &quot;prenom&quot;: &quot;DJINO&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 115,
            &quot;licence&quot;: &quot;179294 K&quot;,
            &quot;nom&quot;: &quot;WAGON&quot;,
            &quot;prenom&quot;: &quot;PIEDADE&quot;,
            &quot;url&quot;: null
        },
        {
            &quot;id&quot;: 116,
            &quot;licence&quot;: &quot;178350 J&quot;,
            &quot;nom&quot;: &quot;WAGON&quot;,
            &quot;prenom&quot;: &quot;TITO&quot;,
            &quot;url&quot;: null
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 116
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-licencies" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-licencies"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-licencies"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-licencies" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-licencies">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-licencies" data-method="GET"
      data-path="api/v1/licencies"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-licencies', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-licencies"
                    onclick="tryItOut('GETapi-v1-licencies');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-licencies"
                    onclick="cancelTryOut('GETapi-v1-licencies');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-licencies"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/licencies</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-licencies"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-licencies"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-licencies-search--name-">Recherche des licenciés par nom, prénom ou numéro de licence.</h2>

<p>
</p>

<p>La recherche est partielle (LIKE %value%).
Les résultats sont dédupliqués et limités aux champs utiles.</p>

<span id="example-requests-GETapi-v1-licencies-search--name-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/licencies/search/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/licencies/search/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-licencies-search--name-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 49
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;search&quot;: &quot;architecto&quot;,
        &quot;count&quot;: 0
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-licencies-search--name-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-licencies-search--name-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-licencies-search--name-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-licencies-search--name-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-licencies-search--name-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-licencies-search--name-" data-method="GET"
      data-path="api/v1/licencies/search/{name}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-licencies-search--name-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-licencies-search--name-"
                    onclick="tryItOut('GETapi-v1-licencies-search--name-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-licencies-search--name-"
                    onclick="cancelTryOut('GETapi-v1-licencies-search--name-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-licencies-search--name-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/licencies/search/{name}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-licencies-search--name-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-licencies-search--name-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="GETapi-v1-licencies-search--name-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-license-import-batches">Retourne la liste paginée des batchs d&#039;import de licences.</h2>

<p>
</p>

<p>Filtres disponibles :</p>
<ul>
<li>source</li>
<li>status</li>
<li>is_active</li>
<li>trigger_type</li>
<li>from</li>
<li>to</li>
</ul>

<span id="example-requests-GETapi-v1-license-import-batches">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/license-import/batches" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/license-import/batches"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-license-import-batches">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-license-import-batches" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-license-import-batches"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-license-import-batches"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-license-import-batches" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-license-import-batches">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-license-import-batches" data-method="GET"
      data-path="api/v1/license-import/batches"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-license-import-batches', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-license-import-batches"
                    onclick="tryItOut('GETapi-v1-license-import-batches');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-license-import-batches"
                    onclick="cancelTryOut('GETapi-v1-license-import-batches');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-license-import-batches"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/license-import/batches</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-license-import-batches"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-license-import-batches"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-license-import-batches--batch_id-">Retourne le rapport complet d’un batch d’import.</h2>

<p>
</p>

<p>Le rapport est construit par le service métier TelematBatchReportBuilder.</p>

<span id="example-requests-GETapi-v1-license-import-batches--batch_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/license-import/batches/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/license-import/batches/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-license-import-batches--batch_id-">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-license-import-batches--batch_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-license-import-batches--batch_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-license-import-batches--batch_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-license-import-batches--batch_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-license-import-batches--batch_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-license-import-batches--batch_id-" data-method="GET"
      data-path="api/v1/license-import/batches/{batch_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-license-import-batches--batch_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-license-import-batches--batch_id-"
                    onclick="tryItOut('GETapi-v1-license-import-batches--batch_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-license-import-batches--batch_id-"
                    onclick="cancelTryOut('GETapi-v1-license-import-batches--batch_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-license-import-batches--batch_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/license-import/batches/{batch_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-license-import-batches--batch_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-license-import-batches--batch_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>batch_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="batch_id"                data-endpoint="GETapi-v1-license-import-batches--batch_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the batch. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-license-import-batches--batch_id--report">Retourne le rapport complet d’un batch d’import.</h2>

<p>
</p>

<p>Alias explicite de show() pour les routes orientées rapport.</p>

<span id="example-requests-GETapi-v1-license-import-batches--batch_id--report">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/license-import/batches/1/report" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/license-import/batches/1/report"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-license-import-batches--batch_id--report">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-license-import-batches--batch_id--report" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-license-import-batches--batch_id--report"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-license-import-batches--batch_id--report"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-license-import-batches--batch_id--report" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-license-import-batches--batch_id--report">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-license-import-batches--batch_id--report" data-method="GET"
      data-path="api/v1/license-import/batches/{batch_id}/report"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-license-import-batches--batch_id--report', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-license-import-batches--batch_id--report"
                    onclick="tryItOut('GETapi-v1-license-import-batches--batch_id--report');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-license-import-batches--batch_id--report"
                    onclick="cancelTryOut('GETapi-v1-license-import-batches--batch_id--report');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-license-import-batches--batch_id--report"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/license-import/batches/{batch_id}/report</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-license-import-batches--batch_id--report"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-license-import-batches--batch_id--report"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>batch_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="batch_id"                data-endpoint="GETapi-v1-license-import-batches--batch_id--report"
               value="1"
               data-component="url">
    <br>
<p>The ID of the batch. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-license-import-batches--batch_id--diff">Retourne uniquement le diff de projection d’un batch.</h2>

<p>
</p>

<p>Le diff permet de visualiser les insertions, mises à jour,
suppressions et lignes inchangées produites par la projection
vers la table des licenciés.</p>

<span id="example-requests-GETapi-v1-license-import-batches--batch_id--diff">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/license-import/batches/1/diff" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/license-import/batches/1/diff"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-license-import-batches--batch_id--diff">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-license-import-batches--batch_id--diff" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-license-import-batches--batch_id--diff"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-license-import-batches--batch_id--diff"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-license-import-batches--batch_id--diff" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-license-import-batches--batch_id--diff">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-license-import-batches--batch_id--diff" data-method="GET"
      data-path="api/v1/license-import/batches/{batch_id}/diff"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-license-import-batches--batch_id--diff', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-license-import-batches--batch_id--diff"
                    onclick="tryItOut('GETapi-v1-license-import-batches--batch_id--diff');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-license-import-batches--batch_id--diff"
                    onclick="cancelTryOut('GETapi-v1-license-import-batches--batch_id--diff');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-license-import-batches--batch_id--diff"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/license-import/batches/{batch_id}/diff</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-license-import-batches--batch_id--diff"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-license-import-batches--batch_id--diff"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>batch_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="batch_id"                data-endpoint="GETapi-v1-license-import-batches--batch_id--diff"
               value="1"
               data-component="url">
    <br>
<p>The ID of the batch. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-cuescore-rankings">Liste les classements CueScore avec filtres optionnels.</h2>

<p>
</p>

<p>Filtres disponibles :</p>
<ul>
<li>discipline</li>
<li>scope</li>
<li>ranking_type</li>
<li>team_category</li>
<li>is_active</li>
</ul>

<span id="example-requests-GETapi-v1-cuescore-rankings">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/rankings" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/rankings"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-cuescore-rankings">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 48
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;US Demi Finale N1 SUD 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;79941976&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Demi+Finale+N1+SUD_2025-2026/79941976&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 1
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;US Demi Finale N1 NORD 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;79941973&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Demi+Finale+N1+NORD_2025-2026/79941973&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 2
        },
        {
            &quot;id&quot;: 3,
            &quot;name&quot;: &quot;US Finale France U17 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;79880440&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/US_finale+de+France+U17+2025-2026/79880440&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 3
        },
        {
            &quot;id&quot;: 4,
            &quot;name&quot;: &quot;US Classement TN N1 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;66292051&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Classement+TN+N1++2025-2026/66292051&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 4
        },
        {
            &quot;id&quot;: 5,
            &quot;name&quot;: &quot;US CDF 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;74350597&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/US_CDF_2025+2026/74350597&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 5
        },
        {
            &quot;id&quot;: 6,
            &quot;name&quot;: &quot;US Classement TN Masters 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;66292048&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Classement+TN+Masters+2025+2026/66292048&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 6
        },
        {
            &quot;id&quot;: 7,
            &quot;name&quot;: &quot;LBCVL Finale Ligue Americain 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;76092814&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/LBCVL+FINALE+LIGUE+AMERICAIN+25-26/76092814&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 10
        },
        {
            &quot;id&quot;: 8,
            &quot;name&quot;: &quot;Snooker Classement Seniors 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67004710&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+Seniors+2025%252F2026/67004710&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 20
        },
        {
            &quot;id&quot;: 9,
            &quot;name&quot;: &quot;Snooker Classement F&eacute;minin 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67004704&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+f&eacute;minin+2025%252F2026/67004704&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 21
        },
        {
            &quot;id&quot;: 10,
            &quot;name&quot;: &quot;Snooker Classement TN Masters 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67004686&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+TN+Masters+2025-2026/67004686&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 22
        },
        {
            &quot;id&quot;: 11,
            &quot;name&quot;: &quot;Snooker Classement national 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67004695&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+national+2025%252F2026/67004695&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 23
        },
        {
            &quot;id&quot;: 12,
            &quot;name&quot;: &quot;CVL Snooker ZOuest&quot;,
            &quot;cuescore_id&quot;: &quot;73214698&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/CVL+Snooker+ZOuest/73214698#match-73214737&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 24
        },
        {
            &quot;id&quot;: 13,
            &quot;name&quot;: &quot;FFB - Blackball - TN - Handi Billard - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726164&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Handi+Billard/65726164&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 25
        },
        {
            &quot;id&quot;: 14,
            &quot;name&quot;: &quot;FFB - Blackball - TN - Master - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726161&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Master/65726161&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 26
        },
        {
            &quot;id&quot;: 15,
            &quot;name&quot;: &quot;FFB - Blackball - TN - Mixte National - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726158&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Mixte+National/65726158&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 27
        },
        {
            &quot;id&quot;: 16,
            &quot;name&quot;: &quot;FFB - Blackball - TN - V&eacute;t&eacute;ran - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726155&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+V%C3%A9t%C3%A9ran/65726155&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 28
        },
        {
            &quot;id&quot;: 17,
            &quot;name&quot;: &quot;FFB - Blackball - TN - F&eacute;minin - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726152&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+F%C3%A9minin/65726152&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 29
        },
        {
            &quot;id&quot;: 18,
            &quot;name&quot;: &quot;FFB - Blackball - TN - Espoir - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726149&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Espoir/65726149&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 30
        },
        {
            &quot;id&quot;: 19,
            &quot;name&quot;: &quot;FFB - Blackball - TN - Junior - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;65726146&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Junior/65726146&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 31
        },
        {
            &quot;id&quot;: 20,
            &quot;name&quot;: &quot;TOP LIGUE 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097476&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/TOP+LIGUE+25-26/67097476&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 32
        },
        {
            &quot;id&quot;: 21,
            &quot;name&quot;: &quot;MIXTE 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097479&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/MIXTE+25-26/67097479&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 33
        },
        {
            &quot;id&quot;: 22,
            &quot;name&quot;: &quot;VETERAN 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097482&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/VETERAN+25-26/67097482&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 34
        },
        {
            &quot;id&quot;: 23,
            &quot;name&quot;: &quot;FEMININ 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097485&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/FEMININ+25-26/67097485&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 35
        },
        {
            &quot;id&quot;: 24,
            &quot;name&quot;: &quot;ESPOIR 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097488&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/ESPOIR+25-26/67097488&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 36
        },
        {
            &quot;id&quot;: 25,
            &quot;name&quot;: &quot;JUNIOR 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097494&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/JUNIOR+25-26/67097494&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 37
        },
        {
            &quot;id&quot;: 26,
            &quot;name&quot;: &quot;BENJAMIN 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097506&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/BENJAMIN+25-26/67097506&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 38
        },
        {
            &quot;id&quot;: 27,
            &quot;name&quot;: &quot;HANDI-DEBOUT 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097509&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/HANDI-DEBOUT+25-26/67097509&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 39
        },
        {
            &quot;id&quot;: 28,
            &quot;name&quot;: &quot;HANDI-FAUTEUIL 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;77825152&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/HANDI-FAUTEUIL+25-26/77825152&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 40
        },
        {
            &quot;id&quot;: 29,
            &quot;name&quot;: &quot;TRZ OUEST MIXTE 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;71389255&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/TRZ+OUEST+MIXTE+25-26/71389255&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 41
        },
        {
            &quot;id&quot;: 30,
            &quot;name&quot;: &quot;TRZ OUEST FEMININ 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;71389252&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/ranking/TRZ+OUEST+FEMININ+25-26/71389252&quot;,
            &quot;source_type&quot;: &quot;ranking&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;,
            &quot;team_category&quot;: null,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 42
        },
        {
            &quot;id&quot;: 31,
            &quot;name&quot;: &quot;FFB - Blackball - Equipes - DN1 - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67490224&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN1+-+2025-2026/67490224&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DN1&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 43
        },
        {
            &quot;id&quot;: 32,
            &quot;name&quot;: &quot;FFB - Blackball - Equipes - DN2 - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67490356&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN2+-+2025-2026/67490356&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DN2&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 44
        },
        {
            &quot;id&quot;: 33,
            &quot;name&quot;: &quot;FFB - Blackball - Equipes - DN3 - 2025-2026&quot;,
            &quot;cuescore_id&quot;: &quot;67490959&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN3+-+2025-2026/67490959&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DN3&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 45
        },
        {
            &quot;id&quot;: 34,
            &quot;name&quot;: &quot;BB_CVL &Eacute;QUIPE DR1 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097593&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/BB_CVL+&Eacute;QUIPE+DR1+25-26/67097593&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DR1&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 46
        },
        {
            &quot;id&quot;: 35,
            &quot;name&quot;: &quot;BB CVL &Eacute;QUIPE DR2 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097608&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/BB_CVL+&Eacute;QUIPE+DR2+25-26/67097608&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DR2&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 47
        },
        {
            &quot;id&quot;: 36,
            &quot;name&quot;: &quot;BB CVL &Eacute;QUIPE DR3 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;67097614&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/BB_CVL+&Eacute;QUIPE+DR3+25-26/67097614&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;regional&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DR3&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 48
        },
        {
            &quot;id&quot;: 37,
            &quot;name&quot;: &quot;CVL &Eacute;QUIPE DR4 ZONE OUEST 25-26&quot;,
            &quot;cuescore_id&quot;: &quot;69856342&quot;,
            &quot;url&quot;: &quot;https://cuescore.com/tournament/CVL+&Eacute;QUIPE+DR4+ZONE+OUEST+25-26/69856342#match-69864133&quot;,
            &quot;source_type&quot;: &quot;tournament&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
            &quot;ranking_type&quot;: &quot;team&quot;,
            &quot;team_category&quot;: &quot;DR4&quot;,
            &quot;season&quot;: &quot;2025-2026&quot;,
            &quot;is_active&quot;: true,
            &quot;sort_order&quot;: 49
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 37
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-cuescore-rankings" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-cuescore-rankings"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-cuescore-rankings"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-cuescore-rankings" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-cuescore-rankings">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-cuescore-rankings" data-method="GET"
      data-path="api/v1/cuescore/rankings"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-cuescore-rankings', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-cuescore-rankings"
                    onclick="tryItOut('GETapi-v1-cuescore-rankings');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-cuescore-rankings"
                    onclick="cancelTryOut('GETapi-v1-cuescore-rankings');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-cuescore-rankings"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/cuescore/rankings</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-cuescore-rankings"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-cuescore-rankings"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-cuescore-club">Vue agrégée des classements club basée sur des filtres query.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-cuescore-club">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/club" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/club"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-cuescore-club">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 47
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;US Demi Finale N1 SUD 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;79941976&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Demi+Finale+N1+SUD_2025-2026/79941976&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 1
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 1,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:14+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 0,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 2,
                &quot;name&quot;: &quot;US Demi Finale N1 NORD 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;79941973&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Demi+Finale+N1+NORD_2025-2026/79941973&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 2
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 2,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:15+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 0,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 3,
                &quot;name&quot;: &quot;US Finale France U17 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;79880440&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/US_finale+de+France+U17+2025-2026/79880440&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 3
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 3,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:17+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 8,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 4,
                &quot;name&quot;: &quot;US Classement TN N1 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;66292051&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Classement+TN+N1++2025-2026/66292051&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 4
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 4,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:20+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 116,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 2,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 5,
                    &quot;participant_name&quot;: &quot;Christophe Lambert&quot;,
                    &quot;participant_external_id&quot;: &quot;1286610&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+Lambert/1286610&quot;,
                    &quot;points&quot;: &quot;772.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 56,
                        &quot;licence&quot;: &quot;121547 X&quot;,
                        &quot;nom&quot;: &quot;LAMBERT&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 82,
                    &quot;participant_name&quot;: &quot;Christophe Faurie&quot;,
                    &quot;participant_external_id&quot;: &quot;31580935&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+Faurie/31580935&quot;,
                    &quot;points&quot;: &quot;70.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 44,
                        &quot;licence&quot;: &quot;116369 T&quot;,
                        &quot;nom&quot;: &quot;FAURIE&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 5,
                &quot;name&quot;: &quot;US CDF 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;74350597&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/US_CDF_2025+2026/74350597&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 5
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 5,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:23+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 16,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 6,
                &quot;name&quot;: &quot;US Classement TN Masters 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;66292048&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Classement+TN+Masters+2025+2026/66292048&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 6
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 6,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:25+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 16,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 7,
                &quot;name&quot;: &quot;LBCVL Finale Ligue Americain 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;76092814&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/LBCVL+FINALE+LIGUE+AMERICAIN+25-26/76092814&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;americain&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 10
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 7,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:27+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 8,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 8,
                &quot;name&quot;: &quot;Snooker Classement Seniors 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67004710&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+Seniors+2025%252F2026/67004710&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;snooker&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 20
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 8,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:29+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 15,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 9,
                &quot;name&quot;: &quot;Snooker Classement F&eacute;minin 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67004704&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+f&eacute;minin+2025%252F2026/67004704&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;snooker&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 21
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 9,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:31+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 9,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;participant_name&quot;: &quot;Oph&eacute;lie Laval&quot;,
                    &quot;participant_external_id&quot;: &quot;30608668&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Oph%C3%A9lie+Laval/30608668&quot;,
                    &quot;points&quot;: &quot;220.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 58,
                        &quot;licence&quot;: &quot;152188 X&quot;,
                        &quot;nom&quot;: &quot;LAVAL&quot;,
                        &quot;prenom&quot;: &quot;OPHELIE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 10,
                &quot;name&quot;: &quot;Snooker Classement TN Masters 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67004686&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+TN+Masters+2025-2026/67004686&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;snooker&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 22
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 10,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:33+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 12,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 12,
                    &quot;participant_name&quot;: &quot;Oph&eacute;lie Laval&quot;,
                    &quot;participant_external_id&quot;: &quot;30608668&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Oph%C3%A9lie+Laval/30608668&quot;,
                    &quot;points&quot;: &quot;390.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 58,
                        &quot;licence&quot;: &quot;152188 X&quot;,
                        &quot;nom&quot;: &quot;LAVAL&quot;,
                        &quot;prenom&quot;: &quot;OPHELIE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 11,
                &quot;name&quot;: &quot;Snooker Classement national 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67004695&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/SNOOKER_Classement+national+2025%252F2026/67004695&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;snooker&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 23
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 11,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:36+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 145,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: []
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 12,
                &quot;name&quot;: &quot;CVL Snooker ZOuest&quot;,
                &quot;cuescore_id&quot;: &quot;73214698&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/CVL+Snooker+ZOuest/73214698#match-73214737&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;snooker&quot;,
                &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 24
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 12,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:40+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 7,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 2,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 4,
                    &quot;participant_name&quot;: &quot;David CHERAMY&quot;,
                    &quot;participant_external_id&quot;: &quot;31530742&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/David+CHERAMY/31530742&quot;,
                    &quot;points&quot;: null,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 21,
                        &quot;licence&quot;: &quot;111518 E&quot;,
                        &quot;nom&quot;: &quot;CHERAMY&quot;,
                        &quot;prenom&quot;: &quot;DAVID&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 6,
                    &quot;participant_name&quot;: &quot;OMAR MOUSSAMIH&quot;,
                    &quot;participant_external_id&quot;: &quot;32637808&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/OMAR+MOUSSAMIH/32637808&quot;,
                    &quot;points&quot;: null,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 83,
                        &quot;licence&quot;: &quot;182056 M&quot;,
                        &quot;nom&quot;: &quot;MOUSSAMIH&quot;,
                        &quot;prenom&quot;: &quot;OMAR&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 13,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Handi Billard - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726164&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Handi+Billard/65726164&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 25
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 13,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:42+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 5,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 3,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 2,
                    &quot;participant_name&quot;: &quot;Thomas RAINEAU&quot;,
                    &quot;participant_external_id&quot;: &quot;32452216&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Thomas+RAINEAU/32452216&quot;,
                    &quot;points&quot;: &quot;140.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 95,
                        &quot;licence&quot;: &quot;173909 G&quot;,
                        &quot;nom&quot;: &quot;RAINEAU&quot;,
                        &quot;prenom&quot;: &quot;THOMAS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;participant_name&quot;: &quot;Christophe FERASSE&quot;,
                    &quot;participant_external_id&quot;: &quot;33399133&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+FERASSE/33399133&quot;,
                    &quot;points&quot;: &quot;112.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 45,
                        &quot;licence&quot;: &quot;180354 M&quot;,
                        &quot;nom&quot;: &quot;FERASSE&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 4,
                    &quot;participant_name&quot;: &quot;Gregory Cadinot&quot;,
                    &quot;participant_external_id&quot;: &quot;30706906&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Gregory+Cadinot/30706906&quot;,
                    &quot;points&quot;: &quot;88.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 16,
                        &quot;licence&quot;: &quot;157681 S&quot;,
                        &quot;nom&quot;: &quot;CADINOT&quot;,
                        &quot;prenom&quot;: &quot;GREGORY&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 14,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Master - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726161&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Master/65726161&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 26
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 14,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:44+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 24,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 6,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 4,
                    &quot;participant_name&quot;: &quot;Christophe Lambert&quot;,
                    &quot;participant_external_id&quot;: &quot;1286610&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+Lambert/1286610&quot;,
                    &quot;points&quot;: &quot;1552.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 56,
                        &quot;licence&quot;: &quot;121547 X&quot;,
                        &quot;nom&quot;: &quot;LAMBERT&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 5,
                    &quot;participant_name&quot;: &quot;Alexandre Buscetti&quot;,
                    &quot;participant_external_id&quot;: &quot;15345739&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Alexandre+Buscetti/15345739&quot;,
                    &quot;points&quot;: &quot;1440.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 14,
                        &quot;licence&quot;: &quot;141625 D&quot;,
                        &quot;nom&quot;: &quot;BUSCETTI&quot;,
                        &quot;prenom&quot;: &quot;ALEXANDRE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 10,
                    &quot;participant_name&quot;: &quot;Paul Coldrick&quot;,
                    &quot;participant_external_id&quot;: &quot;8112141&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Paul+Coldrick/8112141&quot;,
                    &quot;points&quot;: &quot;968.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 24,
                        &quot;licence&quot;: &quot;157999 N&quot;,
                        &quot;nom&quot;: &quot;COLDRICK&quot;,
                        &quot;prenom&quot;: &quot;PAUL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 12,
                    &quot;participant_name&quot;: &quot;Julien Leroux&quot;,
                    &quot;participant_external_id&quot;: &quot;30265855&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Julien+Leroux/30265855&quot;,
                    &quot;points&quot;: &quot;904.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 70,
                        &quot;licence&quot;: &quot;104868 K&quot;,
                        &quot;nom&quot;: &quot;LEROUX&quot;,
                        &quot;prenom&quot;: &quot;JULIEN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 16,
                    &quot;participant_name&quot;: &quot;Simon Pellissier&quot;,
                    &quot;participant_external_id&quot;: &quot;21763675&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Simon+Pellissier/21763675&quot;,
                    &quot;points&quot;: &quot;872.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 90,
                        &quot;licence&quot;: &quot;146692 A&quot;,
                        &quot;nom&quot;: &quot;PELLISSIER&quot;,
                        &quot;prenom&quot;: &quot;SIMON&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 17,
                    &quot;participant_name&quot;: &quot;Elie Christidis&quot;,
                    &quot;participant_external_id&quot;: &quot;31808725&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Elie+Christidis/31808725&quot;,
                    &quot;points&quot;: &quot;864.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 23,
                        &quot;licence&quot;: &quot;155556 H&quot;,
                        &quot;nom&quot;: &quot;CHRISTIDIS&quot;,
                        &quot;prenom&quot;: &quot;ELIE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 15,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Mixte National - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726158&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Mixte+National/65726158&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 27
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 15,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:47+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 558,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 15,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 5,
                    &quot;participant_name&quot;: &quot;Jerome L anthoen&quot;,
                    &quot;participant_external_id&quot;: &quot;30796072&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Jerome+L+anthoen/30796072&quot;,
                    &quot;points&quot;: &quot;630.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 55,
                        &quot;licence&quot;: &quot;156527 N&quot;,
                        &quot;nom&quot;: &quot;L ANTHOEN&quot;,
                        &quot;prenom&quot;: &quot;JEROME&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 26,
                    &quot;participant_name&quot;: &quot;Christophe Faurie&quot;,
                    &quot;participant_external_id&quot;: &quot;31580935&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+Faurie/31580935&quot;,
                    &quot;points&quot;: &quot;470.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 44,
                        &quot;licence&quot;: &quot;116369 T&quot;,
                        &quot;nom&quot;: &quot;FAURIE&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 32,
                    &quot;participant_name&quot;: &quot;Yvan SKNOEZECK&quot;,
                    &quot;participant_external_id&quot;: &quot;30066106&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Yvan+SKNOEZECK/30066106&quot;,
                    &quot;points&quot;: &quot;446.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 105,
                        &quot;licence&quot;: &quot;110008 C&quot;,
                        &quot;nom&quot;: &quot;SKNOEZECK&quot;,
                        &quot;prenom&quot;: &quot;YVAN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 41,
                    &quot;participant_name&quot;: &quot;Antoine Storme&quot;,
                    &quot;participant_external_id&quot;: &quot;30069262&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Antoine+Storme/30069262&quot;,
                    &quot;points&quot;: &quot;410.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 109,
                        &quot;licence&quot;: &quot;157854 F&quot;,
                        &quot;nom&quot;: &quot;STORME&quot;,
                        &quot;prenom&quot;: &quot;ANTOINE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 58,
                    &quot;participant_name&quot;: &quot;Oph&eacute;lie Laval&quot;,
                    &quot;participant_external_id&quot;: &quot;30608668&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Oph%C3%A9lie+Laval/30608668&quot;,
                    &quot;points&quot;: &quot;372.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 58,
                        &quot;licence&quot;: &quot;152188 X&quot;,
                        &quot;nom&quot;: &quot;LAVAL&quot;,
                        &quot;prenom&quot;: &quot;OPHELIE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 250,
                    &quot;participant_name&quot;: &quot;Cl&eacute;ment Da Costa&quot;,
                    &quot;participant_external_id&quot;: &quot;32103676&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Cl%C3%A9ment+Da+Costa/32103676&quot;,
                    &quot;points&quot;: &quot;80.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 29,
                        &quot;licence&quot;: &quot;173585 E&quot;,
                        &quot;nom&quot;: &quot;DA COSTA&quot;,
                        &quot;prenom&quot;: &quot;CLEMENT&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 328,
                    &quot;participant_name&quot;: &quot;MICHAEL MAILLET&quot;,
                    &quot;participant_external_id&quot;: &quot;31085209&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/MICHAEL+MAILLET/31085209&quot;,
                    &quot;points&quot;: &quot;44.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 77,
                        &quot;licence&quot;: &quot;170602 M&quot;,
                        &quot;nom&quot;: &quot;MAILLET&quot;,
                        &quot;prenom&quot;: &quot;MICHAEL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 339,
                    &quot;participant_name&quot;: &quot;OMAR MOUSSAMIH&quot;,
                    &quot;participant_external_id&quot;: &quot;32637808&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/OMAR+MOUSSAMIH/32637808&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 83,
                        &quot;licence&quot;: &quot;182056 M&quot;,
                        &quot;nom&quot;: &quot;MOUSSAMIH&quot;,
                        &quot;prenom&quot;: &quot;OMAR&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 353,
                    &quot;participant_name&quot;: &quot;David CHERAMY&quot;,
                    &quot;participant_external_id&quot;: &quot;31530742&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/David+CHERAMY/31530742&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 21,
                        &quot;licence&quot;: &quot;111518 E&quot;,
                        &quot;nom&quot;: &quot;CHERAMY&quot;,
                        &quot;prenom&quot;: &quot;DAVID&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 370,
                    &quot;participant_name&quot;: &quot;Zakaria El attari&quot;,
                    &quot;participant_external_id&quot;: &quot;67417390&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Zakaria+El+attari/67417390&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 42,
                        &quot;licence&quot;: &quot;190158 T&quot;,
                        &quot;nom&quot;: &quot;EL ATTARI&quot;,
                        &quot;prenom&quot;: &quot;ZAKARIA&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 376,
                    &quot;participant_name&quot;: &quot;Math&eacute;o Bouteille&quot;,
                    &quot;participant_external_id&quot;: &quot;32407420&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Math%C3%A9o+Bouteille/32407420&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 11,
                        &quot;licence&quot;: &quot;137219 R&quot;,
                        &quot;nom&quot;: &quot;BOUTEILLE&quot;,
                        &quot;prenom&quot;: &quot;MATHEO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 395,
                    &quot;participant_name&quot;: &quot;Anass MAHFOUDI&quot;,
                    &quot;participant_external_id&quot;: &quot;32533960&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Anass+MAHFOUDI/32533960&quot;,
                    &quot;points&quot;: &quot;22.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 73,
                        &quot;licence&quot;: &quot;182058 P&quot;,
                        &quot;nom&quot;: &quot;MAHFOUDI&quot;,
                        &quot;prenom&quot;: &quot;ANASS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 395,
                    &quot;participant_name&quot;: &quot;Tarik bouattaoun&quot;,
                    &quot;participant_external_id&quot;: &quot;52919002&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tarik+bouattaoun/52919002&quot;,
                    &quot;points&quot;: &quot;22.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 9,
                        &quot;licence&quot;: &quot;188587 L&quot;,
                        &quot;nom&quot;: &quot;BOUATTAOUN&quot;,
                        &quot;prenom&quot;: &quot;TARIK&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 419,
                    &quot;participant_name&quot;: &quot;Christian OLMEDA&quot;,
                    &quot;participant_external_id&quot;: &quot;32957365&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christian+OLMEDA/32957365&quot;,
                    &quot;points&quot;: &quot;22.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 85,
                        &quot;licence&quot;: &quot;177784 T&quot;,
                        &quot;nom&quot;: &quot;OLMEDA&quot;,
                        &quot;prenom&quot;: &quot;CHRISTIAN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 485,
                    &quot;participant_name&quot;: &quot;DARIUS GANNAT&quot;,
                    &quot;participant_external_id&quot;: &quot;33554953&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/DARIUS+GANNAT/33554953&quot;,
                    &quot;points&quot;: &quot;22.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 47,
                        &quot;licence&quot;: &quot;109197 X&quot;,
                        &quot;nom&quot;: &quot;GANNAT&quot;,
                        &quot;prenom&quot;: &quot;DARIUS&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 16,
                &quot;name&quot;: &quot;FFB - Blackball - TN - V&eacute;t&eacute;ran - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726155&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+V%C3%A9t%C3%A9ran/65726155&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 28
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 16,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:27:57+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 111,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 9,
                    &quot;participant_name&quot;: &quot;Jerome L anthoen&quot;,
                    &quot;participant_external_id&quot;: &quot;30796072&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Jerome+L+anthoen/30796072&quot;,
                    &quot;points&quot;: &quot;368.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 55,
                        &quot;licence&quot;: &quot;156527 N&quot;,
                        &quot;nom&quot;: &quot;L ANTHOEN&quot;,
                        &quot;prenom&quot;: &quot;JEROME&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 17,
                &quot;name&quot;: &quot;FFB - Blackball - TN - F&eacute;minin - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726152&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+F%C3%A9minin/65726152&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 29
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 17,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:00+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 57,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 5,
                    &quot;participant_name&quot;: &quot;Oph&eacute;lie Laval&quot;,
                    &quot;participant_external_id&quot;: &quot;30608668&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Oph%C3%A9lie+Laval/30608668&quot;,
                    &quot;points&quot;: &quot;524.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 58,
                        &quot;licence&quot;: &quot;152188 X&quot;,
                        &quot;nom&quot;: &quot;LAVAL&quot;,
                        &quot;prenom&quot;: &quot;OPHELIE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 18,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Espoir - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726149&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Espoir/65726149&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 30
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 18,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:02+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 43,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 22,
                    &quot;participant_name&quot;: &quot;Math&eacute;o Bouteille&quot;,
                    &quot;participant_external_id&quot;: &quot;32407420&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Math%C3%A9o+Bouteille/32407420&quot;,
                    &quot;points&quot;: &quot;60.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 11,
                        &quot;licence&quot;: &quot;137219 R&quot;,
                        &quot;nom&quot;: &quot;BOUTEILLE&quot;,
                        &quot;prenom&quot;: &quot;MATHEO&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 19,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Junior - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;65726146&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FFB+-+Blackball+-+TN+-+Junior/65726146&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 31
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 19,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:05+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 36,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 13,
                    &quot;participant_name&quot;: &quot;Cl&eacute;ment Da Costa&quot;,
                    &quot;participant_external_id&quot;: &quot;32103676&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Cl%C3%A9ment+Da+Costa/32103676&quot;,
                    &quot;points&quot;: &quot;180.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 29,
                        &quot;licence&quot;: &quot;173585 E&quot;,
                        &quot;nom&quot;: &quot;DA COSTA&quot;,
                        &quot;prenom&quot;: &quot;CLEMENT&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 20,
                &quot;name&quot;: &quot;TOP LIGUE 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097476&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/TOP+LIGUE+25-26/67097476&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 32
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 20,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:07+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 32,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 3,
                    &quot;participant_name&quot;: &quot;Yvan SKNOEZECK&quot;,
                    &quot;participant_external_id&quot;: &quot;30066106&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Yvan+SKNOEZECK/30066106&quot;,
                    &quot;points&quot;: &quot;1460.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 105,
                        &quot;licence&quot;: &quot;110008 C&quot;,
                        &quot;nom&quot;: &quot;SKNOEZECK&quot;,
                        &quot;prenom&quot;: &quot;YVAN&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 21,
                &quot;name&quot;: &quot;MIXTE 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097479&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/MIXTE+25-26/67097479&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 33
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 21,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:10+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 236,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 25,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 9,
                    &quot;participant_name&quot;: &quot;Tarik bouattaoun&quot;,
                    &quot;participant_external_id&quot;: &quot;52919002&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tarik+bouattaoun/52919002&quot;,
                    &quot;points&quot;: &quot;795.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 9,
                        &quot;licence&quot;: &quot;188587 L&quot;,
                        &quot;nom&quot;: &quot;BOUATTAOUN&quot;,
                        &quot;prenom&quot;: &quot;TARIK&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 30,
                    &quot;participant_name&quot;: &quot;David CHERAMY&quot;,
                    &quot;participant_external_id&quot;: &quot;31530742&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/David+CHERAMY/31530742&quot;,
                    &quot;points&quot;: &quot;660.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 21,
                        &quot;licence&quot;: &quot;111518 E&quot;,
                        &quot;nom&quot;: &quot;CHERAMY&quot;,
                        &quot;prenom&quot;: &quot;DAVID&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 46,
                    &quot;participant_name&quot;: &quot;Christian OLMEDA&quot;,
                    &quot;participant_external_id&quot;: &quot;32957365&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christian+OLMEDA/32957365&quot;,
                    &quot;points&quot;: &quot;536.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 85,
                        &quot;licence&quot;: &quot;177784 T&quot;,
                        &quot;nom&quot;: &quot;OLMEDA&quot;,
                        &quot;prenom&quot;: &quot;CHRISTIAN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 51,
                    &quot;participant_name&quot;: &quot;Math&eacute;o Bouteille&quot;,
                    &quot;participant_external_id&quot;: &quot;32407420&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Math%C3%A9o+Bouteille/32407420&quot;,
                    &quot;points&quot;: &quot;516.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 11,
                        &quot;licence&quot;: &quot;137219 R&quot;,
                        &quot;nom&quot;: &quot;BOUTEILLE&quot;,
                        &quot;prenom&quot;: &quot;MATHEO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 53,
                    &quot;participant_name&quot;: &quot;bastien MICHEZ&quot;,
                    &quot;participant_external_id&quot;: &quot;31559914&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/bastien+MICHEZ/31559914&quot;,
                    &quot;points&quot;: &quot;508.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 82,
                        &quot;licence&quot;: &quot;144532 Y&quot;,
                        &quot;nom&quot;: &quot;MICHEZ&quot;,
                        &quot;prenom&quot;: &quot;BASTIEN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 54,
                    &quot;participant_name&quot;: &quot;Cl&eacute;ment Da Costa&quot;,
                    &quot;participant_external_id&quot;: &quot;32103676&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Cl%C3%A9ment+Da+Costa/32103676&quot;,
                    &quot;points&quot;: &quot;507.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 29,
                        &quot;licence&quot;: &quot;173585 E&quot;,
                        &quot;nom&quot;: &quot;DA COSTA&quot;,
                        &quot;prenom&quot;: &quot;CLEMENT&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 57,
                    &quot;participant_name&quot;: &quot;DARIUS GANNAT&quot;,
                    &quot;participant_external_id&quot;: &quot;33554953&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/DARIUS+GANNAT/33554953&quot;,
                    &quot;points&quot;: &quot;497.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 47,
                        &quot;licence&quot;: &quot;109197 X&quot;,
                        &quot;nom&quot;: &quot;GANNAT&quot;,
                        &quot;prenom&quot;: &quot;DARIUS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 62,
                    &quot;participant_name&quot;: &quot;Alexandre Bourlier&quot;,
                    &quot;participant_external_id&quot;: &quot;30257935&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Alexandre+Bourlier/30257935&quot;,
                    &quot;points&quot;: &quot;493.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 10,
                        &quot;licence&quot;: &quot;177407 J&quot;,
                        &quot;nom&quot;: &quot;BOURLIER&quot;,
                        &quot;prenom&quot;: &quot;ALEXANDRE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 71,
                    &quot;participant_name&quot;: &quot;MICHAEL MAILLET&quot;,
                    &quot;participant_external_id&quot;: &quot;31085209&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/MICHAEL+MAILLET/31085209&quot;,
                    &quot;points&quot;: &quot;477.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 77,
                        &quot;licence&quot;: &quot;170602 M&quot;,
                        &quot;nom&quot;: &quot;MAILLET&quot;,
                        &quot;prenom&quot;: &quot;MICHAEL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 76,
                    &quot;participant_name&quot;: &quot;BENJAMIN CHEV&Eacute;&quot;,
                    &quot;participant_external_id&quot;: &quot;34388548&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/BENJAMIN+CHEV%C3%89/34388548&quot;,
                    &quot;points&quot;: &quot;453.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 22,
                        &quot;licence&quot;: &quot;141681 H&quot;,
                        &quot;nom&quot;: &quot;CHEVE&quot;,
                        &quot;prenom&quot;: &quot;BENJAMIN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 92,
                    &quot;participant_name&quot;: &quot;David DA COSTA&quot;,
                    &quot;participant_external_id&quot;: &quot;30258862&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/David+DA+COSTA/30258862&quot;,
                    &quot;points&quot;: &quot;420.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 30,
                        &quot;licence&quot;: &quot;173661 M&quot;,
                        &quot;nom&quot;: &quot;DA COSTA&quot;,
                        &quot;prenom&quot;: &quot;DAVID&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 100,
                    &quot;participant_name&quot;: &quot;Quentin Leneez&quot;,
                    &quot;participant_external_id&quot;: &quot;41942272&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Quentin+Leneez/41942272&quot;,
                    &quot;points&quot;: &quot;392.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 67,
                        &quot;licence&quot;: &quot;184162 B&quot;,
                        &quot;nom&quot;: &quot;LENEEZ&quot;,
                        &quot;prenom&quot;: &quot;QUENTIN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 103,
                    &quot;participant_name&quot;: &quot;mickael souverain&quot;,
                    &quot;participant_external_id&quot;: &quot;32035528&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/mickael+souverain/32035528&quot;,
                    &quot;points&quot;: &quot;392.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 107,
                        &quot;licence&quot;: &quot;178141 G&quot;,
                        &quot;nom&quot;: &quot;SOUVERAIN&quot;,
                        &quot;prenom&quot;: &quot;MICKAEL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 115,
                    &quot;participant_name&quot;: &quot;Djino Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33642442&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Djino+Wagon/33642442&quot;,
                    &quot;points&quot;: &quot;364.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 114,
                        &quot;licence&quot;: &quot;179413 P&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;DJINO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 119,
                    &quot;participant_name&quot;: &quot;OMAR MOUSSAMIH&quot;,
                    &quot;participant_external_id&quot;: &quot;32637808&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/OMAR+MOUSSAMIH/32637808&quot;,
                    &quot;points&quot;: &quot;352.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 83,
                        &quot;licence&quot;: &quot;182056 M&quot;,
                        &quot;nom&quot;: &quot;MOUSSAMIH&quot;,
                        &quot;prenom&quot;: &quot;OMAR&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 151,
                    &quot;participant_name&quot;: &quot;Piedade Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33417292&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Piedade+Wagon/33417292&quot;,
                    &quot;points&quot;: &quot;252.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 115,
                        &quot;licence&quot;: &quot;179294 K&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;PIEDADE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 156,
                    &quot;participant_name&quot;: &quot;Tito Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33642439&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tito+Wagon/33642439&quot;,
                    &quot;points&quot;: &quot;238.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 116,
                        &quot;licence&quot;: &quot;178350 J&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;TITO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 165,
                    &quot;participant_name&quot;: &quot;Thomas RAINEAU&quot;,
                    &quot;participant_external_id&quot;: &quot;32452216&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Thomas+RAINEAU/32452216&quot;,
                    &quot;points&quot;: &quot;224.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 95,
                        &quot;licence&quot;: &quot;173909 G&quot;,
                        &quot;nom&quot;: &quot;RAINEAU&quot;,
                        &quot;prenom&quot;: &quot;THOMAS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 175,
                    &quot;participant_name&quot;: &quot;Zakaria El attari&quot;,
                    &quot;participant_external_id&quot;: &quot;67417390&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Zakaria+El+attari/67417390&quot;,
                    &quot;points&quot;: &quot;168.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 42,
                        &quot;licence&quot;: &quot;190158 T&quot;,
                        &quot;nom&quot;: &quot;EL ATTARI&quot;,
                        &quot;prenom&quot;: &quot;ZAKARIA&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 188,
                    &quot;participant_name&quot;: &quot;Anass MAHFOUDI&quot;,
                    &quot;participant_external_id&quot;: &quot;32533960&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Anass+MAHFOUDI/32533960&quot;,
                    &quot;points&quot;: &quot;126.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 73,
                        &quot;licence&quot;: &quot;182058 P&quot;,
                        &quot;nom&quot;: &quot;MAHFOUDI&quot;,
                        &quot;prenom&quot;: &quot;ANASS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 213,
                    &quot;participant_name&quot;: &quot;Tristan Pierrot&quot;,
                    &quot;participant_external_id&quot;: &quot;33690460&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tristan+Pierrot/33690460&quot;,
                    &quot;points&quot;: &quot;70.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 92,
                        &quot;licence&quot;: &quot;160792 Z&quot;,
                        &quot;nom&quot;: &quot;PIERROT&quot;,
                        &quot;prenom&quot;: &quot;TRISTAN&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 217,
                    &quot;participant_name&quot;: &quot;J&eacute;r&ocirc;me Jussy&quot;,
                    &quot;participant_external_id&quot;: &quot;33409729&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/J%C3%A9r%C3%B4me+Jussy/33409729&quot;,
                    &quot;points&quot;: &quot;70.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 54,
                        &quot;licence&quot;: &quot;156524 K&quot;,
                        &quot;nom&quot;: &quot;JUSSY&quot;,
                        &quot;prenom&quot;: &quot;JEROME&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 224,
                    &quot;participant_name&quot;: &quot;Christophe FERASSE&quot;,
                    &quot;participant_external_id&quot;: &quot;33399133&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+FERASSE/33399133&quot;,
                    &quot;points&quot;: &quot;56.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 45,
                        &quot;licence&quot;: &quot;180354 M&quot;,
                        &quot;nom&quot;: &quot;FERASSE&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 230,
                    &quot;participant_name&quot;: &quot;J&eacute;r&ocirc;me H&eacute;risson&quot;,
                    &quot;participant_external_id&quot;: &quot;49534204&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/J%C3%A9r%C3%B4me+H%C3%A9risson/49534204&quot;,
                    &quot;points&quot;: &quot;56.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 51,
                        &quot;licence&quot;: &quot;177932 E&quot;,
                        &quot;nom&quot;: &quot;HERISSON&quot;,
                        &quot;prenom&quot;: &quot;JEROME&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 230,
                    &quot;participant_name&quot;: &quot;Philippe Robuchon&quot;,
                    &quot;participant_external_id&quot;: &quot;33409852&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Philippe+Robuchon/33409852&quot;,
                    &quot;points&quot;: &quot;56.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 102,
                        &quot;licence&quot;: &quot;179958 G&quot;,
                        &quot;nom&quot;: &quot;ROBUCHON&quot;,
                        &quot;prenom&quot;: &quot;PHILIPPE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 22,
                &quot;name&quot;: &quot;VETERAN 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097482&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/VETERAN+25-26/67097482&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 34
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 22,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:15+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 59,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 39,
                    &quot;participant_name&quot;: &quot;DARIUS GANNAT&quot;,
                    &quot;participant_external_id&quot;: &quot;33554953&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/DARIUS+GANNAT/33554953&quot;,
                    &quot;points&quot;: &quot;70.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 47,
                        &quot;licence&quot;: &quot;109197 X&quot;,
                        &quot;nom&quot;: &quot;GANNAT&quot;,
                        &quot;prenom&quot;: &quot;DARIUS&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 23,
                &quot;name&quot;: &quot;FEMININ 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097485&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/FEMININ+25-26/67097485&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 35
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 23,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:18+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 22,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 16,
                    &quot;participant_name&quot;: &quot;Piedade Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33417292&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Piedade+Wagon/33417292&quot;,
                    &quot;points&quot;: &quot;30.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 115,
                        &quot;licence&quot;: &quot;179294 K&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;PIEDADE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 24,
                &quot;name&quot;: &quot;ESPOIR 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097488&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/ESPOIR+25-26/67097488&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 36
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 24,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:20+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 10,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 2,
                    &quot;participant_name&quot;: &quot;Math&eacute;o Bouteille&quot;,
                    &quot;participant_external_id&quot;: &quot;32407420&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Math%C3%A9o+Bouteille/32407420&quot;,
                    &quot;points&quot;: &quot;435.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 11,
                        &quot;licence&quot;: &quot;137219 R&quot;,
                        &quot;nom&quot;: &quot;BOUTEILLE&quot;,
                        &quot;prenom&quot;: &quot;MATHEO&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 25,
                &quot;name&quot;: &quot;JUNIOR 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097494&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/JUNIOR+25-26/67097494&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 37
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 25,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:21+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 7,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 7,
                    &quot;participant_name&quot;: &quot;Tito Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33642439&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tito+Wagon/33642439&quot;,
                    &quot;points&quot;: &quot;195.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 116,
                        &quot;licence&quot;: &quot;178350 J&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;TITO&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 26,
                &quot;name&quot;: &quot;BENJAMIN 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097506&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/BENJAMIN+25-26/67097506&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 38
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 26,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:23+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 20,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 3,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;participant_name&quot;: &quot;Cl&eacute;ment Da Costa&quot;,
                    &quot;participant_external_id&quot;: &quot;32103676&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Cl%C3%A9ment+Da+Costa/32103676&quot;,
                    &quot;points&quot;: &quot;775.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 29,
                        &quot;licence&quot;: &quot;173585 E&quot;,
                        &quot;nom&quot;: &quot;DA COSTA&quot;,
                        &quot;prenom&quot;: &quot;CLEMENT&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;participant_name&quot;: &quot;Djino Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33642442&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Djino+Wagon/33642442&quot;,
                    &quot;points&quot;: &quot;485.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 114,
                        &quot;licence&quot;: &quot;179413 P&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;DJINO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 11,
                    &quot;participant_name&quot;: &quot;Ma&eacute; Sknoezeck&quot;,
                    &quot;participant_external_id&quot;: &quot;38360302&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Ma%C3%A9+Sknoezeck/38360302&quot;,
                    &quot;points&quot;: &quot;180.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 104,
                        &quot;licence&quot;: &quot;178088 Z&quot;,
                        &quot;nom&quot;: &quot;SKNOEZECK&quot;,
                        &quot;prenom&quot;: &quot;MAE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 27,
                &quot;name&quot;: &quot;HANDI-DEBOUT 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097509&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/HANDI-DEBOUT+25-26/67097509&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 39
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 27,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:25+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 16,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 2,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 6,
                    &quot;participant_name&quot;: &quot;mickael souverain&quot;,
                    &quot;participant_external_id&quot;: &quot;32035528&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/mickael+souverain/32035528&quot;,
                    &quot;points&quot;: &quot;345.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 107,
                        &quot;licence&quot;: &quot;178141 G&quot;,
                        &quot;nom&quot;: &quot;SOUVERAIN&quot;,
                        &quot;prenom&quot;: &quot;MICKAEL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 12,
                    &quot;participant_name&quot;: &quot;J&eacute;r&ocirc;me Jussy&quot;,
                    &quot;participant_external_id&quot;: &quot;33409729&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/J%C3%A9r%C3%B4me+Jussy/33409729&quot;,
                    &quot;points&quot;: &quot;165.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 54,
                        &quot;licence&quot;: &quot;156524 K&quot;,
                        &quot;nom&quot;: &quot;JUSSY&quot;,
                        &quot;prenom&quot;: &quot;JEROME&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 28,
                &quot;name&quot;: &quot;HANDI-FAUTEUIL 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;77825152&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/HANDI-FAUTEUIL+25-26/77825152&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 40
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 28,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:27+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 5,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 4,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;participant_name&quot;: &quot;Christophe FERASSE&quot;,
                    &quot;participant_external_id&quot;: &quot;33399133&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+FERASSE/33399133&quot;,
                    &quot;points&quot;: &quot;235.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 45,
                        &quot;licence&quot;: &quot;180354 M&quot;,
                        &quot;nom&quot;: &quot;FERASSE&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 2,
                    &quot;participant_name&quot;: &quot;Thomas RAINEAU&quot;,
                    &quot;participant_external_id&quot;: &quot;32452216&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Thomas+RAINEAU/32452216&quot;,
                    &quot;points&quot;: &quot;185.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 95,
                        &quot;licence&quot;: &quot;173909 G&quot;,
                        &quot;nom&quot;: &quot;RAINEAU&quot;,
                        &quot;prenom&quot;: &quot;THOMAS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;participant_name&quot;: &quot;Gregory Cadinot&quot;,
                    &quot;participant_external_id&quot;: &quot;30706906&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Gregory+Cadinot/30706906&quot;,
                    &quot;points&quot;: &quot;145.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 16,
                        &quot;licence&quot;: &quot;157681 S&quot;,
                        &quot;nom&quot;: &quot;CADINOT&quot;,
                        &quot;prenom&quot;: &quot;GREGORY&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 4,
                    &quot;participant_name&quot;: &quot;Tristan Pierrot&quot;,
                    &quot;participant_external_id&quot;: &quot;33690460&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tristan+Pierrot/33690460&quot;,
                    &quot;points&quot;: &quot;100.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 92,
                        &quot;licence&quot;: &quot;160792 Z&quot;,
                        &quot;nom&quot;: &quot;PIERROT&quot;,
                        &quot;prenom&quot;: &quot;TRISTAN&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 29,
                &quot;name&quot;: &quot;TRZ OUEST MIXTE 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;71389255&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/TRZ+OUEST+MIXTE+25-26/71389255&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 41
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 29,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:29+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 26,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 11,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 3,
                    &quot;participant_name&quot;: &quot;Djino Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33642442&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Djino+Wagon/33642442&quot;,
                    &quot;points&quot;: &quot;248.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 114,
                        &quot;licence&quot;: &quot;179413 P&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;DJINO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 10,
                    &quot;participant_name&quot;: &quot;Thomas RAINEAU&quot;,
                    &quot;participant_external_id&quot;: &quot;32452216&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Thomas+RAINEAU/32452216&quot;,
                    &quot;points&quot;: &quot;160.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 95,
                        &quot;licence&quot;: &quot;173909 G&quot;,
                        &quot;nom&quot;: &quot;RAINEAU&quot;,
                        &quot;prenom&quot;: &quot;THOMAS&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 13,
                    &quot;participant_name&quot;: &quot;Christophe FERASSE&quot;,
                    &quot;participant_external_id&quot;: &quot;33399133&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Christophe+FERASSE/33399133&quot;,
                    &quot;points&quot;: &quot;144.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 45,
                        &quot;licence&quot;: &quot;180354 M&quot;,
                        &quot;nom&quot;: &quot;FERASSE&quot;,
                        &quot;prenom&quot;: &quot;CHRISTOPHE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 14,
                    &quot;participant_name&quot;: &quot;Tito Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33642439&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Tito+Wagon/33642439&quot;,
                    &quot;points&quot;: &quot;124.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 116,
                        &quot;licence&quot;: &quot;178350 J&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;TITO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 15,
                    &quot;participant_name&quot;: &quot;Math&eacute;o H&eacute;risson&quot;,
                    &quot;participant_external_id&quot;: &quot;33301852&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Math%C3%A9o+H%C3%A9risson/33301852&quot;,
                    &quot;points&quot;: &quot;91.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 52,
                        &quot;licence&quot;: &quot;177885 D&quot;,
                        &quot;nom&quot;: &quot;HERISSON&quot;,
                        &quot;prenom&quot;: &quot;MATHEO&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 17,
                    &quot;participant_name&quot;: &quot;J&eacute;r&ocirc;me H&eacute;risson&quot;,
                    &quot;participant_external_id&quot;: &quot;49534204&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/J%C3%A9r%C3%B4me+H%C3%A9risson/49534204&quot;,
                    &quot;points&quot;: &quot;89.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 51,
                        &quot;licence&quot;: &quot;177932 E&quot;,
                        &quot;nom&quot;: &quot;HERISSON&quot;,
                        &quot;prenom&quot;: &quot;JEROME&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 21,
                    &quot;participant_name&quot;: &quot;Patrice MAILLET&quot;,
                    &quot;participant_external_id&quot;: &quot;31356370&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Patrice+MAILLET/31356370&quot;,
                    &quot;points&quot;: &quot;56.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 78,
                        &quot;licence&quot;: &quot;177470 C&quot;,
                        &quot;nom&quot;: &quot;MAILLET&quot;,
                        &quot;prenom&quot;: &quot;PATRICE&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 22,
                    &quot;participant_name&quot;: &quot;Vital DOMMERY&quot;,
                    &quot;participant_external_id&quot;: &quot;39073993&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Vital+DOMMERY/39073993&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 38,
                        &quot;licence&quot;: &quot;181485 R&quot;,
                        &quot;nom&quot;: &quot;DOMMERY&quot;,
                        &quot;prenom&quot;: &quot;VITAL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 23,
                    &quot;participant_name&quot;: &quot;mickael souverain&quot;,
                    &quot;participant_external_id&quot;: &quot;32035528&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/mickael+souverain/32035528&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 107,
                        &quot;licence&quot;: &quot;178141 G&quot;,
                        &quot;nom&quot;: &quot;SOUVERAIN&quot;,
                        &quot;prenom&quot;: &quot;MICKAEL&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 24,
                    &quot;participant_name&quot;: &quot;Gregory Cadinot&quot;,
                    &quot;participant_external_id&quot;: &quot;30706906&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Gregory+Cadinot/30706906&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 16,
                        &quot;licence&quot;: &quot;157681 S&quot;,
                        &quot;nom&quot;: &quot;CADINOT&quot;,
                        &quot;prenom&quot;: &quot;GREGORY&quot;
                    }
                },
                {
                    &quot;rank_position&quot;: 25,
                    &quot;participant_name&quot;: &quot;Piedade Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33417292&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Piedade+Wagon/33417292&quot;,
                    &quot;points&quot;: &quot;36.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 115,
                        &quot;licence&quot;: &quot;179294 K&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;PIEDADE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 30,
                &quot;name&quot;: &quot;TRZ OUEST FEMININ 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;71389252&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/ranking/TRZ+OUEST+FEMININ+25-26/71389252&quot;,
                &quot;source_type&quot;: &quot;ranking&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;team_category&quot;: null,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 42
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 30,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:31+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 3,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 2,
                    &quot;participant_name&quot;: &quot;Piedade Wagon&quot;,
                    &quot;participant_external_id&quot;: &quot;33417292&quot;,
                    &quot;participant_url&quot;: &quot;https://cuescore.com/player/Piedade+Wagon/33417292&quot;,
                    &quot;points&quot;: &quot;200.00&quot;,
                    &quot;played&quot;: null,
                    &quot;wins&quot;: null,
                    &quot;losses&quot;: null,
                    &quot;ties&quot;: null,
                    &quot;matching&quot;: {
                        &quot;method&quot;: &quot;exact_normalized&quot;,
                        &quot;confidence_score&quot;: 100,
                        &quot;is_confirmed&quot;: true
                    },
                    &quot;licencie&quot;: {
                        &quot;id&quot;: 115,
                        &quot;licence&quot;: &quot;179294 K&quot;,
                        &quot;nom&quot;: &quot;WAGON&quot;,
                        &quot;prenom&quot;: &quot;PIEDADE&quot;
                    }
                }
            ]
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 31,
                &quot;name&quot;: &quot;FFB - Blackball - Equipes - DN1 - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67490224&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN1+-+2025-2026/67490224&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DN1&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 43
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 31,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:36+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 12,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 12,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;team_name&quot;: &quot;VIROFLAY 1 DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29966809&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/VIROFLAY+1+DN1/29966809&quot;,
                    &quot;points&quot;: &quot;87.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 15,
                    &quot;losses&quot;: 1,
                    &quot;ties&quot;: 0,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 226,
                        &quot;frame_score&quot;: 132,
                        &quot;frame_losses&quot;: 94
                    }
                },
                {
                    &quot;rank_position&quot;: 2,
                    &quot;team_name&quot;: &quot;JOU&Eacute; L&Egrave;S TOURS 1 DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29967052&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOU%C3%89+L%C3%88S+TOURS+1+DN1/29967052&quot;,
                    &quot;points&quot;: &quot;76.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 13,
                    &quot;losses&quot;: 1,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 193,
                        &quot;frame_score&quot;: 66,
                        &quot;frame_losses&quot;: 127
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;team_name&quot;: &quot;LES HERBIERS DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29967067&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/LES+HERBIERS+DN1/29967067&quot;,
                    &quot;points&quot;: &quot;72.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 12,
                    &quot;losses&quot;: 3,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 189,
                        &quot;frame_score&quot;: 58,
                        &quot;frame_losses&quot;: 131
                    }
                },
                {
                    &quot;rank_position&quot;: 4,
                    &quot;team_name&quot;: &quot;JOU&Eacute; L&Egrave;S TOURS 2 DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29967073&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOU%C3%89+L%C3%88S+TOURS+2+DN1/29967073&quot;,
                    &quot;points&quot;: &quot;63.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 9,
                    &quot;losses&quot;: 6,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 168,
                        &quot;frame_score&quot;: 16,
                        &quot;frame_losses&quot;: 152
                    }
                },
                {
                    &quot;rank_position&quot;: 5,
                    &quot;team_name&quot;: &quot;VIC LE COMTE DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29969134&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/VIC+LE+COMTE+DN1/29969134&quot;,
                    &quot;points&quot;: &quot;57.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 8,
                    &quot;losses&quot;: 7,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 158,
                        &quot;frame_score&quot;: -4,
                        &quot;frame_losses&quot;: 162
                    }
                },
                {
                    &quot;rank_position&quot;: 6,
                    &quot;team_name&quot;: &quot;QUIMPER DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29967061&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/QUIMPER+DN1/29967061&quot;,
                    &quot;points&quot;: &quot;53.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 6,
                    &quot;losses&quot;: 6,
                    &quot;ties&quot;: 4,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 156,
                        &quot;frame_score&quot;: -8,
                        &quot;frame_losses&quot;: 164
                    }
                },
                {
                    &quot;rank_position&quot;: 7,
                    &quot;team_name&quot;: &quot;DUNKERQUE DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29968849&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/DUNKERQUE+DN1/29968849&quot;,
                    &quot;points&quot;: &quot;50.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 6,
                    &quot;losses&quot;: 9,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 150,
                        &quot;frame_score&quot;: -20,
                        &quot;frame_losses&quot;: 170
                    }
                },
                {
                    &quot;rank_position&quot;: 8,
                    &quot;team_name&quot;: &quot;AGEN DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29969137&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/AGEN+DN1/29969137&quot;,
                    &quot;points&quot;: &quot;47.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 6,
                    &quot;losses&quot;: 10,
                    &quot;ties&quot;: 0,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 142,
                        &quot;frame_score&quot;: -36,
                        &quot;frame_losses&quot;: 178
                    }
                },
                {
                    &quot;rank_position&quot;: 9,
                    &quot;team_name&quot;: &quot;VIROFLAY 2 DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29968945&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/VIROFLAY+2+DN1/29968945&quot;,
                    &quot;points&quot;: &quot;45.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 4,
                    &quot;losses&quot;: 10,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 143,
                        &quot;frame_score&quot;: -34,
                        &quot;frame_losses&quot;: 177
                    }
                },
                {
                    &quot;rank_position&quot;: 10,
                    &quot;team_name&quot;: &quot;ANTONY DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29970487&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ANTONY+DN1/29970487&quot;,
                    &quot;points&quot;: &quot;42.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 4,
                    &quot;losses&quot;: 10,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 129,
                        &quot;frame_score&quot;: -62,
                        &quot;frame_losses&quot;: 191
                    }
                },
                {
                    &quot;rank_position&quot;: 11,
                    &quot;team_name&quot;: &quot;SAINT NICOLAS DE PORT DN1&quot;,
                    &quot;team_external_id&quot;: &quot;29967076&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/SAINT+NICOLAS+DE+PORT+DN1/29967076&quot;,
                    &quot;points&quot;: &quot;38.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 2,
                    &quot;losses&quot;: 12,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 141,
                        &quot;frame_score&quot;: -38,
                        &quot;frame_losses&quot;: 179
                    }
                },
                {
                    &quot;rank_position&quot;: 12,
                    &quot;team_name&quot;: &quot;NOYAL PONTIVY  DN1&quot;,
                    &quot;team_external_id&quot;: &quot;35441404&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/NOYAL+PONTIVY++DN1/35441404&quot;,
                    &quot;points&quot;: &quot;32.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 1,
                    &quot;losses&quot;: 11,
                    &quot;ties&quot;: 4,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 125,
                        &quot;frame_score&quot;: -70,
                        &quot;frame_losses&quot;: 195
                    }
                }
            ],
            &quot;club_team_present&quot;: true
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 32,
                &quot;name&quot;: &quot;FFB - Blackball - Equipes - DN2 - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67490356&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN2+-+2025-2026/67490356&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DN2&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 44
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 32,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:40+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 12,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: [],
            &quot;club_team_present&quot;: false
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 33,
                &quot;name&quot;: &quot;FFB - Blackball - Equipes - DN3 - 2025-2026&quot;,
                &quot;cuescore_id&quot;: &quot;67490959&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/FFB+-+Blackball+-+Equipes+-+DN3+-+2025-2026/67490959&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DN3&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 45
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 33,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:45+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 16,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: [],
            &quot;club_team_present&quot;: false
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 34,
                &quot;name&quot;: &quot;BB_CVL &Eacute;QUIPE DR1 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097593&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/BB_CVL+&Eacute;QUIPE+DR1+25-26/67097593&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DR1&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 46
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 34,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:48+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 10,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 0,
            &quot;data&quot;: [],
            &quot;club_team_present&quot;: false
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 35,
                &quot;name&quot;: &quot;BB CVL &Eacute;QUIPE DR2 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097608&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/BB_CVL+&Eacute;QUIPE+DR2+25-26/67097608&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DR2&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 47
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 35,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:52+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 10,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 10,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;team_name&quot;: &quot;SAINT LAURENT NOUAN 1 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;31539304&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/SAINT+LAURENT+NOUAN+1+DR2/31539304&quot;,
                    &quot;points&quot;: &quot;78.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 12,
                    &quot;losses&quot;: 1,
                    &quot;ties&quot;: 3,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 175,
                        &quot;frame_score&quot;: 94,
                        &quot;frame_losses&quot;: 81
                    }
                },
                {
                    &quot;rank_position&quot;: 2,
                    &quot;team_name&quot;: &quot;BC ORLEANS 2 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;31531096&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/BC+ORLEANS+2+DR2/31531096&quot;,
                    &quot;points&quot;: &quot;73.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 12,
                    &quot;losses&quot;: 3,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 164,
                        &quot;frame_score&quot;: 72,
                        &quot;frame_losses&quot;: 92
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;team_name&quot;: &quot;LUCE 2 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;32864440&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/LUCE+2+DR2/32864440&quot;,
                    &quot;points&quot;: &quot;70.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 11,
                    &quot;losses&quot;: 3,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 153,
                        &quot;frame_score&quot;: 50,
                        &quot;frame_losses&quot;: 103
                    }
                },
                {
                    &quot;rank_position&quot;: 4,
                    &quot;team_name&quot;: &quot;ACADEMIE BLESOISE DE BILLARD 2 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;33204151&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ACADEMIE+BLESOISE+DE+BILLARD+2+DR2/33204151&quot;,
                    &quot;points&quot;: &quot;62.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 8,
                    &quot;losses&quot;: 6,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 143,
                        &quot;frame_score&quot;: 30,
                        &quot;frame_losses&quot;: 113
                    }
                },
                {
                    &quot;rank_position&quot;: 5,
                    &quot;team_name&quot;: &quot;JOUE LES TOURS 1 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;31579930&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOUE+LES+TOURS+1+DR2/31579930&quot;,
                    &quot;points&quot;: &quot;61.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 8,
                    &quot;losses&quot;: 5,
                    &quot;ties&quot;: 3,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 141,
                        &quot;frame_score&quot;: 26,
                        &quot;frame_losses&quot;: 115
                    }
                },
                {
                    &quot;rank_position&quot;: 6,
                    &quot;team_name&quot;: &quot;ROMO 5 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;49190521&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ROMO+5+DR2/49190521&quot;,
                    &quot;points&quot;: &quot;59.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 6,
                    &quot;losses&quot;: 4,
                    &quot;ties&quot;: 6,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 143,
                        &quot;frame_score&quot;: 30,
                        &quot;frame_losses&quot;: 113
                    }
                },
                {
                    &quot;rank_position&quot;: 7,
                    &quot;team_name&quot;: &quot;DREUX 7 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;32864434&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/DREUX+7+DR2/32864434&quot;,
                    &quot;points&quot;: &quot;51.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 5,
                    &quot;losses&quot;: 7,
                    &quot;ties&quot;: 4,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 123,
                        &quot;frame_score&quot;: -10,
                        &quot;frame_losses&quot;: 133
                    }
                },
                {
                    &quot;rank_position&quot;: 8,
                    &quot;team_name&quot;: &quot;ACADEMIE BLESOISE DE BILLARD 3 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;33204154&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ACADEMIE+BLESOISE+DE+BILLARD+3+DR2/33204154&quot;,
                    &quot;points&quot;: &quot;38.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 4,
                    &quot;losses&quot;: 10,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 91,
                        &quot;frame_score&quot;: -74,
                        &quot;frame_losses&quot;: 165
                    }
                },
                {
                    &quot;rank_position&quot;: 9,
                    &quot;team_name&quot;: &quot;DREUX 5 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;31530961&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/DREUX+5+DR2/31530961&quot;,
                    &quot;points&quot;: &quot;29.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 1,
                    &quot;losses&quot;: 14,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 76,
                        &quot;frame_score&quot;: -104,
                        &quot;frame_losses&quot;: 180
                    }
                },
                {
                    &quot;rank_position&quot;: 10,
                    &quot;team_name&quot;: &quot;BOURGES 1 DR2&quot;,
                    &quot;team_external_id&quot;: &quot;33204160&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/BOURGES+1+DR2/33204160&quot;,
                    &quot;points&quot;: &quot;27.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 1,
                    &quot;losses&quot;: 15,
                    &quot;ties&quot;: 0,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 71,
                        &quot;frame_score&quot;: -114,
                        &quot;frame_losses&quot;: 185
                    }
                }
            ],
            &quot;club_team_present&quot;: true
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 36,
                &quot;name&quot;: &quot;BB CVL &Eacute;QUIPE DR3 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;67097614&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/BB_CVL+&Eacute;QUIPE+DR3+25-26/67097614&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;regional&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DR3&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 48
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 36,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:55+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 10,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 10,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;team_name&quot;: &quot;ROMO 4 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31579927&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ROMO+4+DR3/31579927&quot;,
                    &quot;points&quot;: &quot;75.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 12,
                    &quot;losses&quot;: 3,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 164,
                        &quot;frame_score&quot;: 72,
                        &quot;frame_losses&quot;: 92
                    }
                },
                {
                    &quot;rank_position&quot;: 2,
                    &quot;team_name&quot;: &quot;BC ORLEANS 4 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31531102&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/BC+ORLEANS+4+DR3/31531102&quot;,
                    &quot;points&quot;: &quot;66.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 10,
                    &quot;losses&quot;: 3,
                    &quot;ties&quot;: 3,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 144,
                        &quot;frame_score&quot;: 32,
                        &quot;frame_losses&quot;: 112
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;team_name&quot;: &quot;LUCE 3 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;32864449&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/LUCE+3+DR3/32864449&quot;,
                    &quot;points&quot;: &quot;65.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 9,
                    &quot;losses&quot;: 4,
                    &quot;ties&quot;: 3,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 144,
                        &quot;frame_score&quot;: 32,
                        &quot;frame_losses&quot;: 112
                    }
                },
                {
                    &quot;rank_position&quot;: 4,
                    &quot;team_name&quot;: &quot;LENAY 2 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31579945&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/LENAY+2+DR3/31579945&quot;,
                    &quot;points&quot;: &quot;63.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 8,
                    &quot;losses&quot;: 4,
                    &quot;ties&quot;: 4,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 145,
                        &quot;frame_score&quot;: 34,
                        &quot;frame_losses&quot;: 111
                    }
                },
                {
                    &quot;rank_position&quot;: 5,
                    &quot;team_name&quot;: &quot;ISSOUDUN 2 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31530979&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ISSOUDUN+2+DR3/31530979&quot;,
                    &quot;points&quot;: &quot;61.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 8,
                    &quot;losses&quot;: 4,
                    &quot;ties&quot;: 4,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 137,
                        &quot;frame_score&quot;: 18,
                        &quot;frame_losses&quot;: 119
                    }
                },
                {
                    &quot;rank_position&quot;: 6,
                    &quot;team_name&quot;: &quot;CHATEAUROUX 1 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;33204178&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/CHATEAUROUX+1+DR3/33204178&quot;,
                    &quot;points&quot;: &quot;60.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 9,
                    &quot;losses&quot;: 5,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 133,
                        &quot;frame_score&quot;: 10,
                        &quot;frame_losses&quot;: 123
                    }
                },
                {
                    &quot;rank_position&quot;: 7,
                    &quot;team_name&quot;: &quot;JOUE LES TOURS 2 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31579933&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOUE+LES+TOURS+2+DR3/31579933&quot;,
                    &quot;points&quot;: &quot;50.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 4,
                    &quot;losses&quot;: 7,
                    &quot;ties&quot;: 5,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 132,
                        &quot;frame_score&quot;: 8,
                        &quot;frame_losses&quot;: 124
                    }
                },
                {
                    &quot;rank_position&quot;: 8,
                    &quot;team_name&quot;: &quot;BC ORLEANS 3 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31531099&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/BC+ORLEANS+3+DR3/31531099&quot;,
                    &quot;points&quot;: &quot;44.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 4,
                    &quot;losses&quot;: 10,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 113,
                        &quot;frame_score&quot;: -30,
                        &quot;frame_losses&quot;: 143
                    }
                },
                {
                    &quot;rank_position&quot;: 9,
                    &quot;team_name&quot;: &quot;JOUE LES TOURS 4 DR3&quot;,
                    &quot;team_external_id&quot;: &quot;33204163&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOUE+LES+TOURS+4+DR3/33204163&quot;,
                    &quot;points&quot;: &quot;34.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 2,
                    &quot;losses&quot;: 13,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 89,
                        &quot;frame_score&quot;: -78,
                        &quot;frame_losses&quot;: 167
                    }
                },
                {
                    &quot;rank_position&quot;: 10,
                    &quot;team_name&quot;: &quot;POLE ESPOIR DR3&quot;,
                    &quot;team_external_id&quot;: &quot;31579939&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/POLE+ESPOIR+DR3/31579939&quot;,
                    &quot;points&quot;: &quot;29.00&quot;,
                    &quot;played&quot;: 16,
                    &quot;wins&quot;: 1,
                    &quot;losses&quot;: 14,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 79,
                        &quot;frame_score&quot;: -98,
                        &quot;frame_losses&quot;: 177
                    }
                }
            ],
            &quot;club_team_present&quot;: true
        },
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 37,
                &quot;name&quot;: &quot;CVL &Eacute;QUIPE DR4 ZONE OUEST 25-26&quot;,
                &quot;cuescore_id&quot;: &quot;69856342&quot;,
                &quot;url&quot;: &quot;https://cuescore.com/tournament/CVL+&Eacute;QUIPE+DR4+ZONE+OUEST+25-26/69856342#match-69864133&quot;,
                &quot;source_type&quot;: &quot;tournament&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;d&eacute;partemental&quot;,
                &quot;ranking_type&quot;: &quot;team&quot;,
                &quot;team_category&quot;: &quot;DR4&quot;,
                &quot;season&quot;: &quot;2025-2026&quot;,
                &quot;is_active&quot;: true,
                &quot;sort_order&quot;: 49
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 37,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-22T18:28:58+02:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 7,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 7,
            &quot;data&quot;: [
                {
                    &quot;rank_position&quot;: 1,
                    &quot;team_name&quot;: &quot;BILLARDERS BLESOIS 1 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;31579906&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/BILLARDERS+BLESOIS+1+DR4/31579906&quot;,
                    &quot;points&quot;: &quot;60.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 10,
                    &quot;losses&quot;: 1,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 129,
                        &quot;frame_score&quot;: 74,
                        &quot;frame_losses&quot;: 55
                    }
                },
                {
                    &quot;rank_position&quot;: 2,
                    &quot;team_name&quot;: &quot;SAINT AMAND LONGPRE 1 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;33204148&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/SAINT+AMAND+LONGPRE+1+DR4/33204148&quot;,
                    &quot;points&quot;: &quot;54.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 8,
                    &quot;losses&quot;: 1,
                    &quot;ties&quot;: 3,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 124,
                        &quot;frame_score&quot;: 56,
                        &quot;frame_losses&quot;: 68
                    }
                },
                {
                    &quot;rank_position&quot;: 3,
                    &quot;team_name&quot;: &quot;CHINON 1 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;33204181&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/CHINON+1+DR4/33204181&quot;,
                    &quot;points&quot;: &quot;49.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 7,
                    &quot;losses&quot;: 4,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 117,
                        &quot;frame_score&quot;: 50,
                        &quot;frame_losses&quot;: 67
                    }
                },
                {
                    &quot;rank_position&quot;: 4,
                    &quot;team_name&quot;: &quot;ACADEMIE BLESOISE DE BILLARD 4 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;67014373&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/ACADEMIE+BLESOISE+DE+BILLARD+4+DR4/67014373&quot;,
                    &quot;points&quot;: &quot;49.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 7,
                    &quot;losses&quot;: 4,
                    &quot;ties&quot;: 1,
                    &quot;is_club_team&quot;: false,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 116,
                        &quot;frame_score&quot;: 40,
                        &quot;frame_losses&quot;: 76
                    }
                },
                {
                    &quot;rank_position&quot;: 5,
                    &quot;team_name&quot;: &quot;JOUE LES TOURS 6 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;31539301&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOUE+LES+TOURS+6+DR4/31539301&quot;,
                    &quot;points&quot;: &quot;38.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 4,
                    &quot;losses&quot;: 6,
                    &quot;ties&quot;: 2,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 93,
                        &quot;frame_score&quot;: -6,
                        &quot;frame_losses&quot;: 99
                    }
                },
                {
                    &quot;rank_position&quot;: 6,
                    &quot;team_name&quot;: &quot;JOUE LES TOURS 3 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;31579936&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOUE+LES+TOURS+3+DR4/31579936&quot;,
                    &quot;points&quot;: &quot;20.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 1,
                    &quot;losses&quot;: 11,
                    &quot;ties&quot;: 0,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 42,
                        &quot;frame_score&quot;: -99,
                        &quot;frame_losses&quot;: 141
                    }
                },
                {
                    &quot;rank_position&quot;: 7,
                    &quot;team_name&quot;: &quot;JOUE LES TOURS 5 DR4&quot;,
                    &quot;team_external_id&quot;: &quot;33204166&quot;,
                    &quot;team_url&quot;: &quot;https://cuescore.com/team/JOUE+LES+TOURS+5+DR4/33204166&quot;,
                    &quot;points&quot;: &quot;17.00&quot;,
                    &quot;played&quot;: 12,
                    &quot;wins&quot;: 1,
                    &quot;losses&quot;: 11,
                    &quot;ties&quot;: 0,
                    &quot;is_club_team&quot;: true,
                    &quot;additional_data&quot;: {
                        &quot;frame_wins&quot;: 26,
                        &quot;frame_score&quot;: -115,
                        &quot;frame_losses&quot;: 141
                    }
                }
            ],
            &quot;club_team_present&quot;: true
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 37,
        &quot;filters&quot;: {
            &quot;discipline&quot;: &quot;&quot;,
            &quot;scope&quot;: &quot;&quot;,
            &quot;ranking_type&quot;: &quot;&quot;
        }
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-cuescore-club" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-cuescore-club"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-cuescore-club"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-cuescore-club" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-cuescore-club">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-cuescore-club" data-method="GET"
      data-path="api/v1/cuescore/club"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-cuescore-club', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-cuescore-club"
                    onclick="tryItOut('GETapi-v1-cuescore-club');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-cuescore-club"
                    onclick="cancelTryOut('GETapi-v1-cuescore-club');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-cuescore-club"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/cuescore/club</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-cuescore-club"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-cuescore-club"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-cuescore-rankings--ranking_id-">Retourne les informations d’un classement avec son fetch actif.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-cuescore-rankings--ranking_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/rankings/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/rankings/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-cuescore-rankings--ranking_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 46
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;US Demi Finale N1 SUD 2025-2026&quot;,
        &quot;cuescore_id&quot;: &quot;79941976&quot;,
        &quot;url&quot;: &quot;https://cuescore.com/ranking/US_Demi+Finale+N1+SUD_2025-2026/79941976&quot;,
        &quot;source_type&quot;: &quot;ranking&quot;,
        &quot;discipline&quot;: &quot;americain&quot;,
        &quot;scope&quot;: &quot;national&quot;,
        &quot;ranking_type&quot;: &quot;individual&quot;,
        &quot;team_category&quot;: null,
        &quot;season&quot;: &quot;2025-2026&quot;,
        &quot;is_active&quot;: true,
        &quot;sort_order&quot;: 1,
        &quot;active_fetch&quot;: {
            &quot;id&quot;: 1,
            &quot;status&quot;: &quot;success&quot;,
            &quot;fetched_at&quot;: &quot;2026-04-22T18:27:14+02:00&quot;,
            &quot;http_status&quot;: 200,
            &quot;records_count&quot;: 0,
            &quot;is_active&quot;: true
        }
    },
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-cuescore-rankings--ranking_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-cuescore-rankings--ranking_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-cuescore-rankings--ranking_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-cuescore-rankings--ranking_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-cuescore-rankings--ranking_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-cuescore-rankings--ranking_id-" data-method="GET"
      data-path="api/v1/cuescore/rankings/{ranking_id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-cuescore-rankings--ranking_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-cuescore-rankings--ranking_id-"
                    onclick="tryItOut('GETapi-v1-cuescore-rankings--ranking_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-cuescore-rankings--ranking_id-"
                    onclick="cancelTryOut('GETapi-v1-cuescore-rankings--ranking_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-cuescore-rankings--ranking_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/cuescore/rankings/{ranking_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ranking_id"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the ranking. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-cuescore-rankings--ranking_id--club">Retourne les données club d’un classement (individuel ou équipe).</h2>

<p>
</p>

<p>Si aucun fetch actif n’est disponible, retourne une réponse vide.</p>

<span id="example-requests-GETapi-v1-cuescore-rankings--ranking_id--club">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/rankings/1/club" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/rankings/1/club"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-cuescore-rankings--ranking_id--club">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 45
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;count&quot;: 0,
        &quot;ranking_id&quot;: 1,
        &quot;ranking_name&quot;: &quot;US Demi Finale N1 SUD 2025-2026&quot;,
        &quot;fetch_id&quot;: 1,
        &quot;discipline&quot;: &quot;americain&quot;,
        &quot;scope&quot;: &quot;national&quot;,
        &quot;ranking_type&quot;: &quot;individual&quot;,
        &quot;team_category&quot;: null
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-cuescore-rankings--ranking_id--club" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-cuescore-rankings--ranking_id--club"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-cuescore-rankings--ranking_id--club"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-cuescore-rankings--ranking_id--club" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-cuescore-rankings--ranking_id--club">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-cuescore-rankings--ranking_id--club" data-method="GET"
      data-path="api/v1/cuescore/rankings/{ranking_id}/club"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-cuescore-rankings--ranking_id--club', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-cuescore-rankings--ranking_id--club"
                    onclick="tryItOut('GETapi-v1-cuescore-rankings--ranking_id--club');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-cuescore-rankings--ranking_id--club"
                    onclick="cancelTryOut('GETapi-v1-cuescore-rankings--ranking_id--club');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-cuescore-rankings--ranking_id--club"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/cuescore/rankings/{ranking_id}/club</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--club"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--club"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ranking_id"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--club"
               value="1"
               data-component="url">
    <br>
<p>The ID of the ranking. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-cuescore-rankings--ranking_id--teams">Retourne uniquement les classements équipes pour un ranking.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-cuescore-rankings--ranking_id--teams">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/rankings/1/teams" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/rankings/1/teams"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-cuescore-rankings--ranking_id--teams">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 44
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;count&quot;: 0,
        &quot;ranking_id&quot;: 1,
        &quot;ranking_name&quot;: &quot;US Demi Finale N1 SUD 2025-2026&quot;,
        &quot;fetch_id&quot;: 1,
        &quot;discipline&quot;: &quot;americain&quot;,
        &quot;scope&quot;: &quot;national&quot;,
        &quot;ranking_type&quot;: &quot;individual&quot;,
        &quot;team_category&quot;: null,
        &quot;club_team_present&quot;: false
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-cuescore-rankings--ranking_id--teams" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-cuescore-rankings--ranking_id--teams"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-cuescore-rankings--ranking_id--teams"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-cuescore-rankings--ranking_id--teams" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-cuescore-rankings--ranking_id--teams">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-cuescore-rankings--ranking_id--teams" data-method="GET"
      data-path="api/v1/cuescore/rankings/{ranking_id}/teams"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-cuescore-rankings--ranking_id--teams', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-cuescore-rankings--ranking_id--teams"
                    onclick="tryItOut('GETapi-v1-cuescore-rankings--ranking_id--teams');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-cuescore-rankings--ranking_id--teams"
                    onclick="cancelTryOut('GETapi-v1-cuescore-rankings--ranking_id--teams');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-cuescore-rankings--ranking_id--teams"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/cuescore/rankings/{ranking_id}/teams</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--teams"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ranking_id"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--teams"
               value="1"
               data-component="url">
    <br>
<p>The ID of the ranking. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-cuescore--discipline---scope---rankingType-">Vue agrégée des classements club via paramètres d’URL.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-cuescore--discipline---scope---rankingType-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/architecto/architecto/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/architecto/architecto/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-cuescore--discipline---scope---rankingType-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 43
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;count&quot;: 0,
        &quot;filters&quot;: {
            &quot;discipline&quot;: &quot;architecto&quot;,
            &quot;scope&quot;: &quot;architecto&quot;,
            &quot;ranking_type&quot;: &quot;architecto&quot;
        }
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-cuescore--discipline---scope---rankingType-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-cuescore--discipline---scope---rankingType-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-cuescore--discipline---scope---rankingType-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-cuescore--discipline---scope---rankingType-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-cuescore--discipline---scope---rankingType-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-cuescore--discipline---scope---rankingType-" data-method="GET"
      data-path="api/v1/cuescore/{discipline}/{scope}/{rankingType}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-cuescore--discipline---scope---rankingType-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-cuescore--discipline---scope---rankingType-"
                    onclick="tryItOut('GETapi-v1-cuescore--discipline---scope---rankingType-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-cuescore--discipline---scope---rankingType-"
                    onclick="cancelTryOut('GETapi-v1-cuescore--discipline---scope---rankingType-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-cuescore--discipline---scope---rankingType-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/cuescore/{discipline}/{scope}/{rankingType}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-cuescore--discipline---scope---rankingType-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-cuescore--discipline---scope---rankingType-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-cuescore--discipline---scope---rankingType-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>scope</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scope"                data-endpoint="GETapi-v1-cuescore--discipline---scope---rankingType-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>rankingType</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="rankingType"                data-endpoint="GETapi-v1-cuescore--discipline---scope---rankingType-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-documents">Retourne la liste de tous les documents.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-documents">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/documents" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/documents"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-documents">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 39
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;title&quot;: &quot;Code sportif blackball 2024-2025&quot;,
            &quot;file&quot;: &quot;pdf/blackball/code-sportif-blackball-2024-25.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/blackball/code-sportif-blackball-2024-25.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 2,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;title&quot;: &quot;Code arbitrage blackball 2023-2024&quot;,
            &quot;file&quot;: &quot;pdf/blackball/code-arbitrage-balckball-2023-2024.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/blackball/code-arbitrage-balckball-2023-2024.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 4,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;title&quot;: &quot;R&egrave;glement 4 billes U21 2024-2025&quot;,
            &quot;file&quot;: &quot;pdf/carambole/R&egrave;glement 4 BILLES U21-LBCVL 2024-25 V2.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/carambole/R&egrave;glement 4 BILLES U21-LBCVL 2024-25 V2.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 5,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;title&quot;: &quot;R&egrave;glement challenge Jacques Foulon 2023-2024&quot;,
            &quot;file&quot;: &quot;pdf/carambole/REGLEMENT CHALLENGE JACQUES FOULON Saison 2023-24.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/carambole/REGLEMENT CHALLENGE JACQUES FOULON Saison 2023-24.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 27,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;title&quot;: &quot;R&egrave;gle 8 ball internationale&quot;,
            &quot;file&quot;: &quot;pdf/blackball/13b2e8d3de2664ed618b9ced64c7cf56.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/blackball/13b2e8d3de2664ed618b9ced64c7cf56.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 28,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;discipline_id&quot;: 3,
            &quot;title&quot;: &quot;R&egrave;gles officielles du jeu de Snooker&quot;,
            &quot;file&quot;: &quot;pdf/snooker/1203d1c2704f1c4ef9c51c35413d2206.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/snooker/1203d1c2704f1c4ef9c51c35413d2206.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 30,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;title&quot;: &quot;R&egrave;gles billard am&eacute;ricain&quot;,
            &quot;file&quot;: &quot;pdf/americain/4a15ace67eb1db30fcfa50aedf0d03f8.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/americain/4a15ace67eb1db30fcfa50aedf0d03f8.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 31,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;title&quot;: &quot;Code sportif am&eacute;ricain 2024-2025&quot;,
            &quot;file&quot;: &quot;pdf/americain/9ec905cf4aa7fc40f64bfac56eccb5f9.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/americain/9ec905cf4aa7fc40f64bfac56eccb5f9.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 32,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;title&quot;: &quot;Code sportif blackball 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/blackball/a2659d0a8e4107b33fb3e76703915fe5.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/blackball/a2659d0a8e4107b33fb3e76703915fe5.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 33,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;title&quot;: &quot;Code sportif blackball 2025-2026 (R&eacute;sum&eacute; modifications)&quot;,
            &quot;file&quot;: &quot;pdf/blackball/43c8f3a54710da09707ff8bf2a153cb6.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/blackball/43c8f3a54710da09707ff8bf2a153cb6.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 34,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;title&quot;: &quot;Code sportif am&eacute;ricain 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/americain/d80b9b2c57baaa78247bce71a470d6a9.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/americain/d80b9b2c57baaa78247bce71a470d6a9.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 35,
            &quot;discipline&quot;: &quot;americain&quot;,
            &quot;discipline_id&quot;: 4,
            &quot;title&quot;: &quot;Code sportif am&eacute;ricain 2025-2026 (r&eacute;sum&eacute; modifications)&quot;,
            &quot;file&quot;: &quot;pdf/americain/32512862fd6ac04b2214be6be2966ad1.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/americain/32512862fd6ac04b2214be6be2966ad1.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 36,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;discipline_id&quot;: 3,
            &quot;title&quot;: &quot;Code sportif snooker 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/snooker/44c0823b76cf0b99f4350e1a9680720c.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/snooker/44c0823b76cf0b99f4350e1a9680720c.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 37,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;discipline_id&quot;: 3,
            &quot;title&quot;: &quot;Code sportif snooker 2025-2026 (r&eacute;sum&eacute; modifications)&quot;,
            &quot;file&quot;: &quot;pdf/snooker/2b9ca17c1a429b9e9f651080b06edd13.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/snooker/2b9ca17c1a429b9e9f651080b06edd13.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 38,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;title&quot;: &quot;R&eacute;formes 2025-2026 cat&eacute;gories individuelles et &eacute;quipes Carambole&quot;,
            &quot;file&quot;: &quot;pdf/carambole/a190f19f1998a25fe4e4a8d0963bfef4.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/carambole/a190f19f1998a25fe4e4a8d0963bfef4.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 40,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;title&quot;: &quot;Calendrier national carambole 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/carambole/58bbc07b276a609ac0f4183013f5f651.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/carambole/58bbc07b276a609ac0f4183013f5f651.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 43,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;title&quot;: &quot;Code Arbitrage Carambole 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/carambole/a52714de00981eb79a00e0649a3b9516.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/carambole/a52714de00981eb79a00e0649a3b9516.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 44,
            &quot;discipline&quot;: &quot;carambole&quot;,
            &quot;discipline_id&quot;: 2,
            &quot;title&quot;: &quot;Code sportif Carambole 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/carambole/9ce7448f02d51da05db06ccabfd51617.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/carambole/9ce7448f02d51da05db06ccabfd51617.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 45,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;title&quot;: &quot;Code sportif TRZ / DR4 Blackball r&eacute;gion Centre Val-de-Loirs 2025-2026&quot;,
            &quot;file&quot;: &quot;pdf/blackball/8b896c63dfbcf29cba2ca855e04b25c0.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/blackball/8b896c63dfbcf29cba2ca855e04b25c0.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 46,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;discipline_id&quot;: 3,
            &quot;title&quot;: &quot;2025-2026 LBCVL CODE SPORTIF SNOOKER&quot;,
            &quot;file&quot;: &quot;pdf/snooker/13540fa9011009a8dbec059cd0141e45.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/snooker/13540fa9011009a8dbec059cd0141e45.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        },
        {
            &quot;id&quot;: 47,
            &quot;discipline&quot;: &quot;snooker&quot;,
            &quot;discipline_id&quot;: 3,
            &quot;title&quot;: &quot;2025-2026 LBCVL CONVOCATIONS SNOOKER&quot;,
            &quot;file&quot;: &quot;pdf/snooker/5b10bc4745d79d3a3fdd6d4397a31ad5.pdf&quot;,
            &quot;file_url&quot;: &quot;http://localhost:8000/pdf/snooker/5b10bc4745d79d3a3fdd6d4397a31ad5.pdf&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 21
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-documents" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-documents"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-documents"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-documents" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-documents">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-documents" data-method="GET"
      data-path="api/v1/documents"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-documents', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-documents"
                    onclick="tryItOut('GETapi-v1-documents');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-documents"
                    onclick="cancelTryOut('GETapi-v1-documents');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-documents"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/documents</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-documents"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-documents"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-v1-documents--discipline-">Retourne les documents pour une discipline donnée.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-documents--discipline-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/documents/1" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/documents/1"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-documents--discipline-">
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 38
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: {
        &quot;code&quot;: &quot;discipline_not_found&quot;,
        &quot;message&quot;: &quot;Discipline not found&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-documents--discipline-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-documents--discipline-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-documents--discipline-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-documents--discipline-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-documents--discipline-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-documents--discipline-" data-method="GET"
      data-path="api/v1/documents/{discipline}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-documents--discipline-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-documents--discipline-"
                    onclick="tryItOut('GETapi-v1-documents--discipline-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-documents--discipline-"
                    onclick="cancelTryOut('GETapi-v1-documents--discipline-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-documents--discipline-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/documents/{discipline}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-documents--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-documents--discipline-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="discipline"                data-endpoint="GETapi-v1-documents--discipline-"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-documents--discipline---id-">Retourne un document spécifique pour une discipline.</h2>

<p>
</p>

<p>Si le document n'existe pas, retourne une erreur 404.</p>

<span id="example-requests-GETapi-v1-documents--discipline---id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/documents/1/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/documents/1/architecto"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-documents--discipline---id-">
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 37
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-documents--discipline---id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-documents--discipline---id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-documents--discipline---id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-documents--discipline---id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-documents--discipline---id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-documents--discipline---id-" data-method="GET"
      data-path="api/v1/documents/{discipline}/{id}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-documents--discipline---id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-documents--discipline---id-"
                    onclick="tryItOut('GETapi-v1-documents--discipline---id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-documents--discipline---id-"
                    onclick="cancelTryOut('GETapi-v1-documents--discipline---id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-documents--discipline---id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/documents/{discipline}/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-documents--discipline---id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-documents--discipline---id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="discipline"                data-endpoint="GETapi-v1-documents--discipline---id-"
               value="1"
               data-component="url">
    <br>
<p>Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="id"                data-endpoint="GETapi-v1-documents--discipline---id-"
               value="architecto"
               data-component="url">
    <br>
<p>The ID of the {discipline}. Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-GETapi-v1-contact">Retourne la liste des contacts disponibles.</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-contact">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/contact" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/contact"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-contact">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 36
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;message&quot;: &quot;Une question, une demande, ou simplement envie d&#039;&eacute;changer ?\r\nLe Billard Club de Jou&eacute;-l&egrave;s-Tours est &agrave; votre &eacute;coute !\r\nQue vous souhaitiez en savoir plus sur nos activit&eacute;s, assister &agrave; un &eacute;v&eacute;nement, d&eacute;couvrir le billard ou simplement prendre contact, n&#039;h&eacute;sitez pas &agrave; nous &eacute;crire via ce formulaire.\r\nNous vous r&eacute;pondrons avec plaisir !&quot;,
            &quot;email&quot;: &quot;contact@bcj.fr&quot;,
            &quot;telephone&quot;: &quot;06 25 13 35 66&quot;,
            &quot;created_at&quot;: null,
            &quot;updated_at&quot;: null
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 1
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-contact" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-contact"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-contact"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-contact" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-contact">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-contact" data-method="GET"
      data-path="api/v1/contact"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-contact', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-contact"
                    onclick="tryItOut('GETapi-v1-contact');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-contact"
                    onclick="cancelTryOut('GETapi-v1-contact');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-contact"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/contact</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-contact"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-contact"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
