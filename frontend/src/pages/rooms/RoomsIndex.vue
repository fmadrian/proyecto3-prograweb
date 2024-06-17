<template>
    <div class="container">
        <div class="flex flex-column md:flex-row align-items-center">
            <div class="col-12 md:col-6">
                <h2 class="text-5xl">Gestión de habitaciones</h2>
            </div>
            <div class="col-12 md:col-6">
                <router-link v-if="isAdmin" :to="APP_ROUTES.rooms.create"><Button class="w-full" label="Añadir"
                        severity="info" /></router-link>
            </div>
        </div>


        <div class="col" v-if="lastQuery.length === 0">
            <h5 class="text-xl">Todas las {{ rooms.length }} habitaciones disponibles:</h5>
        </div>

        <div class="col" v-else>
            <h5>Resultados de búsqueda "{{ lastQuery }}"</h5>
            <span>Mostrando {{ rooms.length }} habitaciones:</span>
        </div>

        <div class="col-12">
            <span class="text-center" v-if="rooms.length === 0">No hay habitaciones disponibles...</span>
            <DataTable v-else :value="rooms" selectionMode="single" scrollable scrollHeight="300px">
                <Column sortable field="name" header="Nombre"></Column>
                <Column sortable header="Hotel (nombre)">
                    <template #body="room">
                        <div class="flex gap-1">
                            <router-link :to="APP_ROUTES.rooms.view.link(room.data.hotel_id)">
                                {{ room.data.hotel_name }}
                            </router-link>
                        </div>
                    </template>
                </Column>
                <Column sortable field="beds" header="Camas"></Column>
                <Column sortable field="bathrooms" header="Habitaciones"></Column>
                <Column sortable field="additional" header="Extras"></Column>
                <Column sortable field="price" header="Precio"></Column>
                <Column header="">
                    <template #body="room">
                        <div class="flex gap-1">
                            <router-link :to="APP_ROUTES.rooms.view.link(room.data.id)">
                                <Button label="Ver" severity="success" />
                            </router-link>
                            <router-link v-if="isAdmin" :to="APP_ROUTES.rooms.edit.link(room.data.id)">
                                <Button label="Editar" severity="primary" />
                            </router-link>
                            <Button label="Eliminar" v-if="isAdmin" v-on:click="deleteRoom(room.data.id)"
                                severity="danger" />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>

<script setup>
import { useGeneralStore } from '../../stores/generalStore';
import { API_ROUTES } from '../../utils/ApiRoutes';
import { APP_ROUTES } from '../../utils/AppRoutes';
import { fetchGet, fetchDelete, fetchPost } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { storeToRefs } from 'pinia';
import { useRoute, onBeforeRouteUpdate } from 'vue-router';

// Data.
const rooms = ref([]);
const lastQuery = ref("");
// Router
const route = useRoute();

// Estado.
const store = useGeneralStore();
const { isAdmin } = storeToRefs(store);

// Métodos 
function getRooms(query = null) {
    let request = null;
    if (query === null || query.query.trim() === "") {
        request = fetchGet(API_ROUTES.rooms.get);
    } else {
        request = fetchPost(API_ROUTES.search, query);
    }
    request.then(json => {
        rooms.value = json.body.rooms;
    });
}

function deleteRoom(id) {
    fetchDelete(API_ROUTES.rooms.delete(id))
        .then(json => {
            // Recargar hoteles
            if (json.status === 200)
                getRooms();
        });
}

// onMounted
onMounted(() => {
    // Query vacio, carga todos los habitaciones.
    if (Object.keys(route.query).length === 0)
        getRooms();
    else {
        lastQuery.value = route.query.query;
        getRooms({ ...route.query })
    }
});
// Reacción al cambiar de ruta.
onBeforeRouteUpdate(async (to, from) => {
    // Obtener nuevo valor.  
    if (Object.keys(to.query).length === 0) {
        // Limpiar último query.
        lastQuery.value = "";
        getRooms();
    }
    else {
        lastQuery.value = to.query.query;
        getRooms({ ...to.query })
    }
});
</script>