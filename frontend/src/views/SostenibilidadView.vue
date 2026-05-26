<template>

    <div class="sostenibilidad-page">

        <div class="container py-5">

            <!-- HEADER -->

            <div class="text-center mb-5">

                <h1 class="sostenibilidad-title">
                    Compromiso Sostenible (ASG)
                </h1>

                <p class="sostenibilidad-subtitle">
                    La belleza no debe estar reñida con el planeta.
                </p>

            </div>

            <!-- CARDS -->

            <div class="row g-4">

                <!-- CARD 1 -->

                <div class="col-md-4">

                    <div class="s-card text-center h-100">

                        <div class="s-icon">
                            📦
                        </div>

                        <h3 class="s-card-title">
                            Ecodiseño
                        </h3>

                        <p class="s-card-text">

                            Todos nuestros productos usan el distintivo
                            <strong>"Eco-Packaging"</strong>.

                            Minimizamos el uso de plásticos y empleamos cajas
                            de cartón 100% reciclado y reutilizable.

                        </p>

                    </div>

                </div>

                <!-- CARD 2 -->

                <div class="col-md-4">

                    <div class="s-card text-center h-100">

                        <div class="s-icon">
                            ⚡
                        </div>

                        <h3 class="s-card-title">
                            Eficiencia Digital
                        </h3>

                        <p class="s-card-text">

                            Esta SPA está optimizada para consumir menos batería
                            y red posible.

                            Las imágenes se sirven comprimidas y la carga es
                            asíncrona para reducir la huella digital.

                        </p>

                    </div>

                </div>

                <!-- CARD 3 -->

                <div class="col-md-4">

                    <div class="s-card text-center h-100">

                        <div class="s-icon">
                            🤝
                        </div>

                        <h3 class="s-card-title">
                            Impacto Social
                        </h3>

                        <p class="s-card-text">

                            Nuestra interfaz cumple estándares WCAG,
                            garantizando accesibilidad, contraste y navegación
                            para todas las personas.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>

<style scoped>

/* =========================
   PAGE
========================= */

.sostenibilidad-page {

    min-height: 100vh;

    background:
        linear-gradient(
            180deg,
            #101514 0%,
            #151b19 100%
        );

    color: white;
}

/* =========================
   HEADER
========================= */

.sostenibilidad-title {

    font-size: 3.5rem;

    font-weight: 700;

    color: #27e0a3;

    font-family:
        'Playfair Display',
        serif;

    margin-bottom: 1rem;
}

.sostenibilidad-subtitle {

    color: rgba(255,255,255,0.72);

    font-size: 1.15rem;
}

/* =========================
   CARD
========================= */

.s-card {

    background:
        linear-gradient(
            180deg,
            #1a211f 0%,
            #202826 100%
        );

    border:
        1px solid rgba(255,255,255,0.05);

    border-radius: 22px;

    padding: 2.5rem 2rem;

    transition:
        all 0.3s ease;

    box-shadow:
        0 10px 30px rgba(0,0,0,0.25);
}

.s-card:hover {

    transform:
        translateY(-6px);

    border-color:
        rgba(39,224,163,0.25);

    box-shadow:
        0 18px 40px rgba(0,0,0,0.35);
}

/* =========================
   ICON
========================= */

.s-icon {

    font-size: 4rem;

    margin-bottom: 1.5rem;
}

/* =========================
   TITLES
========================= */

.s-card-title {

    color: white;

    font-size: 1.6rem;

    font-weight: 700;

    margin-bottom: 1rem;

    font-family:
        'Playfair Display',
        serif;
}

/* =========================
   TEXT
========================= */

.s-card-text {

    color:
        rgba(255,255,255,0.72);

    line-height: 1.8;

    font-size: 1rem;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    .sostenibilidad-title {

        font-size: 2.5rem;
    }

    .s-card {

        padding: 2rem 1.5rem;
    }

}

</style>