@php use Illuminate\Support\Str; @endphp

<style>
    .dashboard-overview {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
        padding: 2rem;
    }

    .dashboard-section {
        width: 100%;
        max-width: 900px;
        background: #8c96a9; /* nouvelle couleur plus claire */
        color: #333; /* texte sombre pour contraste lisible */
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* ombre plus subtile */
        padding: 2rem;
    }

    .dashboard-section h3 {
        color: #2a2a2a;
        margin-bottom: 1rem;
        text-align: center;
    }

    .dashboard-section img {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
        margin-bottom: 1rem;
        border-radius: 8px;
        background: #fff;
        display: block;
        margin: auto;
    }

    .dashboard-info {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1rem;
        font-size: 1rem;
    }

    .blurred {
        filter: blur(5px);
        transition: filter 0.3s ease;
    }

    .toggle-button {
        display: inline-block;
        margin-left: 10px;
        cursor: pointer;
        color: #2a2a2a;
        font-weight: bold;
    }

    .menu-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 1.5rem;
    }

    .menu-card {
        text-align: center;
    }

    .menu-card img {
        width: 173px;
        height: 145px;
        border-radius: 8px;
        border: 2px solid #444;
        object-fit: cover;
        background: #fff;
        filter: grayscale(100%);
        transition: filter 0.3s ease;
    }

    .menu-card.actif img {
        filter: none;
        border-color: #2a2a2a;
    }

    .menu-card span {
        display: block;
        margin-top: 0.5rem;
        color: #fff;
    }

    .api-key {
        display: inline-block;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        background-color: #2c2c2c;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        vertical-align: middle;
        max-width: 350px; /* Ajuste selon ton layout */
    }

    .blurred {
        filter: blur(5px);
        transition: filter 0.2s ease;
    }

    .api-key.revealed {
        filter: none;
        white-space: normal;
        overflow: visible;
        text-overflow: unset;
        overflow-x: scroll;
        color: #fff
    }

    .toggle-button {
        cursor: pointer;
        color: #2a2a2a;
        margin-left: 10px;
        font-size: 0.9em;
        text-decoration: underline;
    }

    .menu-grid {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
    }

    .menu-card {
        text-align: center;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.2s ease, opacity 0.3s;
        width: 173px;
        height: 145px;
        opacity: 1;
        position: relative;
    }

    .menu-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(100%);
        transition: filter 0.3s, opacity 0.3s;
    }

    .menu-card.actif img {
        filter: grayscale(0%);
        opacity: 1;
    }

    .menu-card span {
        position: absolute;
        bottom: 5px;
        width: 100%;
        text-align: center;
        background: rgba(0,0,0,0.5);
        color: white;
        font-weight: bold;
        font-size: 0.9em;
    }

    .edit-button {
        display: inline-block;
        margin-top: 1rem;
        background-color: #2a2a2a;
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .edit-button:hover {
        background-color: #444;
    }

    .section-description {
        font-size: 0.9em;
        color: #222;
        margin-bottom: 1rem;
        text-align: center;
    }

</style>

<div class="dashboard-overview">

    <div class="dashboard-section">
        <h3>Logo du site</h3>
        <div class="section-description">Ce logo apparaît dans l'en-tête du site et dans le footer du site, s'il doit être remplacé, respecté le ratio 706x349 px<br> Format PNG conseillé</Form></div>
        @if($settings && $settings->logo)
            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo du site">
        @else
            <p>Aucun logo enregistré.</p>
        @endif
        <a href="{{ admin_url('site-settings/1/edit/') }}" class="edit-button">Modifier le logo</a>
    </div>

    <div class="dashboard-section">
        <h3>Bannière du site</h3>
        <div class="section-description">Image d'accueil affichée sur la page principale du site, si celle-ci doit être remplacée, respecté le ratio 3222x964 px <br> Format PNG conseillé</div>
        @if($settings && $settings->banniere)
            <img src="{{ asset('storage/' . $settings->banniere) }}" alt="Bannière du site">
        @else
            <p>Aucune bannière enregistrée.</p>
        @endif
        <a href="{{ admin_url('site-settings/1/edit') }}" class="edit-button">Modifier la bannière</a>
    </div>
    
    <div class="dashboard-section">
        <h3>Menu du site</h3>
        <p style="text-align:center; font-size: 0.95em; color: #222; margin-top: -10px; margin-bottom: 20px;">
            💡 Cliquez sur une image pour activer ou désactiver le menu correspondant.
        </p>
        <div class="menu-grid">
            @foreach(\App\Models\Menu::all() as $menu)
                <div class="menu-card {{ $menu->actif ? 'actif' : '' }}" data-id="{{ $menu->id }}">
                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->nom }}">
                    <span>{{ ucfirst($menu->nom) }}</span>
                </div>
            @endforeach
        </div>
        <a href="{{ admin_url('menus') }}" class="edit-button">Modifier les menus</a>
    </div>

    <div class="dashboard-section">
        <h3>Informations de contact</h3>
        <div class="dashboard-info">
            <div><strong>Adresse du club :</strong></div>
            <div>{{ $settings->adresse ?? '-' }}</div>
            
            <div><strong>Téléphone :</strong></div>
            <div>{{ $settings->telephone ?? '-' }}</div>

            <div><strong>Email :</strong></div>
            <div>{{ $settings->email ?? '-' }}</div>

            <div><strong>Page Facebook :</strong></div>
            <div>
                @if($settings->facebook_page)
                    <p>{{ $settings->facebook_page }}</p>
                    <a href="{{ $settings->facebook_page }}" target="_blank" style="color:#2a2a2a;">
                        Voir la page
                    </a>
                @else
                    -
                @endif
                
            </div>
            <div><strong>Page Youtube :</strong></div>
            <div>
                @if($settings->youtube_page)
                    <p>{{ $settings->youtube_page }}</p>
                    <a href="{{ $settings->youtube_page }}" target="_blank" style="color:#2a2a2a;">
                        Voir la page
                    </a>
                @else
                    -
                @endif
                
            </div>
            <a href="{{ admin_url('site-settings/1/edit') }}" class="edit-button">Modifier les informations</a>
        </div>
    </div>

    {{-- <div class="dashboard-section">
        <h3>Clés API</h3>
        <div class="dashboard-info">
            <div><strong>Facebook Access Token :</strong></div>
            <div>
                <span class="api-key blurred" id="token">{{ $settings->facebook_token ?? '-' }}</span>
                <span class="toggle-button" onclick="toggleBlur('token')">Afficher</span>
            </div>

            <div><strong>Facebook Page ID :</strong></div>
            <div>
                <span class="api-key blurred" id="facebook">{{ $settings->facebook_page_id ?? '-' }}</span>
                <span class="toggle-button" onclick="toggleBlur('facebook')">Afficher</span>
            </div>

            <div><strong>Google Maps API :</strong></div>
            <div>
                <span class="api-key blurred" id="google">{{ $settings->google_map_api ?? '-' }}</span>
                <span class="toggle-button" onclick="toggleBlur('google')">Afficher</span>
            </div>
            <a href="{{ admin_url('site-settings/1/edit') }}" class="edit-button">Modifier les clé API</a>

        </div>
    </div> --}}


</div>

<script>
    function toggleBlur(id) {
        const span = document.getElementById(id);
        span.classList.toggle('revealed');
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.menu-card').forEach(card => {
            card.addEventListener('click', function () {
                const id = this.dataset.id;

                fetch(`/admin/menu/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        this.classList.toggle('actif');
                    }
                });
            });
        });
    });
</script>

