<header>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eligibilité</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
        <!-- Favicons -->
        <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
        <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/manifest.json">
        <link rel="mask-icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/safari-pinned-tab.svg" color="#712cf9">
        <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon.ico">
        <!-- Inclure Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

        <!-- Inclure Bootstrap JS depuis le site officiel de Bootstrap -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

        <meta name="theme-color" content="#712cf9">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js" integrity="sha384-yGBirT0+1QoAinzrR2XknviQHi4o0oVyJVrPPSkHQ6jA9xk5hP7lq5g/W0qU6v56" crossorigin="anonymous"></script>
        <!-- Leaflet JS -->
        <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
        <!-- Custom JavaScript -->
        <script src="./js/script.js"></script>
        <!-- Custom CSS -->
        <link href="./css/style.css" rel="stylesheet">
    </head>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="./img/Ema.png" alt="Logo">
            </a>
            <!-- Bouton pour afficher le menu sur les appareils mobiles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Contenu de la barre de navigation -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 justify-content-center custom-navbar-nav">
                    <!-- Fournisseur -->
                    <li class="nav-item custom-navbar-nav">
                        <i class="bi bi-box"></i>
                        <a class="nav-link" href="#">
                            Fournisseur <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Offres et Catalogue -->
                    <li class="nav-item">
                        <i class="bi bi-file-earmark-text"></i>
                        <a class="nav-link" href="#">
                            Offres et Catalogue <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Clients -->
                    <li class="nav-item">
                        <i class="bi bi-people"></i>
                        <a class="nav-link" href="#">
                            Clients <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Commandes -->
                    <li class="nav-item">
                        <i class="bi bi-bag-check"></i>
                        <a class="nav-link" href="#">
                            Commandes <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Parc -->
                    <li class="nav-item">
                        <i class="bi bi-building"></i>
                        <a class="nav-link" href="#">
                            Parc <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Exploitation -->
                    <li class="nav-item">
                        <i class="bi bi-gear"></i>
                        <a class="nav-link" href="#">
                            Exploitation <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Factures -->
                    <li class="nav-item">
                        <i class="bi bi-file-earmark-text"></i>
                        <a class="nav-link" href="#">
                            Factures <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                    <!-- Barre de recherche -->
                    <li class="nav-item">
                        <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                        </form>
                    </li>
                      <!-- Factures -->
                      <li class="nav-item">
                        <i class="bi bi-folder"></i>
                        <a class="nav-link" href="#">
                             <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                      <!-- Factures -->
                      <li class="nav-item">
                        <i class="bi bi-cart"></i>
                        <a class="nav-link" href="#">
                             <i class="bi bi-caret-down-fill"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>