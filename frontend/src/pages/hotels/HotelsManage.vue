<template>
    <div class="container grid">

        <div class="grid col-12 md:col-6">
            <Card class="col-12">
                <template #header>
                    <div>
                        <h5 class="text-2xl px-5">{{ isUpdate ? 'Modificar hotel' : 'Crear hotel' }}</h5>
                        <span>Digite la información del formulario, por favor.</span>
                    </div>
                </template>
                <template #content>
                    <Divider />
                    <div class="flex mb-3 align-items-center gap-5 pr-8">
                        <label class="col-3" for="name">Nombre:</label>
                        <InputText class="col-9" type="text" name="name" v-model="form.name" required />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5 pr-8">
                        <label class="col-3" for="stars">Estrellas:</label>
                        <InputNumber class="col-9" name="stars" v-model="form.stars" required :useGrouping="false"
                            :min="0" :max="10" />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5 pr-8">
                        <label class="col-3" for="location">Ubicación:</label>
                        <InputText class="col-9" type="text" name="location" v-model="form.location" required />
                    </div>
                    <div class="flex mb-8 align-items-center gap-5 pr-8">
                        <label class="col-3" for="description">Descripción:</label>
                        <Textarea class="col-9" rows="5" cols="30 " name="description" v-model="form.description"
                            required />
                    </div>
                    <Divider />
                    <div class="flex flex-column md:flex-row mb-3">
                        <Button class="w-full" v-if="isUpdate" label="Modificar hotel" v-on:click="submit" />
                        <Button class="w-full" v-else label="Crear hotel" v-on:click="submit" />
                    </div>
                </template>
            </Card>
        </div>
        <img class="hidden md:block md:col-6 p-8 h-max" src="/images/login.jpg">

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
const { isAdmin } = storeToRefs(store);

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