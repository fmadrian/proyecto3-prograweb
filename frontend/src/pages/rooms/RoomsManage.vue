<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Room </h5>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="name">Nombre</label>
                                <input class="form-control" type="text" name="name" v-model="form.name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="hotel_id">Hotel </label>
                                <select class="form-select" id="hotel_id" name="hotel_id" v-model="form.hotel_id">

                                    <option v-for="hotel in hotels" :value="hotel.id">{{ hotel.name }}
                                    </option>

                                </select>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="price">Precio</label>
                                <input class="form-control" type="number" name="price" v-model="form.price" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="beds">Camas de la Habitación</label>
                                <input class="form-control" type="number" name="beds" v-model="form.beds" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="bathrooms">Baños</label>
                                <input class="form-control" type="number" name="bathrooms" v-model="form.bathrooms"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="extras">Extras</label>
                                <input class="form-control" type="text" name="extras" v-model="form.additional"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="d-grid">
                                <input v-if="isUpdate" class="btn btn-primary" type="submit" value="Modificar hotel"
                                    v-on:click="submit">
                                <input v-else class="btn btn-primary" type="submit" value="Crear hotel"
                                    v-on:click="submit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useGeneralStore } from '../../stores/generalStore';
import { API_ROUTES } from '../../utils/ApiRoutes';
import { APP_ROUTES } from '../../utils/AppRoutes';
import { fetchGet, fetchPut, fetchPost } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

// Router.
const route = useRoute();
const router = useRouter();

// Data.
const isUpdate = ref(false);
const form = defineModel("form", {
    default: {
        name: "",
        hotel_id: "",
        price: "",
        beds: "",
        bathrooms: "",
        extras: ""
    }
})
const hotels = ref([])

// Estado.
const store = useGeneralStore();
const { isLoggedIn, isAdmin } = storeToRefs(store);
// Métodos 
function getRoom(id) {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchGet(API_ROUTES.rooms.getOne(id))
        .then(json => {
            // Escoger variables que se van a usar de la respuesta y pasarlas al formulario.
            let { name, hotel_id, price, beds, bathrooms, additional } = { ...json.body.room };
            form.value = { name, hotel_id, price, beds, bathrooms, additional };
        });
}
function submit() {
    let request, redirectTo;
    if (isUpdate.value) {
        request = fetchPut(API_ROUTES.rooms.edit(route.params.id), { ...form.value });
        redirectTo = APP_ROUTES.rooms.view.link(route.params.id);
    } else {
        request = fetchPost(API_ROUTES.rooms.create, { ...form.value })
        redirectTo = APP_ROUTES.rooms.index;
    }
    request.then((response) => {
        if (response.status == 200)
            router.push(redirectTo);
    });
}
function getHotels() {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchGet(API_ROUTES.hotels.get)
        .then(json => {
            hotels.value = json.body.hotels;
        });
}
// onMounted
onMounted(() => {
    // Redirigir si no está autenticado o no es administrador.
    if (!isAdmin.value)
        router.push(APP_ROUTES.home);
    // Actualizar el valor.
    if (route.params.id) {
        isUpdate.value = true;
        getRoom(route.params.id);
    }
    getHotels();

})
</script>