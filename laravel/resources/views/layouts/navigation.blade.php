<nav class="navbar">
    <div class="container">
        <div class="nav-content">
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <a href="{{ route('catalogo') }}" class="logo">GLAMUR CLUB</a>

            <div class="nav-links" id="navLinks">
                <a href="{{ route('catalogo') }}">Catálogo</a>
                <a href="#">Crea tu Perfume</a>
            </div>

            <div class="nav-actions">
                @auth
                <div class="dropdown">
                    <a href="#" class="nav-icon profile-btn" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Mi Perfil">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        <span style="font-size: 12px; margin-left: 5px;">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="background: var(--bg-card); border-color: var(--color-border);">
                        @if(Auth::user()->email === 'admin@glamur.com')
                        <li><a class="dropdown-item" href="{{ route('admin.products.index') }}" style="color: var(--color-accent);">⚙️ Administración</a></li>
                        <li>
                            <hr class="dropdown-divider" style="border-color: var(--color-border);">
                        </li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}" style="color: var(--text-primary);">Mi Perfil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: #ff6b6b;">Cerrar Sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn btn-ghost login-btn" style="color: var(--text-primary);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                    </svg>
                    Entrar
                </a>
                @endauth

                <a href="#" class="nav-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                    <span class="badge" id="favoritesBadge">0</span>
                </a>

                <a href="#" class="nav-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1" />
                        <circle cx="20" cy="21" r="1" />
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                    </svg>
                    <span class="badge" id="cartBadge">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>