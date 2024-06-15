<template>

    <div class="container">
        <div class="row">
            <h3>Información del usuario</h3>

        </div>
        <div class="row">
            <table class="table table-primary table-striped table-hover">
                <tbody>
                    <tr>
                        <th>Nombre</th>
                        <td>{{ queried_user.name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ queried_user.email }}</td>
                    </tr>
                    <tr v-if="isAdmin">
                        <th>Es administrador</th>
                        <td v-if="queried_user.is_admin">Sí</td>
                        <td v-else>No</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row">
            <h3>Reservaciones</h3>
            <span v-if="reservations.length == 0">No hay...</span>
            <table v-else class="table table-primary table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">Hotel</th>
                        <th scope="col">Habitación</th>
                        <th scope="col">Duración</th>
                        <th scope="col">Total</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>

                    <tr v-for="reservation in reservations">
                        <td>
                            <router-link :to="APP_ROUTES.hotels.view.link(reservation.reservation_hotel_id)">{{
                                reservation.reservation_hotel_name }}</router-link>
                        </td>
                        <td>
                            <router-link :to="APP_ROUTES.rooms.view.link(reservation.reservation_room_id)">{{
                                reservation.reservation_room_name }}</router-link>
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
import { useRoute, useRouter } from 'vue-router';

// Router.
const route = useRoute();
const router = useRouter();
// Data.
const queried_user = ref({});

const reservations = ref([]);
const user_id = ref(1);

// Estado.
const store = useGeneralStore();
const { user, isAdmin, isLoggedIn } = storeToRefs(store);

// Métodos 
function getUser(id) {
    // Almacenar usuario en estado y volver a inicio.
    fetchGet(API_ROUTES.users.getOne(id))
        .then(json => {
            if (json.status === 200) {
                queried_user.value = json.body.queried_user;
                reservations.value = json.body.reservations;
            }
        });
}

function deleteReservation(id) {
    fetchDelete(API_ROUTES.reservations.delete(id))
        .then(json => {
            if (json.status === 200)
                getUser(user_id.value);
        });
}

// onMounted
onMounted(() => {

    // No permitir a otros usuarios ver la información si no es propia o no son administradores
    user_id.value = route.params.id;
    if (!isLoggedIn.value || (user.value.id !== user_id.value && !isAdmin.value)) {
        router.push(APP_ROUTES.home);
    }
    getUser(user_id.value);
})
</script>