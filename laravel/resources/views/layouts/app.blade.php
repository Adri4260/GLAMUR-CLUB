<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GLAMUR CLUB') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/reviews_api.js'])
</head>

<body>

    @include('layouts.navigation')

    <main>
        {{ $slot }}
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>GLAMUR CLUB</h3>
                    <p>Tu destino de lujo para perfumes y productos de belleza exclusivos.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><svg width="24" height="24" fill="currentColor">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg></a>
                        <a href="#" aria-label="Instagram"><svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="{{ route('catalogo') }}">Catálogo</a></li>
                        <li><a href="#">Sobre Nosotros</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contacto</h4>
                    <ul class="contact-info">
                        <li>📍 Calle Elegancia 123, Madrid</li>
                        <li>📞 +34 900 123 456</li>
                        <li>✉️ info@glamurclub.com</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Admin</h4>
                    <ul>
                        @auth
                        @if(Auth::user()->email === 'admin@glamur.com')
                        <li><a href="{{ route('import.show') }}" style="color:var(--color-accent);">📂 Importar Excel</a></li>
                        <li><a href="{{ route('admin.products.index') }}" style="color:var(--color-accent);">⚙️ Gestión Productos</a></li>
                        @endif
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} GLAMUR CLUB. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const navLinks = document.getElementById('navLinks');
        if (mobileBtn && navLinks) {
            mobileBtn.addEventListener('click', () => {
                navLinks.classList.toggle('active');
                mobileBtn.classList.toggle('active');
            });
        }
    </script>
</body>

</html>