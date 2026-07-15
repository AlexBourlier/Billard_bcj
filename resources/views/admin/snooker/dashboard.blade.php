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
        width: 200px;
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
        font-size: 4em;
        margin-top: 10px;
    }

    /* Style spécifique pour le snooker */
    .snooker-section {
        background: linear-gradient(135deg, #1b5e20, #4caf50);
    }
</style>

<div class="admin-grid">


    <div class="admin-card snooker-section">
        <a href="{{ admin_url('cuescore-classements') }}?discipline=snooker&scope=national">
            <strong>Classements · National</strong><br>
            <i class="fa-solid fa-trophy"></i>
        </a>
    </div>

    <div class="admin-card snooker-section">
        <a href="{{ admin_url('cuescore-classements') }}?discipline=snooker&scope=départemental">
            <strong>Classements · Départemental</strong><br>
            <i class="fa-solid fa-trophy"></i>
        </a>
    </div>

    <div class="admin-card snooker-section">
        <a href="{{ admin_url('snooker/calendrier') }}">
            <strong>Calendrier</strong><br>
            <i class="fa-solid fa-calendar-days"></i>
        </a>
    </div>


    <div class="admin-card snooker-section">
        <a href="{{ admin_url('documents-snooker') }}">
            <strong>Documents</strong><br>
            <i class="fa-solid fa-file-lines"></i>
        </a>
    </div>

</div>
