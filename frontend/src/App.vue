<!-- src/App.vue -->
<template>
    <!-- 
        Reloads component after login or logout to solve a bug where despite having a change on the user object
        the navbar wouldn't be able to grab that new user object.
     -->
    <!-- 
        Defining  :key=user reloads the navbar each time the user changes. 
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