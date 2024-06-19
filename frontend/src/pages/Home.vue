<template>
    <div class="container">
        <h1>¡Bienvenido a InnovaGestión! {{ isLoggedIn ? user.name : '' }}</h1>
        <div class="card">
            <div class="card-body">
                <div class="flex flex-column row-gap-3">
                    <span class="block">Estoy buscando...</span>
                    <div class="px-8">
                        <Searchbar />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import Searchbar from '../components/Searchbar.vue';
import { useGeneralStore } from '../stores/generalStore';
// Estado
const store = useGeneralStore();
// Elementos de estado.
const { user, isLoggedIn } = storeToRefs(store);

</script>