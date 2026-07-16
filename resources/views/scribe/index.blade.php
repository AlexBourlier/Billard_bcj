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
                    <ul id="tocify-header-articles" class="tocify-header">
                <li class="tocify-item level-1" data-unique="articles">
                    <a href="#articles">Articles</a>
                </li>
                                    <ul id="tocify-subheader-articles" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts">
                                <a href="#articles-GETapi-v1-posts">Liste des articles</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts-favoris">
                                <a href="#articles-GETapi-v1-posts-favoris">Articles favoris</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts-discipline--discipline-">
                                <a href="#articles-GETapi-v1-posts-discipline--discipline-">Articles par discipline</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts-slug--slug-">
                                <a href="#articles-GETapi-v1-posts-slug--slug-">Article par slug</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts-decade--year-">
                                <a href="#articles-GETapi-v1-posts-decade--year-">Articles par décennie</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts-year--year-">
                                <a href="#articles-GETapi-v1-posts-year--year-">Articles par année</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="articles-GETapi-v1-posts--id-">
                                <a href="#articles-GETapi-v1-posts--id-">Détail d’un article</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-calendriers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="calendriers">
                    <a href="#calendriers">Calendriers</a>
                </li>
                                    <ul id="tocify-subheader-calendriers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="calendriers-GETapi-v1-calendrier">
                                <a href="#calendriers-GETapi-v1-calendrier">Liste des calendriers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="calendriers-GETapi-v1-calendrier--discipline-">
                                <a href="#calendriers-GETapi-v1-calendrier--discipline-">Calendriers par discipline</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="calendriers-GETapi-v1-calendrier--discipline---scope-">
                                <a href="#calendriers-GETapi-v1-calendrier--discipline---scope-">Calendrier par discipline et scope</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-classements" class="tocify-header">
                <li class="tocify-item level-1" data-unique="classements">
                    <a href="#classements">Classements</a>
                </li>
                                    <ul id="tocify-subheader-classements" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="classements-GETapi-v1-disciplines-carambole-classement">
                                <a href="#classements-GETapi-v1-disciplines-carambole-classement">Classements carambole (PDF)</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-cuescore" class="tocify-header">
                <li class="tocify-item level-1" data-unique="cuescore">
                    <a href="#cuescore">CueScore</a>
                </li>
                                    <ul id="tocify-subheader-cuescore" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="cuescore-GETapi-v1-cuescore-rankings">
                                <a href="#cuescore-GETapi-v1-cuescore-rankings">Liste des classements CueScore</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cuescore-GETapi-v1-cuescore-club">
                                <a href="#cuescore-GETapi-v1-cuescore-club">Vue club des classements CueScore</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cuescore-GETapi-v1-cuescore-rankings--ranking_id-">
                                <a href="#cuescore-GETapi-v1-cuescore-rankings--ranking_id-">Détail d’un classement CueScore</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cuescore-GETapi-v1-cuescore-rankings--ranking_id--club">
                                <a href="#cuescore-GETapi-v1-cuescore-rankings--ranking_id--club">Données club d’un classement</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cuescore-GETapi-v1-cuescore-rankings--ranking_id--teams">
                                <a href="#cuescore-GETapi-v1-cuescore-rankings--ranking_id--teams">Données équipes d’un classement</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="cuescore-GETapi-v1-cuescore--discipline---scope---rankingType-">
                                <a href="#cuescore-GETapi-v1-cuescore--discipline---scope---rankingType-">Vue club par discipline, scope et type</a>
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
                    <ul id="tocify-header-documents" class="tocify-header">
                <li class="tocify-item level-1" data-unique="documents">
                    <a href="#documents">Documents</a>
                </li>
                                    <ul id="tocify-subheader-documents" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="documents-GETapi-v1-documents">
                                <a href="#documents-GETapi-v1-documents">Liste des documents</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="documents-GETapi-v1-documents--discipline-">
                                <a href="#documents-GETapi-v1-documents--discipline-">Documents par discipline</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="documents-GETapi-v1-documents--discipline---id-">
                                <a href="#documents-GETapi-v1-documents--discipline---id-">Détail d’un document</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-v1-posts-period--period-">
                                <a href="#endpoints-GETapi-v1-posts-period--period-">GET api/v1/posts/period/{period}</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-licencies" class="tocify-header">
                <li class="tocify-item level-1" data-unique="licencies">
                    <a href="#licencies">Licenciés</a>
                </li>
                                    <ul id="tocify-subheader-licencies" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="licencies-GETapi-v1-licencies">
                                <a href="#licencies-GETapi-v1-licencies">Retourne la liste complète des licenciés.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="licencies-GETapi-v1-licencies-search--name-">
                                <a href="#licencies-GETapi-v1-licencies-search--name-">Recherche des licenciés par nom, prénom ou numéro de licence.</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-public" class="tocify-header">
                <li class="tocify-item level-1" data-unique="public">
                    <a href="#public">Public</a>
                </li>
                                    <ul id="tocify-subheader-public" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="public-GETapi-v1-public-site">
                                <a href="#public-GETapi-v1-public-site">Informations globales du site</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-v1-public-home">
                                <a href="#public-GETapi-v1-public-home">Données de la page d'accueil</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-v1-partenaires">
                                <a href="#public-GETapi-v1-partenaires">Liste des partenaires</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-v1-contact">
                                <a href="#public-GETapi-v1-contact">Liste des contacts</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-POSTapi-v1-contact">
                                <a href="#public-POSTapi-v1-contact">Envoie un message de contact.</a>
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
        <li>Last updated: July 16, 2026</li>
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

        <h1 id="articles">Articles</h1>

    

                                <h2 id="articles-GETapi-v1-posts">Liste des articles</h2>

