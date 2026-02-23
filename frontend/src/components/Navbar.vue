<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <router-link class="navbar-brand" to="/">💎 GLAMUR CLUB</router-link>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/">Catálogo</router-link>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><router-link class="nav-link" to="/login">Entrar</router-link></li>
                </ul>
            </div>
        </div>
    </nav>
</template>