<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../modules/auth/store.js'
import { useRouter } from 'vue-router'
import RoleBadge from '../modules/roles/components/RoleBadge.vue'

const isMenuOpen = ref(false)
const authStore = useAuthStore()
const router = useRouter()

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
}

const handleLogout = async () => {
    await authStore.logout()
    isMenuOpen.value = false
    router.push('/login')
}
</script>

<template>
    <nav class="navbar shadow-sm">
        <div class="container">
            <div class="nav-content d-flex justify-content-between align-items-center w-100 py-3">

                <router-link to="/" class="logo text-decoration-none fw-bold fs-4"
                    style="font-family: 'Playfair Display', serif; color: var(--text-primary);"
                    @click="isMenuOpen = false">
                    GLAMUR CLUB
                </router-link>

                <div class="nav-links d-none d-md-flex gap-4">
                    <router-link to="/" class="text-decoration-none text-muted fw-bold">Inicio</router-link>
                    <router-link to="/catalogo" class="text-decoration-none text-muted fw-bold">Catálogo</router-link>

                    <router-link v-if="authStore.isAdmin" to="/admin"
                        class="text-decoration-none text-danger fw-bold">Gestión (Admin)</router-link>
                </div>

                <div class="nav-actions d-flex align-items-center gap-3">

                    <div v-if="authStore.isAuthenticated" class="dropdown">
                        <button class="btn btn-outline-dark btn-sm dropdown-toggle d-flex align-items-center"
                            type="button" data-bs-toggle="dropdown">
                            Hola, {{ authStore.user?.name || 'Usuario' }}
                            <RoleBadge />
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li><a class="dropdown-item text-danger" href="#" @click.prevent="handleLogout">Cerrar
                                    Sesión</a></li>
                        </ul>
                    </div>

                    <router-link v-else to="/login" class="btn btn-sm fw-bold px-3"
                        style="background: var(--color-primary); color: #000;">
                        Entrar
                    </router-link>

                </div>
            </div>
        </div>
    </nav>
</template>