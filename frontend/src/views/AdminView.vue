<script setup>

import {
    Package,
    Users,
    Star,
    ShoppingBag,
    Plus,
    Pencil,
    ShieldCheck
} from 'lucide-vue-next'

</script>

<template>

    <section class="admin-page">

        <!-- HEADER -->

        <div class="admin-header">

            <div>

                <p class="admin-badge">
                    PANEL PRIVADO
                </p>

                <h1 class="admin-title">
                    Zona de Administración
                </h1>

                <p class="admin-subtitle">
                    Gestiona productos, usuarios y contenido de Glamur Club.
                </p>

            </div>

            <div class="admin-icon">

                <ShieldCheck :size="42" />

            </div>

        </div>

        <!-- STATS -->

        <div class="row g-4 mb-5">

            <div class="col-md-3">

                <div class="stat-card">

                    <div class="stat-icon green">
                        <Package :size="28" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Productos
                        </p>

                        <h3 class="stat-value">
                            128
                        </h3>
                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <div class="stat-icon gold">
                        <Users :size="28" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Socios
                        </p>

                        <h3 class="stat-value">
                            2.4k
                        </h3>
                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <div class="stat-icon pink">
                        <ShoppingBag :size="28" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Pedidos
                        </p>

                        <h3 class="stat-value">
                            346
                        </h3>
                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="stat-card">

                    <div class="stat-icon blue">
                        <Star :size="28" />
                    </div>

                    <div>
                        <p class="stat-label">
                            Valoración
                        </p>

                        <h3 class="stat-value">
                            4.9
                        </h3>
                    </div>

                </div>

            </div>

        </div>

        <!-- ACTIONS -->

        <div class="admin-grid">

            <!-- PRODUCTS -->

            <div class="admin-card large">

                <div class="card-top">

                    <div>

                        <h3>
                            Gestión de Productos
                        </h3>

                        <p>
                            Añade, edita o elimina productos del catálogo.
                        </p>

                    </div>

                    <Package :size="32" />

                </div>

                <div class="action-buttons">

                    <button class="admin-btn primary">

                        <Plus :size="18" />

                        Nuevo Producto

                    </button>

                    <button class="admin-btn secondary">

                        <Pencil :size="18" />

                        Editar Catálogo

                    </button>

                </div>

            </div>

            <!-- USERS -->

            <div class="admin-card">

                <div class="card-top">

                    <div>

                        <h3>
                            Usuarios
                        </h3>

                        <p>
                            Control de roles y permisos.
                        </p>

                    </div>

                    <Users :size="28" />

                </div>

                <button class="admin-btn primary w-100 mt-4">
                    Gestionar Usuarios
                </button>

            </div>

            <!-- REVIEWS -->

            <div class="admin-card">

                <div class="card-top">

                    <div>

                        <h3>
                            Moderación
                        </h3>

                        <p>
                            Gestiona comentarios y reseñas.
                        </p>

                    </div>

                    <Star :size="28" />

                </div>

                <button class="admin-btn primary w-100 mt-4">
                    Revisar Comentarios
                </button>

            </div>

        </div>

    </section>

</template>

<style scoped>

/* =========================
   PAGE
========================= */

.admin-page {

    min-height: 100vh;

    padding: 3rem;

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

.admin-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 4rem;
}

.admin-badge {

    color: #27e0a3;

    font-weight: 700;

    letter-spacing: 2px;

    margin-bottom: 1rem;
}

.admin-title {

    font-size: 4rem;

    font-family:
        'Playfair Display',
        serif;

    color: white;

    margin-bottom: 1rem;
}

.admin-subtitle {

    color: rgba(255,255,255,0.65);

    font-size: 1.1rem;
}

.admin-icon {

    width: 90px;
    height: 90px;

    border-radius: 24px;

    display: flex;
    align-items: center;
    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #27e0a3,
            #1fc98f
        );

    color: #07150f;

    box-shadow:
        0 20px 40px rgba(39,224,163,0.25);
}

/* =========================
   STATS
========================= */

.stat-card {

    background:
        rgba(255,255,255,0.04);

    border:
        1px solid rgba(255,255,255,0.06);

    border-radius: 24px;

    padding: 1.5rem;

    display: flex;

    align-items: center;

    gap: 1rem;

    backdrop-filter: blur(12px);

    transition: all 0.3s ease;
}

.stat-card:hover {

    transform:
        translateY(-4px);

    border-color:
        rgba(39,224,163,0.3);
}

.stat-icon {

    width: 58px;
    height: 58px;

    border-radius: 18px;

    display: flex;
    align-items: center;
    justify-content: center;
}

.green {

    background:
        rgba(39,224,163,0.15);

    color:
        #27e0a3;
}

.gold {

    background:
        rgba(212,175,55,0.15);

    color:
        #d4af37;
}

.pink {

    background:
        rgba(255,100,124,0.15);

    color:
        #ff647c;
}

.blue {

    background:
        rgba(88,166,255,0.15);

    color:
        #58a6ff;
}

.stat-label {

    margin: 0;

    color: rgba(255,255,255,0.6);
}

.stat-value {

    margin: 0;

    font-size: 2rem;

    font-weight: 700;
}

/* =========================
   GRID
========================= */

.admin-grid {

    display: grid;

    grid-template-columns:
        2fr 1fr 1fr;

    gap: 1.5rem;
}

/* =========================
   CARDS
========================= */

.admin-card {

    background:
        rgba(255,255,255,0.04);

    border:
        1px solid rgba(255,255,255,0.06);

    border-radius: 28px;

    padding: 2rem;

    backdrop-filter: blur(12px);

    transition: all 0.3s ease;
}

.admin-card:hover {

    transform:
        translateY(-5px);

    border-color:
        rgba(39,224,163,0.25);
}

.card-top {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    margin-bottom: 2rem;
}

.card-top h3 {

    font-size: 1.5rem;

    margin-bottom: 0.7rem;
}

.card-top p {

    color: rgba(255,255,255,0.65);

    margin: 0;
}

.large {

    min-height: 260px;
}

/* =========================
   BUTTONS
========================= */

.action-buttons {

    display: flex;

    gap: 1rem;

    flex-wrap: wrap;
}

.admin-btn {

    border: none;

    padding:
        1rem 1.5rem;

    border-radius: 16px;

    font-weight: 700;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 0.6rem;

    transition: all 0.3s ease;
}

.primary {

    background:
        linear-gradient(
            135deg,
            #27e0a3,
            #1fc98f
        );

    color: #07150f;
}

.secondary {

    background:
        rgba(255,255,255,0.06);

    color: white;

    border:
        1px solid rgba(255,255,255,0.08);
}

.admin-btn:hover {

    transform:
        translateY(-3px);
}

/* =========================
   MOBILE
========================= */

@media (max-width: 991px) {

    .admin-page {

        padding: 2rem 1rem;
    }

    .admin-grid {

        grid-template-columns: 1fr;
    }

    .admin-header {

        flex-direction: column;

        align-items: flex-start;

        gap: 2rem;
    }

    .admin-title {

        font-size: 2.8rem;
    }

}

</style>