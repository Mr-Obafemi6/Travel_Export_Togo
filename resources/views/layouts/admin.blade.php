@php
    $utilisateur = auth()->user();
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration') — Travel Togo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-mark"><i class="bi bi-compass"></i></span>
                Travel <strong>Togo</strong>
            </a>

            <nav class="admin-nav">
                <div class="admin-nav-label">Pilotage</div>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>Tableau de bord
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i>Réservations
                </a>

                <div class="admin-nav-label">Catalogue</div>
                <a href="{{ route('admin.destinations.index') }}" class="{{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>Destinations
                </a>
                <a href="{{ route('admin.excursions.index') }}" class="{{ request()->routeIs('admin.excursions.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>Excursions
                </a>
                <a href="{{ route('admin.regions.index') }}" class="{{ request()->routeIs('admin.regions.*') ? 'active' : '' }}">
                    <i class="bi bi-signpost-split"></i>Régions
                </a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>Catégories
                </a>

                <div class="admin-nav-label">Communauté</div>
                <a href="{{ route('admin.utilisateurs.index') }}" class="{{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>Utilisateurs
                </a>
                <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i>Messages
                    @if(($messagesNonLus ?? 0) > 0)
                        <span class="badge rounded-pill text-bg-warning">{{ $messagesNonLus }}</span>
                    @endif
                </a>

                <div class="admin-nav-label">Site</div>
                <a href="{{ route('home') }}">
                    <i class="bi bi-box-arrow-up-right"></i>Voir le site
                </a>
            </nav>
        </aside>

        <div class="admin-backdrop d-none" id="adminBackdrop"></div>

        <div class="admin-main">
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light border btn-icon d-lg-none" type="button" id="adminMenuToggle" aria-label="Ouvrir le menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <div class="text-small text-muted-tt">Administration</div>
                        <strong class="text-tt-green">@yield('heading', 'Tableau de bord')</strong>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-md-block text-end">
                        <div class="text-small fw-semibold">{{ $utilisateur->nom }}</div>
                        <div class="text-small text-muted-tt">Administrateur</div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light border btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu du compte">
                            <i class="bi bi-person"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-tt alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-tt alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Merci de vérifier le formulaire.</strong>
                        <ul class="mb-0 mt-2 ps-4">
                            @foreach($errors->all() as $erreur)
                                <li>{{ $erreur }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            var sidebar = document.getElementById('adminSidebar');
            var backdrop = document.getElementById('adminBackdrop');
            var toggle = document.getElementById('adminMenuToggle');

            function fermer() {
                sidebar.classList.remove('open');
                backdrop.classList.add('d-none');
            }

            if (toggle && sidebar && backdrop) {
                toggle.addEventListener('click', function () {
                    sidebar.classList.toggle('open');
                    backdrop.classList.toggle('d-none');
                });

                backdrop.addEventListener('click', fermer);
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
