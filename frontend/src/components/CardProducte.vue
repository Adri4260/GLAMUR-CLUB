<script setup>
import RoleGuard from '../modules/roles/components/RoleGuard.vue'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const getImageUrl = (path) => {

    if (!path)
        return 'http://localhost:8000/img/prod1.jpg'

    if (path.startsWith('http'))
        return path

    let cleanPath =
        path
            .replace('/public/', '')
            .replace('public/', '')

    if (!cleanPath.startsWith('img/'))
        cleanPath = 'img/' + cleanPath

    return `http://localhost:8000/${cleanPath}`
}
</script>

<template>

    <div class="card product-card h-100 position-relative">

        <!-- BADGE -->
        <span class="eco-badge">
            🌱 Eco-Packaging
        </span>

        <!-- IMAGE -->
        <div class="image-wrapper">

            <img :src="getImageUrl(product.image)" class="product-image"
                :alt="'Fotografía del producto ' + product.name">

        </div>

        <!-- BODY -->
        <div class="card-body d-flex flex-column text-center">

            <span class="sku-badge">
                {{ product.sku }}
            </span>

            <h5 class="card-title product-title">
                {{ product.name }}
            </h5>

            <p class="product-description flex-grow-1">
                {{ product.description?.substring(0, 60) }}...
            </p>

            <div class="product-price">
                {{ product.price }} €
            </div>

            <!-- BUTTONS -->
            <div class="mt-auto d-grid gap-2">

                <router-link :to="'/producto/' + product.id" class="details-btn">
                    Ver Detalles
                </router-link>

                <div class="d-flex gap-2 mt-2">

                    <RoleGuard requirePermission="edit">

                        <button class="edit-btn flex-fill" aria-label="Editar producto">
                            ✏️ Editar
                        </button>

                    </RoleGuard>

                    <RoleGuard requirePermission="delete">

                        <button class="delete-btn flex-fill" aria-label="Borrar producto">
                            🗑️ Borrar
                        </button>

                    </RoleGuard>

                </div>

            </div>

        </div>

    </div>

</template>

<style scoped>
/* =========================
   CARD
========================= */

.product-card {

    background:
        linear-gradient(180deg,
            #18201d 0%,
            #111715 100%) !important;

    border:
        1px solid rgba(255, 255, 255, 0.06) !important;

    border-radius: 22px;

    overflow: hidden;

    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease,
        border-color 0.3s ease;

    box-shadow:
        0 12px 35px rgba(0, 0, 0, 0.28);
}

.product-card:hover {

    transform:
        translateY(-8px);

    border-color:
        rgba(39, 224, 163, 0.25) !important;

    box-shadow:
        0 18px 45px rgba(0, 0, 0, 0.38);
}

/* =========================
   BADGE
========================= */

.eco-badge {

    position: absolute;

    top: 14px;
    right: 14px;

    z-index: 10;

    background: #1fc98f;

    color: #07150f;

    font-weight: 700;

    padding:
        0.45rem 0.8rem;

    border-radius: 999px;

    font-size: 0.75rem;

    box-shadow:
        0 8px 20px rgba(39, 224, 163, 0.25);
}

/* =========================
   IMAGE
========================= */

.image-wrapper {

    height: 280px;

    padding: 1.2rem;

    display: flex;
    align-items: center;
    justify-content: center;

    overflow: hidden;
}

.product-image {

    width: 100%;
    height: 100%;

    object-fit: contain;

    transition:
        transform 0.4s ease;
}

.product-card:hover .product-image {

    transform:
        scale(1.05);
}

/* =========================
   BODY
========================= */

.card-body {

    padding:
        1.5rem;
}

/* SKU */

.sku-badge {

    align-self: center;

    background:
        rgba(255, 255, 255, 0.06);

    border:
        1px solid rgba(255, 255, 255, 0.08);

    color:
        rgba(255, 255, 255, 0.75);

    padding:
        0.4rem 0.9rem;

    border-radius: 999px;

    font-size: 0.75rem;

    margin-bottom: 1rem;
}

/* TITLE */

.product-title {

    color: white;

    font-size: 1.7rem;

    font-weight: 700;

    margin-bottom: 1rem;

    font-family:
        'Playfair Display',
        serif;
}

/* DESCRIPTION */

.product-description {

    color:
        rgba(255, 255, 255, 0.68);

    font-size: 0.95rem;

    line-height: 1.7;

    margin-bottom: 1.5rem;
}

/* PRICE */

.product-price {

    color: #27e0a3;

    font-size: 2rem;

    font-weight: 700;

    margin-bottom: 1.5rem;
}

/* =========================
   BUTTONS
========================= */

.details-btn {

    display: inline-block;

    width: 100%;

    border: none;

    background:
        linear-gradient(135deg,
            #27e0a3,
            #1fc98f);

    color: #07150f;

    text-decoration: none;

    font-weight: 700;

    padding: 1rem;

    border-radius: 14px;

    transition:
        all 0.3s ease;
}

.details-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 12px 24px rgba(39, 224, 163, 0.25);

    color: #07150f;
}

/* EDIT */

.edit-btn {

    border:
        1px solid rgba(255, 255, 255, 0.12);

    background:
        rgba(255, 255, 255, 0.03);

    color: white;

    padding: 0.75rem;

    border-radius: 12px;

    font-weight: 600;

    transition: all 0.3s ease;
}

.edit-btn:hover {

    background:
        rgba(255, 255, 255, 0.08);
}

/* DELETE */

.delete-btn {

    border:
        1px solid rgba(255, 100, 124, 0.35);

    background: transparent;

    color: #ff647c;

    padding: 0.75rem;

    border-radius: 12px;

    font-weight: 600;

    transition: all 0.3s ease;
}

.delete-btn:hover {

    background:
        rgba(255, 100, 124, 0.08);
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 768px) {

    .image-wrapper {

        height: 240px;
    }

    .product-title {

        font-size: 1.4rem;
    }

}
</style>