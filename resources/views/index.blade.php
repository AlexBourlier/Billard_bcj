@section('meta_description', 'Bienvenue sur le site du club de billard de Joué-Lès-Tours : compétitions, résultats, actualités et informations sur toutes les disciplines.')
<x-layout>
    {{-- Lien d'évitement --}}
    <a class="skip-link" href="#main-content">Aller au contenu principal</a>

    {{-- H1 de page --}}
    <header class="sr-only">
        <h1>Billard Club de Joué-Lès-Tours - Accueil</h1>
    </header>

    <main id="main-content" role="main">
    <section>
        <img src="{{ asset('uploads/' . $banniere->banniere) }}" alt="Bannière du Billard Club de Joué-Lès-Tours" loading="eager" decoding="async">
    </section>
    {{-- Calque blanc transparent --}}
    <div class="overlay" aria-hidden="true"></div>

    <section class="accueil">
        {{-- Elément accueil start --}}
        <div class="home">
            <x-title>Accueil</x-title>
            <x-cadre>
                @if($indexPost)
                @php
                    $allowedTags = '<p><br><b><i><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6><span>';
                    $cleanContent = strip_tags($indexPost->content, $allowedTags);
                @endphp
                    <p>{!! nl2br($cleanContent) !!}</p>
                @endif
            </x-cadre>
        </div>
        {{-- Elément accueil end --}}
        {{-- Elément post start --}}
        {{-- Si un post est mis à la une dans l'administration, 
            celui ci s'affiche, sinon on affiche les posts facebook 
        ou rien du tout --}}
        {{-- @if($favoriPost)
        <div class="favoriPost p-0 m-0">
            <x-title>Actualité</x-title>
            <x-cadre>
                <h2 class="text-2xl font-bold mb-2">{{ $favoriPost->title }}</h2>
                <div class="p-0 w-full">
                    @if ($favoriPost->thumbnail)
                        <img src="{{ asset('storage/' . $favoriPost->thumbnail) }}" alt="Illustration : {{ $favoriPost->title }}" class="my-4 w-96px w-md m-auto rounded shadow" loading="lazy" decoding="async">
                    @endif

                    @if ($favoriPost->video)
                        <div class="mt-4">
                            <iframe src="{{ $favoriPost->video }}" title="Vidéo : {{ $favoriPost->title }}" width="560" height="315" frameborder="0" allowfullscreen class="w-full h-64"></iframe>
                        </div>
                    @endif
                    @php
                        $allowedTags = '<p><br><b><i><strong><em><ul><ol><li><a><h1><h2><h3><h4><h5><h6><span>';
                        $cleanContent = strip_tags($favoriPost->content, $allowedTags);
                    @endphp

                    <p>{!!  nl2br($cleanContent) !!}</p>
                </div>
            </x-cadre>
        </div>
        @endif --}}
        {{-- Elément post end --}}
        {{-- Elément maps start --}}
        <div class="maps" aria-labelledby="maps-title">
            <x-title id="maps-title">Nous trouver</x-title>
            <x-cadre>
                <p class="sr-only" id="map-desc">
                    Carte indiquant l'emplacement du Billard Club de Joué-Lès-Tours.
                </p>

                {{-- Conteneur carte (reste vide tant que pas consenti) --}}
                <div id="map"
                    class="h-[360px] w-full rounded-lg bg-gray-100"
                    role="region"
                    aria-labelledby="maps-title"
                    aria-describedby="map-desc">
                </div>

                {{-- Lien accessible en complément (ouvre un nouvel onglet) --}}
                <p class="mt-2">
                    <a href="https://www.google.com/maps/search/?api=1&query=Billard+Club+de+Joué-Lès-Tours"
                    target="_blank" rel="noopener noreferrer"
                    aria-label="Ouvrir l’itinéraire dans Google Maps (nouvel onglet)">
                    Ouvrir dans Google Maps
                    </a>
                </p>
                <!-- Leaflet (multi-CDN fallback) + init -->
                <script>
                (function(){
                const CSS = [
                    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
                    'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css',
                    'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css'
                ];
                const JS = [
                    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
                    'https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js'
                ];

                function loadCSS(url){ return new Promise((res, rej) => {
                    const l = document.createElement('link'); l.rel = 'stylesheet'; l.href = url;
                    l.onload = res; l.onerror = () => rej(url); document.head.appendChild(l);
                });}
                function loadJS(url){ return new Promise((res, rej) => {
                    const s = document.createElement('script'); s.src = url; s.async = true;
                    s.onload = res; s.onerror = () => rej(url); document.head.appendChild(s);
                });}

                async function loadLeaflet(){
                    if (window.L) return;
                    let last = null;
                    for (let i=0;i<CSS.length;i++){
                    try { await loadCSS(CSS[i]); await loadJS(JS[i]); if (window.L) return; }
                    catch(e){ last = e; }
                    }
                    console.error('[Leaflet] échec chargement sur tous CDNs', last);
                }

                function initMap(){
                    const mapEl = document.getElementById('map');
                    if (!mapEl) return;

                    const lat = 47.341811344805635, lng = 0.6309143289699515;
                    const map = L.map(mapEl, {
                    center: [lat, lng],
                    zoom: 17,
                    scrollWheelZoom: false
                    });

                    // Tuiles OpenStreetMap (sans cookie)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 30,
                    attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    // Marqueur + popup
                    L.marker([lat, lng], { title: 'Billard Club de Joué-Lès-Tours' })
                    .addTo(map)
                    .bindPopup('<strong>Billard Club de Joué-Lès-Tours</strong><br>28 Rue Joseph Cugnot, 37300 Joué-lès-Tours<br>5 Pool<br>5 Carambole<br>1 Snooker')
                    .openPopup();

                    // Échelle (utile + accessible)
                    L.control.scale().addTo(map);
                }

                document.addEventListener('DOMContentLoaded', async () => {
                    await loadLeaflet();
                    if (window.L) initMap();
                });
                })();
                </script>


            </x-cadre>

        </div>
        {{-- Elément maps end --}}
    </section>
    {{-- Elément partenaires start --}}
    <div class="partenaire lg:pt-4">
    <x-title>Partenaires</x-title>
    <x-cadre>
        <div class="partenaires-flex">
            @foreach ($partenaires as $image)
                <div class="partenaire-item">
                    <a href="{{ $image->url }}" target="_blank" rel="noopener noreferrer"><img src="{{ asset('storage/' . $image->img) }}" alt="{{ $image->titre ?: ''}}" class="src" @if(empty($image->titre)) aria-hidden="true" @endif loading="lazy" decoding="async"></a>
                </div>
            @endforeach
        </div>
    </x-cadre>
    </div>
    {{-- Elément partenaires end --}}
    </main>
</x-layout>

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Billard Club de Joué-Lès-Tours",
  "url": "https://bcj37.fr/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://bcj37.fr/recherche?q={query}",
    "query-input": "required name=query"
  }
}
</script>
@endpush

<style>
    .skip-link {
        position: absolute;
        left:-9999px;
        top: auto;
        width: 1px;
        height: 1px;
        overflow:hidden;
    }

    .skip-link:focus{
        position: static;
        width: auto;
        height: auto;
        padding: .5rem 1rem;
        outline: 2px solid;
    }

    :focus-visible {
        outline: 2px solid currentColor;
        outline-offset: 2px;
    }

    #map { 
        height: 360px;
        isolation: isolate;
        z-index: 0;
    }

    .hidden {
        display: none !important;
    }

</style>