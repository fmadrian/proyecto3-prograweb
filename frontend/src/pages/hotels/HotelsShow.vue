<template>
    <div class="container">

        <div class="flex flex-column md:flex-row align-items-center">
            <div class="col-12 md:col-6">
                <h3 class="col text-2xl">{{ hotel.name }}</h3>
            </div>
            <div class="col-12 md:col-6">
                <router-link v-if="isAdmin" :to="APP_ROUTES.hotels.edit.link(hotel.id)"><Button class="w-full"
                        label="Modificar hotel" severity="info" /></router-link>
            </div>
        </div>

        <div class="grid">
            <div class="flex flex-column col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3>Información del hotel</h3>
                    </template>
                    <template #content>
                        <div class="grid grid-column">
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Nombre:</span>


                                <span class="block"> {{ hotel.name }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Estrellas:</span>


                                <span class="block"> {{ hotel.stars }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Descripción:</span>


                                <span class="block">{{ hotel.description }}</span>

                            </div>
                            <div
                                class="col-12 flex gap-2 border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">

                                <span class="block font-bold">Ubicación:</span>


                                <span class="block">{{ hotel.location }}</span>

                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <div class="flex flex-column col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3>Habitaciones del hotel</h3>
                    </template>
                    <template #content>
                        <span v-if="rooms.length === 0">No hay...</span>
                        <DataTable v-else :value="rooms" selectionMode="single" scrollable scrollHeight="300px">
                            <Column header="Nombre" sortable>
                                <template #body="room">
                                    <router-link :to="APP_ROUTES.rooms.view.link(room.data.room_id)">
                                        {{ room.data.room_name }}
                                    </router-link>
                                </template>
                            </Column>
                            <Column field="price" header="Precio" sortable></Column>
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
const { isAdmin } = storeToRefs(store);

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