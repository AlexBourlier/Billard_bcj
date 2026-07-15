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
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
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

    /* Styles spécifiques */

    .parametres {
        background: #4a5568; /* gris foncé/bleu */
    }

    .club {
        background: #718096; /* gris moyen */
    }

    .blackball {
        background: linear-gradient(135deg, #000, #ffd700); /* noir et or */
    }

    .carambole {
        background: #c53030; /* rouge foncé */
    }

    .snooker {
        background: #2f855a; /* vert foncé */
    }

    .americain {
        background: #2b6cb0; /* bleu */
    }

    .externe {
        background: linear-gradient(135deg, #3182ce, #63b3ed); /* dégradé bleu ciel */
    }

    .analytics {
        background: linear-gradient(135deg, #3152A0, #5D8AFF);
    }
</style>

<div class="admin-grid">
    <div class="admin-card parametres">
        <a href="{{ admin_url('systeme') }}">
            <strong>Paramètres du site</strong><br>
            <i class="fa-solid fa-gears"></i>
        </a>
    </div>

    <div class="admin-card club">
        <a href="{{ admin_url('club') }}">
            <strong>Le Club</strong><br>
            <i class="fa-solid fa-building"></i>
        </a>
    </div>
</div>

<div class="admin-grid">
    <div class="admin-card blackball">
        <a href="{{ admin_url('blackball') }}">
            <strong>Blackball</strong><br>
            <i class="fa-solid fa-circle-dot"></i>
        </a>
    </div>

    <div class="admin-card carambole">
        <a href="{{ admin_url('carambole') }}">
            <strong>Carambole</strong><br>
            <i class="fa-solid fa-circle"></i>
        </a>
    </div>

    <div class="admin-card snooker">
        <a href="{{ admin_url('snooker') }}">
            <strong>Snooker</strong><br>
            <i class="fa-solid fa-circle"></i>
        </a>
    </div>

    <div class="admin-card americain">
        <a href="{{ admin_url('americain') }}">
            <strong>Américain</strong><br>
            <i class="fa-solid fa-circle-half-stroke"></i>
        </a>
    </div>
</div>

<div class="admin-grid">
    <div class="admin-card externe">
        <a href="{{ config('app.frontend_url') }}" target="_blank">
            <strong>Voir le site</strong><br>
            <i class="fa-solid fa-globe"></i>
        </a>
    </div>
    <div class="admin-card analytics">
        <a href="https://www.analytics.bcj37.fr/matomo/" target="_blank">
            <strong>Analyse Matomo</strong>
            <i class="fa-solid fa-chart-line"></i>
        </a>
    </div>
</div>

<div class="admin-grid">

</div>