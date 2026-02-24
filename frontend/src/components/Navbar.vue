<script setup>
import { ref } from 'vue'

// Variable reactiva para el menú de móvil
const isMenuOpen = ref(false)

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
}
</script>

<template>
    <nav class="navbar">
        <div class="container">
            <div class="nav-content">
                <button class="mobile-menu-btn" :class="{ active: isMenuOpen }" @click="toggleMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <router-link to="/" class="logo" @click="isMenuOpen = false">GLAMUR CLUB</router-link>

                <div class="nav-links" :class="{ active: isMenuOpen }">
                    <router-link to="/" @click="isMenuOpen = false">Catálogo</router-link>
                </div>

                <div class="nav-actions">
                    <router-link to="/login" class="btn btn-ghost login-btn" style="color: var(--text-primary);">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                            style="vertical-align: middle;">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3" />
                        </svg>
                        Entrar
                    </router-link>

                    <a href="#" class="nav-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</template>