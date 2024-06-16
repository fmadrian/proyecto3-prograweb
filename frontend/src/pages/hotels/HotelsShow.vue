<template>
    <div class="container">
        <div class="grid">
            <h3 class="col text-2xl">{{ hotel.name }}</h3>
        </div>
        <div class="grid">
            <div class="flex flex-column col-12 md:col-6">
                <Card>
                    <template #title>
                        <h3>Información del hotel</h3>
                    </template>
                    <template #content>
                        <div class="grid grid-column gap-1">
                            <div
                                class="col-12 grid border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200 ">
                                <div class="col-2">
                                    <span>Nombre:</span>
                                </div>
                                <div class="col-10">
                                    <span> {{ hotel.name }}</span>
                                </div>
                            </div>
                            <div
                                class="col-12 grid border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">
                                <div class="col-2">
                                    <span>Estrellas:</span>
                                </div>
                                <div class="col-10">
                                    <span> {{ hotel.stars }}</span>
                                </div>
                            </div>
                            <div
                                class="col-12 grid border-1 border-cyan-200 bg-cyan-100 hover:bg-gray-200 hover:border-gray-200 ">
                                <div class="col-2">
                                    <span>Descripción:</span>
                                </div>
                                <div class="col-10">
                                    <span>{{ hotel.description }}</span>
                                </div>
                            </div>
                            <div
                                class="col-12 grid border-1 border-cyan-200 bg-cyan-200 hover:bg-gray-200 hover:border-gray-200 ">
                                <div class="col-2">
                                    Ubicación:
                                </div>
                                <div class="col-10">
                                    {{ hotel.location }}
                                </div>
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
                        <DataTable v-else :value="rooms" selectionMode="single" scrollable scrollHeight="400px">
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