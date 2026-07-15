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
            width: 220px;
            border-radius: 12px;
            text-align: center;
            color: white;
            padding: 20px 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 2px 2px 12px rgba(0, 0, 0, 0.2);
            font-weight: bold;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 2px 2px 18px rgba(0, 0, 0, 0.3);
        }

        .admin-card a {
            color: inherit;
            text-decoration: none;
            display: block;
        }

        .admin-card i {
            font-size: 3.5em;
            margin: 15px 0;
        }

        /* Variantes de couleurs */
        .admin-update {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6); /* bleu */
        }
        .admin-list {
            background: linear-gradient(135deg, #047857, #10b981); /* vert */
        }
        .admin-settings {
            background: linear-gradient(135deg, #92400e, #f59e0b); /* orange */
        }
        .admin-delete {
            background: linear-gradient(135deg, #991b1b, #ef4444); /* rouge */
        }  

      .alert {
            padding: 10px;
            margin: 10px auto;
            border-radius: 5px;
            width: 80%;
            text-align: center;
            font-weight: bold;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
  </style>
<h4>Licenciés du club BCJ 37</h4>
@if(session('success') || request('success'))
    <div class="alert alert-success">
        {{ session('success') ?? request('success') }}
    </div>
@endif

<div class="admin-grid">
    <div class="admin-card admin-update">
        <a href="{{ admin_url('license-import/batches') }}">
            <strong>Gérer les imports<br>({{ $nbLicencies }} licenciés)</strong></br>
            <i class="fa-solid fa-arrows-rotate"></i>
        </a>
    </div>

    <div class="admin-card admin-list">
        <a href="{{ admin_url('licencies') }}">
            <strong>Voir la liste</strong></br>
            <i class="fa-solid fa-users"></i>
        </a>
    </div>
</div>
<script>
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(el => el.style.display = 'none');
    }, 5000);
</script>