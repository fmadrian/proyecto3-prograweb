<template>
    <div class="container">
        <div class="flex flex-column md:flex-row align-items-center">
            <div class="col-12 md:col-6">
                <h3 class="col text-2xl">{{ room.name }}</h3>
            </div>
            <div class="col-12 md:col-6">
                <router-link v-if="isAdmin" :to="APP_ROUTES.rooms.edit.link(room.id)"><Button class="w-full"
                        label="Modificar habitación" severity="info" /></router-link>
            </div>
        </div>

        <div class="grid">
            <div class="flex flex-column col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3>Información de la habitación</h3>
                    </template>
                    <template #content>
                        <div class="flex flex-column">
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200">
                                <span class="block font-bold">Nombre:</span>
                                <span class="block"> {{ room.name }}</span>
                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Hotel (nombre):</span>


                                <router-link class="block" :to="APP_ROUTES.hotels.view.link(room.hotel_id)">{{
                                    room.hotel_name }}</router-link>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Camas:</span>


                                <span class="block"> {{ room.beds }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Baños:</span>


                                <span class="block"> {{ room.bathrooms }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Adicional:</span>


                                <span class="block"> {{ room.additional }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">
                                <span class="block font-bold">Precio:</span>
                                <span class="block"> {{ room.price }}</span>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <div v-if="isLoggedIn" class="flex flex-column col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3>Reservar habitación</h3>
                    </template>
                    <template #content>
                        <div class="flex mb-3 align-items-center gap-5 px-8">
                            <label class="col-2 font-bold block mb-2" for="duration">Duración</label>
                            <InputNumber class="col-10" v-model="duration" name="duration" suffix=" días" :min="1"
                                showButtons required />
                        </div>
                        <div class="flex flex-column gap-1">
                            <Divider />
                            <Button severity="primary" v-on:click="book" label="Reservar" />
                        </div>
                    </template>
                </Card>
            </div>
        </div>
        <Divider />

        <div v-if="isAdmin" class="flex flex-column">
            <Card>
                <template #header>
                    <h3>Reservaciones</h3>
                </template>
                <template #content>
                    <span v-if="reservations.length == 0">No hay...</span>
                    <DataTable v-else :value="reservations" selectionMode="single" scrollable scrollHeight="300px">
                        <Column sortable header="Usuario">
                            <template #body="reservation">
                                <router-link :to="APP_ROUTES.users.view.link(reservation.data.reservation_user_id)">{{
                                    reservation.data.reservation_user_name }}</router-link>
                            </template>
                        </Column>
                        <Column sortable field="duration" header="Duración"></Column>
                        <Column sortable field="total_price" header="Total"></Column>
                        <Column sortable header="">
                            <template #body="reservation">
                                <Button label="Eliminar" v-if="isAdmin"
                                    v-on:click="deleteReservation(reservation.data.id)" severity="danger" />
                            </template>
                        </Column>
                    </DataTable>
                </template>
            </Card>
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
const room = ref({});
const reservations = ref([]);
const duration = ref(1);
const room_id = ref("");

// Estado.
const store = useGeneralStore();
const { user, isAdmin, isLoggedIn } = storeToRefs(store);

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
            if (json && json.status === 200) {
                getRoom(room_id.value);
                router.push(APP_ROUTES.users.view.link(user.value.id));
            }
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