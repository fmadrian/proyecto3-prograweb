<template>
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 col-sm-6">
                <h3>Información de la habitación</h3>
                <table class="table table-primary table-striped table-hover">
                    <tbody>

                        <tr>
                            <th>Nombre</th>
                            <td>{{ room.name }}</td>
                        </tr>
                        <tr>
                            <th>Hotel</th>
                            <td>
                                <router-link :to="APP_ROUTES.rooms.view.link(room.hotel_id)">{{
                                    room.hotel_name }}</router-link>
                            </td>

                        </tr>
                        <tr>
                            <th>Camas</th>
                            <td>{{ room.beds }}</td>
                        </tr>
                        <tr>
                            <th>Baños</th>
                            <td>{{ room.bathrooms }}</td>
                        </tr>
                        <tr>
                            <th>Extras</th>
                            <td>{{ room.additional }}</td>
                        </tr>
                        <tr>
                            <th>Precio</th>
                            <td>{{ room.price }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div v-if="isLoggedIn" class="col-12 col-sm-6 my-0 my-sm-3">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">Reservar habitación</h3>
                            <div class="row">
                                <div>
                                    <label class="form-label" for="duration">Duración</label>
                                    <input class="form-control" type="number" name="duration" v-model="duration" min="1"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row d-grid">
                                <button class="btn btn-primary" v-on:click="book">Reservar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div v-if="isAdmin" class="row">
            <h3>Reservaciones</h3>
            <span v-if="reservations.length == 0">No hay...</span>
            <table v-else class="table table-primary table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Usuario</th>
                        <th scope="col">Duración</th>
                        <th scope="col">Total</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reservation in reservations">
                        <td>
                            <router-link :to="APP_ROUTES.users.view.link(reservation.reservation_user_id)">{{
                                reservation.reservation_user_name }}</router-link>
                        </td>
                        <td>{{ reservation.duration }}</td>
                        <td>{{ reservation.total_price }}</td>

                        <td class="d-grid">
                            <button type="button" v-on:click="deleteReservation(reservation.id)"
                                class="btn btn-danger">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { storeToRefs } from 'pinia';
import { useGeneralStore } from '../../stores/generalStore';
import { API_ROUTES } from '../../utils/ApiRoutes';
import { APP_ROUTES } from '../../utils/AppRoutes';
import { fetchGet, fetchPost, fetchDelete } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

// Router.
const route = useRoute();

// Data.
const room = ref({});
const reservations = ref([]);
const duration = ref(1);
const room_id = ref("");

// Estado.
const store = useGeneralStore();
const { isAdmin, isLoggedIn } = storeToRefs(store);

// Métodos 
function getRoom(id) {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchGet(API_ROUTES.rooms.getOne(id))
        .then(json => {
            room.value = json.body.room;
            reservations.value = json.body.reservations;
        });
}

function book() {
    fetchPost(API_ROUTES.reservations.create, { duration: duration.value, room_id: room_id.value })
        .then(json => {
            if (json.status === 200)
                getRoom(room_id.value);
        });
}
function deleteReservation(id) {
    fetchDelete(API_ROUTES.reservations.delete(id))
        .then(json => {
            if (json.status === 200)
                getRoom(room_id.value);
        });
}

// onMounted
onMounted(() => {
    room_id.value = route.params.id
    getRoom(room_id.value);
})
</script>