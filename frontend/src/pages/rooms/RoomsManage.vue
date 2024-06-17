<template>
    <div class="container grid">
        <div class="grid col-12 md:col-6">
            <Card>
                <template #header>
                    <h5 class="text-2xl">{{ isUpdate ? 'Modificar habitación' : 'Crear habitación' }}</h5>
                    <span>Digite la información del formulario, por favor.</span>
                </template>
                <template #content>
                    {{ form }}
                    <Divider />
                    <div class="flex mb-3 align-items-center gap-5">
                        <label class="col-2" for="name">Nombre:</label>
                        <InputText class="col-10" name="name" v-model="form.name" required />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5">
                        <label class="col-2" for="hotel_id">Hotel:</label>
                        <Dropdown class="col-10" v-model="form.hotel_id" :options="hotels" optionLabel="name"
                            optionValue="id" placeholder="Hotel" />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5">
                        <label class="col-2" for="price">Precio:</label>
                        <InputNumber class="col-10" name="price" v-model="form.price" :min="0" showButtons required />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5">
                        <label class="col-2" for="beds">Camas:</label>
                        <InputNumber class="col-10" name="beds" v-model="form.beds" :min="0" showButtons
                            :useGrouping="false" required />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5">
                        <label class="col-2" for="bathrooms">Baños:</label>
                        <InputNumber class="col-10" name="bathrooms" v-model="form.bathrooms" :min="0" showButtons
                            :useGrouping="false" required />
                    </div>
                    <div class="flex mb-3 align-items-center gap-5">
                        <label class="col-2" for="bathrooms">Extras:</label>
                        <Textarea class="col-10" rows="5" cols="30 " name="additional" v-model="form.additional"
                            required />
                    </div>
                    <Divider />
                    <div class="flex flex-column md:flex-row mb-3">
                        <Button class="w-full" v-if="isUpdate" label="Modificar habitación" v-on:click="submit" />
                        <Button class="w-full" v-else label="Crear habitación" v-on:click="submit" />
                    </div>
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
import { fetchGet, fetchPut, fetchPost } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

// Router.
const route = useRoute();
const router = useRouter();

// Data.
const isUpdate = ref(false);
const form = ref({

    name: "",
    hotel_id: "",
    price: "",
    beds: "",
    bathrooms: "",
    extras: ""

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