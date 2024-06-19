<!-- src/App.vue -->
<template>
    <!-- 
        Se recarga el componente después de hacer login o logout para resolver un bug 
        donde a pesar de que hay un cambio de usuario en el estado, el navbar no detecta el cambio.
     -->
    <!-- 
        Definiendo :key=user se recarga el navbar cada vez que cambia el usuario. 
        https://stackoverflow.com/a/54367510 
    -->
    <app-header :key="user"></app-header>
    <main class="container-fluid px-5 py-4">
        <router-view></router-view>
    </main>
    <app-footer></app-footer>
</template>

<script setup>
import AppHeader from './components/AppHeader.vue';
import AppFooter from './components/AppFooter.vue';

import { storeToRefs } from 'pinia';
import { useGeneralStore } from './stores/generalStore';
// Estado
const store = useGeneralStore();
// Elementos de estado.
const { user } = storeToRefs(store);

</script>