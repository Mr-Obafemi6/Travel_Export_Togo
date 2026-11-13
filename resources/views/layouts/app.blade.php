@php
    $utilisateur = auth()->user();
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Travel Togo — découvrez les destinations, excursions et expériences du Togo, et réservez en ligne.">
    <title>@yield('title', 'Travel Togo — Le Togo à hauteur d’émotion')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="@yield('body-class')">
    <nav class="navbar navbar-expand-lg navbar-tt sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="brand-mark"><i class="bi bi-compass"></i></span>
                <span class="brand-text">Travel <strong>Togo</strong></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Ouvrir le menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}" href="{{ route('destinations.index') }}">Destinations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('excursions.*') ? 'active' : '' }}" href="{{ route('excursions.index') }}">Excursions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.*') ? 'active' : '' }}" href="{{ route('contact.create') }}">Contact</a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-lg-center gap-lg-2">
                    @auth
                        @if($utilisateur->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Administration
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}" href="{{ route('reservations.index') }}">
                                <i class="bi bi-calendar2-check me-1"></i>Mes réservations
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-circle">{{ mb_strtoupper(mb_substr($utilisateur->nom, 0, 1)) }}</span>
                                <span class="d-none d-xl-inline">{{ \Illuminate\Support\Str::limit($utilisateur->nom, 18) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Mon profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('reservations.index') }}"><i class="bi bi-journal-check me-2"></i>Mes réservations</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-tt btn-sm px-3" href="{{ route('register') }}">Créer un compte</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show alert-tt" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show alert-tt" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Merci de vérifier le formulaire.</strong>
                    <ul class="mb-0 mt-2 ps-4">
                        @foreach($errors->all() as $erreur)
                            <li>{{ $erreur }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer-tt">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <a class="footer-brand" href="{{ route('home') }}">
                        <span class="brand-mark"><i class="bi bi-compass"></i></span>
                        Travel <strong>Togo</strong>
                    </a>
                    <p class="mt-3 text-white-50">
                        La plateforme qui rassemble les plus belles destinations du Togo et vous met
                        en relation avec des expériences locales authentiques.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <a class="social-link" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a class="social-link" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a class="social-link" href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="footer-title">Explorer</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                        <li><a href="{{ route('excursions.index') }}">Excursions</a></li>
                        <li><a href="{{ route('contact.create') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="footer-title">Mon espace</h6>
                    <ul class="list-unstyled footer-links">
                        @auth
                            <li><a href="{{ route('reservations.index') }}">Mes réservations</a></li>
                            <li><a href="{{ route('profile.edit') }}">Mon profil</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Connexion</a></li>
                            <li><a href="{{ route('register') }}">Inscription</a></li>
                        @endauth
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="footer-title">Nous joindre</h6>
                    <ul class="list-unstyled footer-contact">
                        <li><i class="bi bi-geo-alt me-2"></i>Lomé, Togo</li>
                        <li><i class="bi bi-envelope me-2"></i>contact@traveltogo.tg</li>
                        <li><i class="bi bi-telephone me-2"></i>+228 90 00 00 00</li>
                    </ul>
                </div>
            </div>
            <hr class="border-light opacity-25 my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2 small text-white-50">
                <span>&copy; {{ date('Y') }} Travel Togo. Tous droits réservés.</span>
                <span>Conçu avec passion pour le tourisme togolais.</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
