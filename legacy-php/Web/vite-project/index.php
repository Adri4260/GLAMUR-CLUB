<?php
// index.php
require_once "./includes/auth_check.php";
$is_logged_in = is_logged_in();
?>
<!DOCTYPE html>
<html lang="es" class="dark" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLAMUR CLUB - Perfumes y Belleza de Lujo</title>
    <meta name="description" content="Descubre perfumes exclusivos y productos de belleza premium. Crea tu propio perfume personalizado con GLAMUR CLUB.">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="./public/css/styles.css">
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <a href="/" class="logo">GLAMUR CLUB</a>

                <div class="nav-links" id="navLinks">
                    <a href="./src/catalogo.html">Catálogo</a>
                    <a href="./src/crear-perfume.html">Crea tu Perfume</a>
                </div>

                <div class="nav-actions">
                    <?php if ($is_logged_in): ?>
                        <a href="./auth/profile.php" class="nav-icon profile-btn" title="Mi Perfil">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="./auth/login.php" class="btn btn-ghost login-btn">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle;">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                            </svg>
                            Entrar
                        </a>
                    <?php endif; ?>
                    <a href="/src/favoritos.html" class="nav-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span class="badge" id="favoritesBadge">0</span>
                    </a>
                    <a href="./src/carrito.html" class="nav-icon">
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

    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Lujo y Elegancia en Cada Fragancia</h1>
            <p class="hero-subtitle">Descubre perfumes exclusivos y crea tu propia fragancia personalizada con GLAMUR CLUB</p>
            <div class="hero-buttons">
                <a href="./src/catalogo.html" class="btn btn-primary btn-lg px-4">Ver Catálogo</a>
                <a href="./src/crear-perfume.html" class="btn btn-outline btn-lg px-4 text-white">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2v20M2 12h20" />
                    </svg>
                    Crea tu Perfume
                </a>
            </div>
        </div>
    </section>

    <section class="benefits py-5">
        <div class="container">
            <div class="row text-center text-md-start">
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <div class="benefit-item d-flex align-items-center gap-3">
                        <div class="benefit-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13" />
                                <path d="M16 8h6M16 12h6M16 16h6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-1 h5 fw-bold">Envío Gratis</h3>
                            <p class="mb-0 small">En pedidos superiores a €50</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-4 mb-md-0">
                    <div class="benefit-item d-flex align-items-center gap-3">
                        <div class="benefit-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 12v-2a4 4 0 1 0-8 0v2m-2 0h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-1 h5 fw-bold">Regalo en Cada Pedido</h3>
                            <p class="mb-0 small">Muestras exclusivas gratis</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="benefit-item d-flex align-items-center gap-3">
                        <div class="benefit-icon">
                            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2v20M2 12h20" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="mb-1 h5 fw-bold">Calidad Premium</h3>
                            <p class="mb-0 small">Productos de alta gama</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-products py-5">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="display-5 fw-bold" style="color: var(--color-primary);">Productos Destacados</h2>
                <p class="text-muted">Descubre nuestra selección de productos más vendidos y exclusivos</p>
            </div>
            <div class="products-grid" id="featuredProducts">
            </div>
            <div class="section-footer text-center mt-5">
                <a href="./src/catalogo.html" class="btn btn-outline px-4">Ver Todos los Productos</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        <a href="#" aria-label="Twitter"><svg width="24" height="24" fill="currentColor">
                                <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z" />
                            </svg></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Enlaces Rápidos</h4>
                    <ul>
                        <li><a href="./src/catalogo.html">Catálogo</a></li>
                        <li><a href="./src/crear-perfume.html">Crear Perfume</a></li>
                        <li><a href="./src/aboutUs.html">Sobre Nosotros</a></li>
                        <li><a href="./src/contacto.php">Contacto</a></li>
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
                    <h4>Newsletter</h4>
                    <p>Suscríbete para ofertas exclusivas</p>
                    <form class="newsletter-form" id="newsletterForm">
                        <input type="email" placeholder="Tu email" required>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 GLAMUR CLUB. Todos los derechos reservados.</p>
                <div class="footer-links">
                    <a href="#">Privacidad</a>
                    <a href="#">Términos</a>
                    <a href="#">Cookies</a>
                    <a href="./src/importar_excel.php">Cargar Excel</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="./public/js/script.js"></script>
    <script src="./public/js/validacion.js" defer></script>
</body>

</html>