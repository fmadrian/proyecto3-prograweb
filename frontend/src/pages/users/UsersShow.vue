<template>
    <div class="container flex flex-column">
        <div class="flex flex-column md:flex-row align-items-center">
            <div class="col-12 md:col-6">
                <h3 class="col text-2xl">{{ queried_user.name }}</h3>
            </div>
            <div class="col-12 md:col-6">
                <router-link v-if="isAdmin || isLoggedIn" :to="APP_ROUTES.users.edit.link(queried_user.id)"><Button
                        class="w-full" label="Modificar usuario" severity="info" /></router-link>
            </div>
        </div>

        <div class="flex flex-row flex-wrap align-items-stretch">
            <div class="mb-3 col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3 class="mb-3">Información del usuario</h3>
                    </template>
                    <template #content>
                        <div class="grid grid-column">
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200">

                                <span class="block font-bold">Nombre:</span>


                                <span class="block">{{ queried_user.name }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200">

                                <span class="block font-bold">Correo electrónico:</span>


                                <span class="block">{{ queried_user.email }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-20 ">

                                <span class="block font-bold">¿Es administrador?</span>


                                <span class="block"> {{ queried_user.is_admin ? "Si" : "No" }}</span>

                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <div class="mb-3 col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3>Reservaciones</h3>
                    </template>
                    <template #content>
                        <span class="block font-bold mb-5">{{ reservations.length > 0 ? `Mostrando
                            ${reservations.length}
                            reservaciones:` : `No
                            hay reservaciones.`}}</span>
                        <DataTable v-if="reservations.length > 0" :value="reservations" selectionMode="single"
                            scrollable scrollHeight="300px">
                            <Column sortable header="Hotel">
                                <template #body="reservation">
                                    <router-link
                                        :to="APP_ROUTES.hotels.view.link(reservation.data.reservation_hotel_id)">{{
                                            reservation.data.reservation_hotel_name }}</router-link>
                                </template>
                            </Column>
                            <Column sortable field="duration" header="Duración"></Column>
                            <Column sortable field="total_price" header="Total"></Column>
                            <Column sortable header="">
                                <template #body="reservation">
                                    <Button label="Eliminar" v-on:click="deleteReservation(reservation.data.id)"
                                        severity="danger" />
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>
            </div>
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