<template>


    <InputGroup>
        <Dropdown v-model="form.query_type" :options="options" optionLabel="name" optionValue="query_type"
            placeholder="Hotel / habitación" />
        <InputText type="text" v-model="form.query" placeholder="Búsqueda..." />
        <Button severity="success" v-on:click="search" label="Buscar" />
    </InputGroup>



</template>

<script setup>

import Dropdown from 'primevue/dropdown';
import { APP_ROUTES } from '../utils/AppRoutes';
import { useRouter } from 'vue-router';
import { ref } from 'vue';

// Router (permite redirigir)
const router = useRouter();
// Data
const form = ref({
    query_type: "hotels",
    query: ""
});

const options = ref([
    { name: 'Hotel', query_type: "hotels" },
    { name: 'Habitaciones', query_type: "rooms" },
]);

function search() {
    router.push({ path: APP_ROUTES[form.value.query_type].index, query: { ...form.value } });
}

</script>