<p>
</p>

<p>Retourne la liste paginée des articles, triés du plus récent au plus ancien.</p>

<span id="example-requests-GETapi-v1-posts">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts?per_page=16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts"
);

const params = {
    "per_page": "16",
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

<span id="example-responses-GETapi-v1-posts">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;count&quot;: 25,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 3,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 10,
        &quot;total&quot;: 25
    },
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://127.0.0.1:8000/api/v1/posts?page=1&quot;,
        &quot;last&quot;: &quot;http://127.0.0.1:8000/api/v1/posts?page=3&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://127.0.0.1:8000/api/v1/posts?page=2&quot;
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-posts"
               value="16"
               data-component="query">
    <br>
<p>Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10 Example: <code>16</code></p>
            </div>
                </form>

                    <h2 id="articles-GETapi-v1-posts-favoris">Articles favoris</h2>

<p>
</p>

<p>Retourne les articles marqués comme favoris.</p>

<span id="example-requests-GETapi-v1-posts-favoris">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/favoris?per_page=16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/favoris"
);

const params = {
    "per_page": "16",
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

<span id="example-responses-GETapi-v1-posts-favoris">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;favoris&quot;: true,
        &quot;count&quot;: 3,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 1,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 3,
        &quot;total&quot;: 3
    },
    &quot;links&quot;: [],
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-posts-favoris"
               value="16"
               data-component="query">
    <br>
<p>Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10 Example: <code>16</code></p>
            </div>
                </form>

                    <h2 id="articles-GETapi-v1-posts-discipline--discipline-">Articles par discipline</h2>

<p>
</p>

<p>Retourne la liste paginée des articles associés à une discipline.</p>

<span id="example-requests-GETapi-v1-posts-discipline--discipline-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/discipline/architecto?per_page=16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/discipline/architecto"
);

const params = {
    "per_page": "16",
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

<span id="example-responses-GETapi-v1-posts-discipline--discipline-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;count&quot;: 12,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 2,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 10,
        &quot;total&quot;: 12
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
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-posts-discipline--discipline-"
               value="16"
               data-component="query">
    <br>
<p>Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10 Example: <code>16</code></p>
            </div>
                </form>

                    <h2 id="articles-GETapi-v1-posts-slug--slug-">Article par slug</h2>

<p>
</p>

<p>Retourne un article à partir de son slug.</p>

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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Titre de l&#039;article&quot;,
        &quot;slug&quot;: &quot;titre-de-l-article&quot;,
        &quot;excerpt&quot;: &quot;R&eacute;sum&eacute; de l&#039;article...&quot;,
        &quot;content&quot;: &quot;&lt;p&gt;Contenu HTML de l&#039;article&lt;/p&gt;&quot;,
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;discipline_id&quot;: 1,
        &quot;year&quot;: 2026,
        &quot;favoris&quot;: false,
        &quot;image&quot;: null,
        &quot;image_url&quot;: null,
        &quot;video&quot;: null,
        &quot;created_at&quot;: &quot;2026-02-02T08:11:13.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-02-02T08:31:49.000000Z&quot;
    },
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: null
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
<p>Slug de l’article. Exemple : titre-de-l-article Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="articles-GETapi-v1-posts-decade--year-">Articles par décennie</h2>

<p>
</p>

<p>Retourne les articles appartenant à la décennie calculée depuis l’année fournie.</p>
<p>Exemple : 2023 retourne les articles de 2020 à 2029.</p>

<span id="example-requests-GETapi-v1-posts-decade--year-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/decade/16?per_page=16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/decade/16"
);

