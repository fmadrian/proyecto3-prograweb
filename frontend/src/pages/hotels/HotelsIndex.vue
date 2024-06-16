<template>
    <div class="container">
        <div class="flex flex-column md:flex-row mb-3 align-items-center">
            <div class="col-12 md:col-6">
                <h2 class="text-5xl">Hoteles</h2>
            </div>
            <div class="col-12 md:col-6">
                <router-link v-if="isAdmin" :to="APP_ROUTES.hotels.create"><Button class="w-full" label="Añadir"
                        severity="info" /></router-link>
            </div>
        </div>

        <div v-if="lastQuery.length === 0" class="mb-3">
            <h5 class="text-xl">Todos los {{ hotels.length }} hoteles disponibles:</h5>
        </div>

        <div v-else class="mb-3">
            <h5 class="mb-3">Resultados de búsqueda "{{ lastQuery }}"</h5>
            <span>Mostrando {{ hotels.length }} hoteles:</span>
        </div>

        <div class="col-12">
            <span class="text-center" v-if="hotels.length === 0">No hay...</span>
            <DataTable v-else :value="hotels" selectionMode="single" scrollable scrollHeight="400px">
                <Column sortable field="name" header="Nombre"></Column>
                <Column sortable field="stars" header="Estrellas"></Column>
                <Column sortable field="description" header="Descripción"></Column>
                <Column sortable field="location" header="Ubicación"></Column>
                <Column header="">
                    <template #body="hotel">
                        <div class="flex gap-1">
                            <router-link :to="APP_ROUTES.hotels.view.link(hotel.data.id)">
                                <Button label="Ver" severity="success" />
                            </router-link>
                            <router-link v-if="isAdmin" :to="APP_ROUTES.hotels.edit.link(hotel.data.id)">
                                <Button label="Editar" severity="primary" />
                            </router-link>
                            <Button label="Eliminar" v-if="isAdmin" v-on:click="deleteHotel(hotel.data.id)"
                                severity="danger" />
                        </div>
                    </template>
                </Column>
                <Column header="">
                </Column>
                <Column header=""></Column>
            </DataTable>
        </div>
    </div>
</template>

<script setup>
import { useGeneralStore } from '../../stores/generalStore';
import { useRouter, useRoute, onBeforeRouteUpdate } from 'vue-router'
import { API_ROUTES } from '../../utils/ApiRoutes';
import { APP_ROUTES } from '../../utils/AppRoutes';
import { fetchGet, fetchDelete, fetchPost } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { storeToRefs } from 'pinia';

// Router.
const router = useRouter();
const route = useRoute();

// Data.
const hotels = ref([{

}]);
const lastQuery = ref("");
// Estado.
const store = useGeneralStore();
const { isAdmin } = storeToRefs(store);

// Métodos 
function getHotels(query = null) {
    let request = null;
    if (query === null || query.query.trim() === "") {
        request = fetchGet(API_ROUTES.hotels.get);
    } else {
        request = fetchPost(API_ROUTES.search, query);
    }
    request.then(json => {
        hotels.value = json.body.hotels;
    });
}

function deleteHotel(id) {
    fetchDelete(API_ROUTES.hotels.delete(id))
        .then(json => {
            // Recargar hoteles
            if (json.status === 200)
                getHotels();
        });
}

// onMounted
onMounted(() => {
    // Query vacio, carga todos los hoteles.
    if (Object.keys(route.query).length === 0)
        getHotels();
    else {
        lastQuery.value = route.query.query;
        getHotels({ ...route.query })
    }
});

onBeforeRouteUpdate(async (to, from) => {
    // react to route changes...
    if (Object.keys(to.query).length === 0) {
        lastQuery.value = "";
        getHotels();
    }
    else {
        lastQuery.value = to.query.query;
        getHotels({ ...to.query })
    }

});
</script>