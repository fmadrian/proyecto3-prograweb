<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-12 col-sm-8">
                <h2>Gestión de habitaciones</h2>
            </div>

            <div v-if="isAdmin" class="col-12 col-sm-4">
                <router-link class="btn btn-outline-primary" :to="APP_ROUTES.rooms.create">Añadir</router-link>
            </div>
        </div>
        <div v-if="lastQuery.length === 0" class="row mb-3">
            <h5>Todas las {{ rooms.length }} habitaciones disponibles:</h5>
        </div>

        <div v-else class="row mb-3">
            <h5 class="mb-3">Resultados de búsqueda "{{ lastQuery }}"</h5>
            <span>Mostrando {{ rooms.length }} habitaciones:</span>
        </div>

        <div class="row">
            <span class="text-center" v-if="rooms.length === 0">No hay...</span>
            <table v-else class="table table-primary table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Hotel (nombre)</th>
                        <th>Cantidad de camas</th>
                        <th>Baños</th>
                        <th>Extras</th>
                        <th>Precio (por día)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="room in rooms">
                        <td>{{ room.name }}</td>

                        <td>
                            <router-link :to="APP_ROUTES.rooms.view.link(room.hotel_id)">{{
                                room.hotel_name }}</router-link>
                        </td>

                        <td>{{ room.beds }}</td>
                        <td>{{ room.bathrooms }}</td>
                        <td>{{ room.additional }}</td>
                        <td>{{ room.price }}</td>
                        <td> <router-link class="btn btn-success"
                                :to="APP_ROUTES.rooms.view.link(room.id)">Ver</router-link>

                            <router-link v-if="isAdmin" class="btn btn-primary"
                                :to="APP_ROUTES.rooms.edit.link(room.id)">Modificar</router-link>
                            <button v-if="isAdmin" type="button" class="btn btn-danger"
                                v-on:click="deleteRoom(room.id)">
                                Eliminar</button>
                        </td>


                    </tr>
                </tbody>
            </table>
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