const params = {
    "per_page": "16",
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

<span id="example-responses-GETapi-v1-posts-decade--year-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;decade&quot;: &quot;2020-2029&quot;,
        &quot;start_year&quot;: 2020,
        &quot;end_year&quot;: 2029,
        &quot;count&quot;: 8,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 1,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 8,
        &quot;total&quot;: 8
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-v1-posts-decade--year-"
               value="16"
               data-component="url">
    <br>
<p>Année utilisée pour calculer la décennie. Exemple : 2023 Example: <code>16</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-posts-decade--year-"
               value="16"
               data-component="query">
    <br>
<p>Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10 Example: <code>16</code></p>
            </div>
                </form>

                    <h2 id="articles-GETapi-v1-posts-year--year-">Articles par année</h2>

<p>
</p>

<p>Retourne les articles associés à une année donnée.</p>

<span id="example-requests-GETapi-v1-posts-year--year-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/year/16?per_page=16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/year/16"
);

const params = {
    "per_page": "16",
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

<span id="example-responses-GETapi-v1-posts-year--year-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [],
    &quot;meta&quot;: {
        &quot;year&quot;: 2026,
        &quot;count&quot;: 5,
        &quot;current_page&quot;: 1,
        &quot;last_page&quot;: 1,
        &quot;per_page&quot;: 10,
        &quot;from&quot;: 1,
        &quot;to&quot;: 5,
        &quot;total&quot;: 5
    },
    &quot;links&quot;: [],
    &quot;error&quot;: null
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="year"                data-endpoint="GETapi-v1-posts-year--year-"
               value="16"
               data-component="url">
    <br>
<p>Année des articles. Exemple : 2026 Example: <code>16</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-posts-year--year-"
               value="16"
               data-component="query">
    <br>
<p>Nombre d’articles par page. Min: 1. Max: 100. Default: 10. Exemple : 10 Example: <code>16</code></p>
            </div>
                </form>

                    <h2 id="articles-GETapi-v1-posts--id-">Détail d’un article</h2>

<p>
</p>

<p>Retourne un article à partir de son identifiant.</p>

<span id="example-requests-GETapi-v1-posts--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/16"
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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;Titre de l&#039;article&quot;,
        &quot;slug&quot;: &quot;titre-de-l-article&quot;,
        &quot;excerpt&quot;: &quot;R&eacute;sum&eacute; de l&#039;article...&quot;,
        &quot;content&quot;: &quot;&lt;p&gt;Contenu HTML de l&#039;article&lt;/p&gt;&quot;,
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;discipline_id&quot;: 1,
        &quot;year&quot;: 2026,
        &quot;favoris&quot;: true,
        &quot;image&quot;: null,
        &quot;image_url&quot;: null,
        &quot;video&quot;: null,
        &quot;created_at&quot;: &quot;2026-02-02T08:11:13.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2026-02-02T08:31:49.000000Z&quot;
    },
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: null
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
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-posts--id-"
               value="16"
               data-component="url">
    <br>
<p>Identifiant de l’article. Exemple : 1 Example: <code>16</code></p>
            </div>
                    </form>

                <h1 id="calendriers">Calendriers</h1>

    

                                <h2 id="calendriers-GETapi-v1-calendrier">Liste des calendriers</h2>

<p>
</p>

<p>Retourne la liste de tous les calendriers actifs.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Calendrier national Blackball&quot;,
            &quot;slug&quot;: &quot;blackball-national&quot;,
            &quot;source_type&quot;: &quot;ffb&quot;,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-01-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-02T10:00:00.000000Z&quot;
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

                    <h2 id="calendriers-GETapi-v1-calendrier--discipline-">Calendriers par discipline</h2>

<p>
</p>

<p>Retourne les calendriers actifs associés à une discipline donnée.</p>

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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Calendrier national Blackball&quot;,
            &quot;slug&quot;: &quot;blackball-national&quot;,
            &quot;source_type&quot;: &quot;ffb&quot;,
            &quot;is_active&quot;: true,
            &quot;events_count&quot;: 12,
            &quot;created_at&quot;: &quot;2025-01-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-02T10:00:00.000000Z&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;count&quot;: 1
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
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="calendriers-GETapi-v1-calendrier--discipline---scope-">Calendrier par discipline et scope</h2>

<p>
</p>

