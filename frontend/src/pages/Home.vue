<template>
    <div class="container px-5">
        <h1>Bienvenido a InnovaGestión</h1>
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
import Searchbar from '../components/Searchbar.vue';

</script>