<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Lien vers l'accueil du tableau de bord -->
            <a class="navbar-brand" href="<?= route_to('dashboard.index') ?>">Crystal Event</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <!-- Lien vers la gestion des images -->
                        <a class="nav-link" href="<?= route_to('images.index') ?>">Images</a>
                    </li>
                    <li class="nav-item">
                        <!-- Lien vers la gestion des pages -->
                        <a class="nav-link" href="<?= route_to('pages.index') ?>">Pages</a>
                    </li>
                    <li class="nav-item">
                        <!-- Lien vers la gestion des événements -->
                        <a class="nav-link" href="<?= route_to('events.index') ?>">Events</a>
                    </li>
                </ul>
                <div class="d-flex ms-3">
                    <!-- Lien Déconnexion -->
                    <a href="<?= route_to('auth.logout') ?>" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>