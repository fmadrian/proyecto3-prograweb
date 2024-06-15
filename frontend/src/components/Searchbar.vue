<template>

    <div class="d-flex">
        <div class="col-4">
            <select class="form-select" v-model="form.query_type">
                <option value="hotels">Hotel</option>
                <option value="rooms">Habitación</option>
            </select>
        </div>
        <div class="col-8">
            <div class="input-group">
                <input class="form-control" type="text" v-model="form.query" placeholder="Búsqueda...">
                <button class="btn btn-success" type="button" v-on:click="search">Buscar</button>
            </div>
        </div>
    </div>



</template>

<script setup>

import { APP_ROUTES } from '../utils/AppRoutes';
import { useRouter } from 'vue-router';

// Router (permite redirigir)
const router = useRouter();
// Data
const form = defineModel("form",
    {
        "default": {
            query_type: "hotels",
            query: ""
        }
    }
);

function search() {
    router.push({ path: APP_ROUTES[form.value.query_type].index, query: { ...form.value } });
}

</script>