<p>Retourne un calendrier spécifique avec la liste de ses événements et leurs liens.</p>

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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;calendar&quot;: {
            &quot;id&quot;: 1,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;name&quot;: &quot;Calendrier national Blackball&quot;,
            &quot;slug&quot;: &quot;blackball-national&quot;,
            &quot;source_type&quot;: &quot;ffb&quot;,
            &quot;is_active&quot;: true,
            &quot;created_at&quot;: &quot;2025-01-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-02T10:00:00.000000Z&quot;
        },
        &quot;events&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;calendar_id&quot;: 1,
                &quot;external_id&quot;: &quot;event-123&quot;,
                &quot;date_debut&quot;: &quot;2026-01-10T09:00:00.000000Z&quot;,
                &quot;date_fin&quot;: &quot;2026-01-10T18:00:00.000000Z&quot;,
                &quot;date_limite&quot;: &quot;2026-01-05T23:59:00.000000Z&quot;,
                &quot;titre&quot;: &quot;Tournoi national&quot;,
                &quot;lieu&quot;: &quot;Jou&eacute;-l&egrave;s-Tours&quot;,
                &quot;club&quot;: &quot;BCJ37&quot;,
                &quot;url&quot;: &quot;https://example.com/event&quot;,
                &quot;status&quot;: &quot;published&quot;,
                &quot;links&quot;: [
                    {
                        &quot;id&quot;: 1,
                        &quot;category&quot;: &quot;inscription&quot;,
                        &quot;label&quot;: &quot;S&#039;inscrire&quot;,
                        &quot;url&quot;: &quot;https://example.com/register&quot;,
                        &quot;sort_order&quot;: 1
                    }
                ]
            }
        ]
    },
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;scope&quot;: &quot;national&quot;,
        &quot;count&quot;: 1
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
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
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
<p>Scope du calendrier. Exemple : national Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="classements">Classements</h1>

    

                                <h2 id="classements-GETapi-v1-disciplines-carambole-classement">Classements carambole (PDF)</h2>

<p>
</p>

<p>Liste les fichiers PDF de classement carambole mis a disposition.</p>

<span id="example-requests-GETapi-v1-disciplines-carambole-classement">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/disciplines/carambole/classement" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/disciplines/carambole/classement"
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

<span id="example-responses-GETapi-v1-disciplines-carambole-classement">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;name&quot;: &quot;3 bandes&quot;,
            &quot;filename&quot;: &quot;3 bandes.pdf&quot;,
            &quot;url&quot;: &quot;https://bcj37.fr/ftp/3 bandes.pdf&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 1
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
    &quot;data&quot;: [],
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: &quot;Dossier ftp introuvable&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-disciplines-carambole-classement" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-disciplines-carambole-classement"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-disciplines-carambole-classement"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-disciplines-carambole-classement" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-disciplines-carambole-classement">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-disciplines-carambole-classement" data-method="GET"
      data-path="api/v1/disciplines/carambole/classement"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-disciplines-carambole-classement', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-disciplines-carambole-classement"
                    onclick="tryItOut('GETapi-v1-disciplines-carambole-classement');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-disciplines-carambole-classement"
                    onclick="cancelTryOut('GETapi-v1-disciplines-carambole-classement');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-disciplines-carambole-classement"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/disciplines/carambole/classement</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-disciplines-carambole-classement"
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
                              name="Accept"                data-endpoint="GETapi-v1-disciplines-carambole-classement"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="cuescore">CueScore</h1>

    

                                <h2 id="cuescore-GETapi-v1-cuescore-rankings">Liste des classements CueScore</h2>

<p>
</p>

<p>Retourne les classements CueScore disponibles avec filtres optionnels.</p>

<span id="example-requests-GETapi-v1-cuescore-rankings">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/rankings?discipline=architecto&amp;scope=architecto&amp;ranking_type=architecto&amp;team_category=architecto&amp;is_active=" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/rankings"
);

const params = {
    "discipline": "architecto",
    "scope": "architecto",
    "ranking_type": "architecto",
    "team_category": "architecto",
    "is_active": "0",
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

<span id="example-responses-GETapi-v1-cuescore-rankings">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;FFB - Blackball - TN - Master&quot;,
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-cuescore-rankings"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>scope</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scope"                data-endpoint="GETapi-v1-cuescore-rankings"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par scope. Exemple : national Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ranking_type"                data-endpoint="GETapi-v1-cuescore-rankings"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par type de classement. Exemple : individual Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>team_category</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="team_category"                data-endpoint="GETapi-v1-cuescore-rankings"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par catégorie équipe. Exemple : DN1 Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="GETapi-v1-cuescore-rankings" style="display: none">
            <input type="radio" name="is_active"
                   value="1"
                   data-endpoint="GETapi-v1-cuescore-rankings"
                   data-component="query"             >
            <code>true</code>
        </label>
        <label data-endpoint="GETapi-v1-cuescore-rankings" style="display: none">
            <input type="radio" name="is_active"
                   value="0"
                   data-endpoint="GETapi-v1-cuescore-rankings"
                   data-component="query"             >
            <code>false</code>
        </label>
    <br>
<p>Filtrer les classements actifs/inactifs. Exemple : true Example: <code>false</code></p>
            </div>
                </form>

                    <h2 id="cuescore-GETapi-v1-cuescore-club">Vue club des classements CueScore</h2>

<p>
</p>

<p>Retourne une vue agrégée des classements club selon des filtres optionnels.</p>

<span id="example-requests-GETapi-v1-cuescore-club">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/cuescore/club?discipline=architecto&amp;scope=architecto&amp;ranking_type=architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/cuescore/club"
);

