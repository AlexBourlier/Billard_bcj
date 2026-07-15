<head>
  <script src="https://kit.fontawesome.com/a6212ffa8d.js" crossorigin="anonymous"></script>
</head>

<style>
    .admin-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 40px;
    }

    .admin-card {
        width: 230px;
        border-radius: 10px;
        text-align: center;
        color: white;
        padding: 15px;
        transition: transform 0.2s ease;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    }

    .admin-card:hover {
        transform: translateY(-5px);
    }

    .admin-card a {
        color: inherit;
        text-decoration: none;
        display: block;
    }

    .admin-card i {
        font-size: 3.5em;
        margin-top: 10px;
    }

    .cuescore-section {
        background: linear-gradient(135deg, #4a148c, #7b1fa2);
    }
</style>

<h4>Classements CueScore — Snooker</h4>
<p style="text-align:center; color:#555;">
    Gère ici les classements CueScore du snooker. Chaque écran regroupe les
    classements individuels <strong>et</strong> par équipes (filtrables par type).
</p>
<div class="admin-grid">
    <div class="admin-card cuescore-section">
        <a href="{{ admin_url('cuescore-classements') }}?discipline=snooker&scope=national">
            <strong>National</strong><br>
            <i class="fa-solid fa-trophy"></i>
        </a>
    </div>

    <div class="admin-card cuescore-section">
        <a href="{{ admin_url('cuescore-classements') }}?discipline=snooker&scope=départemental">
            <strong>Départemental</strong><br>
            <i class="fa-solid fa-trophy"></i>
        </a>
    </div>
</div>
