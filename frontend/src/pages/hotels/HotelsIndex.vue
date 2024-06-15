<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col-12 col-sm-8">
                <h2>Hoteles</h2>
            </div>

            <div v-if="isAdmin" class="col-12 col-sm-4">
                <router-link class="btn btn-outline-primary d-grid" :to="APP_ROUTES.hotels.create">Añadir</router-link>
            </div>

        </div>

        <div v-if="lastQuery.length === 0" class="row mb-3">
            <h5>Todos los {{ hotels.length }} hoteles disponibles:</h5>
        </div>

        <div v-else class="row mb-3">
            <h5 class="mb-3">Resultados de búsqueda "{{ lastQuery }}"</h5>
            <span>Mostrando {{ hotels.length }} hoteles:</span>
        </div>

        <div class="row">
            <span class="text-center" v-if="hotels.length === 0">No hay...</span>
            <table v-else class="table table-primary table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Estrellas</th>
                        <th>Descripción</th>
                        <th>Ubicación</th>
                        <th></th>
                    </tr>

                </thead>
                <tbody>
                    <tr v-for="hotel in hotels">
                        <td>{{ hotel.name }}</td>
                        <td>{{ hotel.stars }}</td>
                        <td>{{ hotel.description }}</td>
                        <td>{{ hotel.location }}</td>

                        <td>
                            <router-link class="btn btn-success"
                                :to="APP_ROUTES.hotels.view.link(hotel.id)">Ver</router-link>
                            <router-link v-if="isAdmin" class="btn btn-primary"
                                :to="APP_ROUTES.hotels.edit.link(hotel.id)">Modificar</router-link>
                            <button v-if="isAdmin" type="button" class="btn btn-danger"
                                v-on:click="deleteHotel(hotel.id)"><i class='bx bx-trash'></i>Eliminar</button>
                        </td>


                    </tr>

                </tbody>
            </table>
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
const hotels = ref([]);
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