const params = {
    "discipline": "architecto",
    "scope": "architecto",
    "ranking_type": "architecto",
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

<span id="example-responses-GETapi-v1-cuescore-club">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Master&quot;,
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
            &quot;fetch&quot;: {
                &quot;id&quot;: 10,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-25T10:00:00+00:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 120,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: []
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 1,
        &quot;filters&quot;: {
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;
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
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>discipline</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-cuescore-club"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>scope</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scope"                data-endpoint="GETapi-v1-cuescore-club"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par scope. Exemple : national Example: <code>architecto</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="ranking_type"                data-endpoint="GETapi-v1-cuescore-club"
               value="architecto"
               data-component="query">
    <br>
<p>Filtrer par type de classement. Exemple : individual Example: <code>architecto</code></p>
            </div>
                </form>

                    <h2 id="cuescore-GETapi-v1-cuescore-rankings--ranking_id-">Détail d’un classement CueScore</h2>

<p>
</p>

<p>Retourne les informations d’un classement CueScore avec son fetch actif.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;FFB - Blackball - TN - Master&quot;,
        &quot;cuescore_id&quot;: &quot;123456&quot;,
        &quot;url&quot;: &quot;https://cuescore.com/ranking/example&quot;,
        &quot;source_type&quot;: &quot;ranking&quot;,
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;scope&quot;: &quot;national&quot;,
        &quot;ranking_type&quot;: &quot;individual&quot;,
        &quot;team_category&quot;: null,
        &quot;season&quot;: &quot;2025-2026&quot;,
        &quot;is_active&quot;: true,
        &quot;sort_order&quot;: 1,
        &quot;active_fetch&quot;: {
            &quot;id&quot;: 10,
            &quot;status&quot;: &quot;success&quot;,
            &quot;fetched_at&quot;: &quot;2026-04-25T10:00:00+00:00&quot;,
            &quot;http_status&quot;: 200,
            &quot;records_count&quot;: 120,
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
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ranking"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id-"
               value="16"
               data-component="url">
    <br>
<p>Identifiant du classement CueScore local. Exemple : 1 Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="cuescore-GETapi-v1-cuescore-rankings--ranking_id--club">Données club d’un classement</h2>

<p>
</p>

<p>Retourne les données du club pour un classement CueScore.</p>
<p>Pour un classement individuel, seules les entrées correspondant à des licenciés confirmés sont retournées.
Pour un classement équipe, les équipes sont retournées si au moins une équipe du club est détectée.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
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
    &quot;meta&quot;: {
        &quot;count&quot;: 1,
        &quot;ranking_id&quot;: 1,
        &quot;ranking_name&quot;: &quot;FFB - Blackball - TN - Master&quot;,
        &quot;fetch_id&quot;: 10,
        &quot;discipline&quot;: &quot;blackball&quot;,
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
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ranking"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--club"
               value="16"
               data-component="url">
    <br>
<p>Identifiant du classement CueScore local. Exemple : 1 Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="cuescore-GETapi-v1-cuescore-rankings--ranking_id--teams">Données équipes d’un classement</h2>

<p>
</p>

<p>Retourne les données équipes d’un classement CueScore.</p>
<p>Les équipes appartenant au club sont identifiées via les règles de matching club.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;rank_position&quot;: 1,
            &quot;team_name&quot;: &quot;JOU&Eacute; L&Egrave;S TOURS 1 DN1&quot;,
            &quot;team_external_id&quot;: &quot;123456&quot;,
            &quot;team_url&quot;: &quot;https://cuescore.com/team/example&quot;,
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
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 1,
        &quot;ranking_id&quot;: 1,
        &quot;ranking_name&quot;: &quot;FFB - Blackball - Equipes - DN1&quot;,
        &quot;fetch_id&quot;: 10,
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;scope&quot;: &quot;national&quot;,
        &quot;ranking_type&quot;: &quot;team&quot;,
        &quot;team_category&quot;: &quot;DN1&quot;,
        &quot;club_team_present&quot;: true
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
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>ranking</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="ranking"                data-endpoint="GETapi-v1-cuescore-rankings--ranking_id--teams"
               value="16"
               data-component="url">
    <br>
<p>Identifiant du classement CueScore local. Exemple : 1 Example: <code>16</code></p>
            </div>
                    </form>

                    <h2 id="cuescore-GETapi-v1-cuescore--discipline---scope---rankingType-">Vue club par discipline, scope et type</h2>

<p>
</p>

<p>Retourne une vue agrégée des classements club à partir des paramètres d’URL.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;ranking&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;FFB - Blackball - TN - Master&quot;,
                &quot;discipline&quot;: &quot;blackball&quot;,
                &quot;scope&quot;: &quot;national&quot;,
                &quot;ranking_type&quot;: &quot;individual&quot;,
                &quot;is_active&quot;: true
            },
            &quot;fetch&quot;: {
                &quot;id&quot;: 10,
                &quot;status&quot;: &quot;success&quot;,
                &quot;fetched_at&quot;: &quot;2026-04-25T10:00:00+00:00&quot;,
                &quot;http_status&quot;: 200,
                &quot;records_count&quot;: 120,
                &quot;is_active&quot;: true
            },
            &quot;count&quot;: 1,
            &quot;data&quot;: []
        }
    ],
    &quot;meta&quot;: {
        &quot;count&quot;: 1,
        &quot;filters&quot;: {
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;scope&quot;: &quot;national&quot;,
            &quot;ranking_type&quot;: &quot;individual&quot;
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
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
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
<p>Scope du classement. Exemple : national Example: <code>architecto</code></p>
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
<p>Type du classement. Exemple : individual Example: <code>architecto</code></p>
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

                <h1 id="documents">Documents</h1>

    

                                <h2 id="documents-GETapi-v1-documents">Liste des documents</h2>

<p>
</p>

<p>Retourne la liste de tous les documents publics.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;R&egrave;glement int&eacute;rieur&quot;,
            &quot;file&quot;: &quot;documents/reglement.pdf&quot;,
            &quot;file_url&quot;: &quot;https://example.com/documents/reglement.pdf&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;created_at&quot;: &quot;2025-01-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-02T10:00:00.000000Z&quot;
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

                    <h2 id="documents-GETapi-v1-documents--discipline-">Documents par discipline</h2>

<p>
</p>

<p>Retourne les documents associés à une discipline donnée.</p>

<span id="example-requests-GETapi-v1-documents--discipline-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/documents/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/documents/architecto"
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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;R&egrave;glement int&eacute;rieur&quot;,
            &quot;file&quot;: &quot;documents/reglement.pdf&quot;,
            &quot;file_url&quot;: &quot;https://example.com/documents/reglement.pdf&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;created_at&quot;: &quot;2025-01-01T10:00:00.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-01-02T10:00:00.000000Z&quot;
        }
    ],
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;count&quot;: 1
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
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-documents--discipline-"
               value="architecto"
               data-component="url">
    <br>
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="documents-GETapi-v1-documents--discipline---id-">Détail d’un document</h2>

<p>
</p>

<p>Retourne un document spécifique pour une discipline donnée.</p>

<span id="example-requests-GETapi-v1-documents--discipline---id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/documents/architecto/16" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/documents/architecto/16"
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
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;title&quot;: &quot;R&egrave;glement int&eacute;rieur&quot;,
        &quot;file&quot;: &quot;documents/reglement.pdf&quot;,
        &quot;file_url&quot;: &quot;https://example.com/documents/reglement.pdf&quot;,
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;created_at&quot;: &quot;2025-01-01T10:00:00.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-01-02T10:00:00.000000Z&quot;
    },
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;document_id&quot;: 1
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
    &quot;meta&quot;: {
        &quot;discipline&quot;: &quot;blackball&quot;,
        &quot;document_id&quot;: 1
    },
    &quot;links&quot;: [],
    &quot;error&quot;: {
        &quot;code&quot;: &quot;document_not_found&quot;,
        &quot;message&quot;: &quot;Document not found&quot;
    }
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
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="discipline"                data-endpoint="GETapi-v1-documents--discipline---id-"
               value="architecto"
               data-component="url">
    <br>
<p>Slug de la discipline. Exemple : blackball Example: <code>architecto</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-documents--discipline---id-"
               value="16"
               data-component="url">
    <br>
<p>Identifiant du document. Exemple : 1 Example: <code>16</code></p>
            </div>
                    </form>

                <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-v1-posts-period--period-">GET api/v1/posts/period/{period}</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-posts-period--period-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/posts/period/architecto" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/posts/period/architecto"
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

<span id="example-responses-GETapi-v1-posts-period--period-">
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
vary: Origin
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;meta&quot;: [],
    &quot;links&quot;: [],
    &quot;error&quot;: {
        &quot;code&quot;: &quot;invalid_period&quot;,
        &quot;message&quot;: &quot;Invalid period. Valid values: depuis_2020, 2010_2019, 2000_2009, avant_2000&quot;
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-posts-period--period-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-posts-period--period-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-posts-period--period-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-posts-period--period-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-posts-period--period-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-posts-period--period-" data-method="GET"
      data-path="api/v1/posts/period/{period}"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-posts-period--period-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-posts-period--period-"
                    onclick="tryItOut('GETapi-v1-posts-period--period-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-posts-period--period-"
                    onclick="cancelTryOut('GETapi-v1-posts-period--period-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-posts-period--period-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/posts/period/{period}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-posts-period--period-"
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
                              name="Accept"                data-endpoint="GETapi-v1-posts-period--period-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>period</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="period"                data-endpoint="GETapi-v1-posts-period--period-"
               value="architecto"
               data-component="url">
    <br>
<p>The period. Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="licencies">Licenciés</h1>

    <p>La recherche est partielle (LIKE %value%).
Les résultats sont dédupliqués et limités aux champs utiles.</p>

                                <h2 id="licencies-GETapi-v1-licencies">Retourne la liste complète des licenciés.</h2>

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
x-ratelimit-remaining: 58
vary: Origin
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

                    <h2 id="licencies-GETapi-v1-licencies-search--name-">Recherche des licenciés par nom, prénom ou numéro de licence.</h2>

<p>
</p>



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
x-ratelimit-remaining: 57
vary: Origin
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

                <h1 id="public">Public</h1>

    <p>La réponse est mise en cache pendant 10 minutes.</p>
<p>L’article mis en avant correspond au dernier article favori.
Si aucun article favori n’existe, le dernier article publié est utilisé en fallback.</p>

                                <h2 id="public-GETapi-v1-public-site">Informations globales du site</h2>

<p>
</p>

<p>Retourne les informations générales nécessaires au front public :
paramètres du site et menus actifs.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;site_settings&quot;: {
            &quot;id&quot;: 1,
            &quot;logo&quot;: &quot;img/logo.png&quot;,
            &quot;logo_url&quot;: &quot;https://example.com/img/logo.png&quot;,
            &quot;banniere&quot;: &quot;img/banner.png&quot;,
            &quot;banniere_url&quot;: &quot;https://example.com/img/banner.png&quot;,
            &quot;adresse&quot;: &quot;28 Rue Joseph Cugnot, 37300 Jou&eacute;-l&egrave;s-Tours&quot;,
            &quot;telephone&quot;: null,
            &quot;email&quot;: &quot;contact@bcj37.fr&quot;,
            &quot;youtube_page&quot;: &quot;https://www.youtube.com/@BCJ37&quot;,
            &quot;facebook_page&quot;: &quot;https://www.facebook.com/example&quot;,
            &quot;facebook_page_id&quot;: &quot;123456789&quot;,
            &quot;created_at&quot;: &quot;2025-05-13T10:22:11.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-08-21T15:12:15.000000Z&quot;
        },
        &quot;menus&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;blackball&quot;,
                &quot;image&quot;: &quot;menu/blackball.png&quot;,
                &quot;image_url&quot;: &quot;https://example.com/menu/blackball.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-29T17:06:39.000000Z&quot;
            }
        ]
    },
    &quot;meta&quot;: {
        &quot;menus_count&quot;: 1
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

                    <h2 id="public-GETapi-v1-public-home">Données de la page d&#039;accueil</h2>

<p>
</p>

<p>Retourne les données nécessaires à l’affichage de la page d’accueil publique.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;site_settings&quot;: {
            &quot;id&quot;: 1,
            &quot;logo&quot;: &quot;img/logo.png&quot;,
            &quot;logo_url&quot;: &quot;https://example.com/img/logo.png&quot;,
            &quot;banniere&quot;: &quot;img/banner.png&quot;,
            &quot;banniere_url&quot;: &quot;https://example.com/img/banner.png&quot;,
            &quot;adresse&quot;: &quot;28 Rue Joseph Cugnot, 37300 Jou&eacute;-l&egrave;s-Tours&quot;,
            &quot;telephone&quot;: null,
            &quot;email&quot;: &quot;contact@bcj37.fr&quot;,
            &quot;youtube_page&quot;: &quot;https://www.youtube.com/@BCJ37&quot;,
            &quot;facebook_page&quot;: &quot;https://www.facebook.com/example&quot;,
            &quot;facebook_page_id&quot;: &quot;123456789&quot;,
            &quot;created_at&quot;: &quot;2025-05-13T10:22:11.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-08-21T15:12:15.000000Z&quot;
        },
        &quot;menus&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;blackball&quot;,
                &quot;image&quot;: &quot;menu/blackball.png&quot;,
                &quot;image_url&quot;: &quot;https://example.com/menu/blackball.png&quot;,
                &quot;actif&quot;: true,
                &quot;created_at&quot;: null,
                &quot;updated_at&quot;: &quot;2025-05-29T17:06:39.000000Z&quot;
            }
        ],
        &quot;partners&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Tours M&eacute;tropole&quot;,
                &quot;logo&quot;: &quot;partenaires/logo.png&quot;,
                &quot;logo_url&quot;: &quot;https://example.com/partenaires/logo.png&quot;,
                &quot;website_url&quot;: &quot;https://www.tours-metropole.fr&quot;,
                &quot;created_at&quot;: &quot;2025-05-26T14:21:37.000000Z&quot;,
                &quot;updated_at&quot;: &quot;2025-12-06T20:52:52.000000Z&quot;
            }
        ],
        &quot;featured_post&quot;: {
            &quot;id&quot;: 1,
            &quot;title&quot;: &quot;Titre de l&#039;article&quot;,
            &quot;slug&quot;: &quot;titre-de-l-article&quot;,
            &quot;excerpt&quot;: &quot;R&eacute;sum&eacute; de l&#039;article...&quot;,
            &quot;content&quot;: &quot;&lt;p&gt;Contenu HTML de l&#039;article&lt;/p&gt;&quot;,
            &quot;discipline&quot;: &quot;blackball&quot;,
            &quot;discipline_id&quot;: 1,
            &quot;year&quot;: 2026,
            &quot;favoris&quot;: true,
            &quot;image&quot;: null,
            &quot;image_url&quot;: null,
            &quot;video&quot;: null,
            &quot;created_at&quot;: &quot;2026-02-02T08:11:13.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2026-02-02T08:31:49.000000Z&quot;
        }
    },
    &quot;meta&quot;: {
        &quot;menus_count&quot;: 1,
        &quot;partners_count&quot;: 1,
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

                    <h2 id="public-GETapi-v1-partenaires">Liste des partenaires</h2>

<p>
</p>

<p>Retourne la liste des partenaires publics du club.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Tours M&eacute;tropole&quot;,
            &quot;logo&quot;: &quot;partenaires/logo.png&quot;,
            &quot;logo_url&quot;: &quot;https://example.com/partenaires/logo.png&quot;,
            &quot;website_url&quot;: &quot;https://www.tours-metropole.fr&quot;,
            &quot;created_at&quot;: &quot;2025-05-26T14:21:37.000000Z&quot;,
            &quot;updated_at&quot;: &quot;2025-12-06T20:52:52.000000Z&quot;
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

                    <h2 id="public-GETapi-v1-contact">Liste des contacts</h2>

<p>
</p>

<p>Retourne les informations de contact publiques du club.</p>

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
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;nom&quot;: &quot;BCJ37&quot;,
            &quot;email&quot;: &quot;contact@bcj37.fr&quot;,
            &quot;telephone&quot;: null
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

                    <h2 id="public-POSTapi-v1-contact">Envoie un message de contact.</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-contact">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/contact" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"email\": \"john.doe@example.com\",
    \"message\": \"Bonjour, je souhaite...\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/v1/contact"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "email": "john.doe@example.com",
    "message": "Bonjour, je souhaite..."
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-contact">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: null,
    &quot;meta&quot;: null,
    &quot;links&quot;: [],
    &quot;error&quot;: null
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-contact" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-contact"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-contact"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-contact" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-contact">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-contact" data-method="POST"
      data-path="api/v1/contact"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-contact', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-contact"
                    onclick="tryItOut('POSTapi-v1-contact');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-contact"
                    onclick="cancelTryOut('POSTapi-v1-contact');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-contact"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/contact</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-contact"
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
                              name="Accept"                data-endpoint="POSTapi-v1-contact"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-contact"
               value="John Doe"
               data-component="body">
    <br>
<p>Le nom de l'expéditeur. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-contact"
               value="john.doe@example.com"
               data-component="body">
    <br>
<p>L'email de l'expéditeur. Example: <code>john.doe@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>message</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="message"                data-endpoint="POSTapi-v1-contact"
               value="Bonjour, je souhaite..."
               data-component="body">
    <br>
<p>Le message de l'expéditeur. Example: <code>Bonjour, je souhaite...</code></p>
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
