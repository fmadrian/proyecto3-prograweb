<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Hotel </h5>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="name">Nombre</label>
                                <input class="form-control" type="text" name="name" v-model="form.name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="stars">Estrellas</label>
                                <input class="form-control" type="number" name="stars" v-model="form.stars" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="description">Descripción</label>
                                <input class="form-control" type="text" name="description" v-model="form.description"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="email">Ubicación</label>
                                <input class="form-control" type="text" name="location" v-model="form.location"
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
import { onMounted, ref, toRaw } from 'vue';
import { useRoute, useRouter } from 'vue-router';

// Router.
const route = useRoute();
const router = useRouter();

// Data.
const isUpdate = ref(false);
const form = defineModel("form", {
    default: {
        name: "",
        stars: "",
        description: "",
        location: ""
    }
})

// Estado.
const store = useGeneralStore();
const { isAdmin, isLoggedIn } = storeToRefs(store);

// Métodos 
function getHotel(id) {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchGet(API_ROUTES.hotels.getOne(id))
        .then(json => {
            // Escoger variables que se van a usar de la respuesta y pasarlas al formulario.
            let { name, email, stars, description, location } = { ...json.body.hotel };
            form.value = { name, email, stars, description, location };
        });
}
function submit() {
    let request, redirectTo;
    if (isUpdate.value) {
        request = fetchPut(API_ROUTES.hotels.edit(route.params.id), { ...form.value });
        redirectTo = APP_ROUTES.hotels.view.link(route.params.id);
    } else {
        request = fetchPost(API_ROUTES.hotels.create, { ...form.value })
        redirectTo = APP_ROUTES.hotels.index;
    }
    request.then((response) => {
        if (response.status == 200)
            router.push(redirectTo);
    });
}
// onMounted
onMounted(() => {
    // Redirigir si no está autenticado o no es administrador.
    if (!isAdmin.value)
        router.push(APP_ROUTES.home);
    // Obtener hotel en base a ID.
    if (route.params.id) {
        isUpdate.value = true;
        getHotel(route.params.id);
    }

})
</script>