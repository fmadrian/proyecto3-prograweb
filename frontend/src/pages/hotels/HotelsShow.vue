<template>
    <div class="container">
        <div class="row">
            <h3>Información del hotel</h3>
        </div>
        <div class="row">
            <table class="table table-primary table-striped table-hover">
                <tbody>

                    <tr>
                        <th>Nombre</th>
                        <td>{{ hotel.name }}</td>
                    </tr>
                    <tr>
                        <th>Estrellas</th>
                        <td>{{ hotel.stars }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="row">
            <h3>Habitaciones del hotel</h3>
            <span v-if="rooms.length === 0">No hay...</span>
            <table v-else class="table table-primary table-striped table-hover">
                <tbody>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                    </tr>
                    <tr v-for="room in rooms">
                        <td><router-link :to="APP_ROUTES.rooms.view.link(room.room_id)">
                                {{ room.room_name
                                }}</router-link></td>
                        <td>{{ room.price }}</td>
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
import { fetchGet } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

// Router.
const route = useRoute();

// Data.
const hotel = ref({});
const rooms = ref([]);
// Estado.
const store = useGeneralStore();

// Métodos 
function getHotel(id) {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchGet(API_ROUTES.hotels.getOne(id))
        .then(json => {
            hotel.value = json.body.hotel;
            rooms.value = json.body.rooms;
        });
}

// onMounted
onMounted(() => {
    getHotel(route.params.id);
})
</script>