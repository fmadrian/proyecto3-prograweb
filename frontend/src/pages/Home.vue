<template>
    <div class="container">
        <h1>Bienvenido a InnovaGestión</h1>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column row-gap-3">
                        <span class="d-block">Estoy buscando...</span>
                        <Searchbar></Searchbar>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Searchbar from '../components/Searchbar.vue';

</script>