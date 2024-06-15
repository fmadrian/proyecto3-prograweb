<template>
    <section class="text-center">
        <h1>404</h1>
        <p>Página no encontrada</p>
        <span class="navbar-brand"><router-link :to="APP_ROUTES.home">Ir a
                inicio</router-link></span>
    </section>
</template>

<script setup>
import { APP_ROUTES } from '../utils/AppRoutes';

</script>