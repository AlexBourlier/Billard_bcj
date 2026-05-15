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

    /* Style spécifique pour les calendriers */
    .calendar-section {
        background: linear-gradient(135deg, #00695c, #26a69a); /* Teal dégradé */
    }
</style>

<div class="admin-grid">
    <div class="admin-card calendar-section">
        <a href="/admin/calendriers/blackball/international/events">
            <strong>Calendrier international</strong><br>
            <i class="fa-solid fa-globe"></i>
        </a>
    </div>

    <div class="admin-card calendar-section">
        <a href="/admin/calendriers/blackball/national/events">
            <strong>Calendrier national</strong><br>
            <i class="fa-solid fa-flag"></i>
        </a>
    </div>

    <div class="admin-card calendar-section">
        <a href="/admin/calendriers/blackball/regional/events">
            <strong>Calendrier régional</strong><br>
            <i class="fa-solid fa-map-location-dot"></i>
        </a>
    </div>

    <div class="admin-card calendar-section">
        <a href="/admin/calendriers/blackball/departemental/events">
            <strong>Calendrier départemental</strong><br>
            <i class="fa-solid fa-location-dot"></i>
        </a>
    </div>
</div>
