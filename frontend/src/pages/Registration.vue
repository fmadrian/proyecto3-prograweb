<template>
    <div class="container">
        <div class="flex flex-wrap align-items-center">
            <div class="col-12 md:col-6">
                <Card class="px-2">
                    <template #header>
                        <h5 class="text-3xl mb-3">{{ isUpdate ? "Modificar usuario" : "Crear usuario" }}</h5>
                    </template>
                    <template #content>
                        <div class="flex mb-3 align-items-center gap-5">
                            <label class="col-2" for="name">Nombre</label>
                            <InputText class="col-10" type="text" name="name" v-model="form.name" required />
                        </div>
                        <div class="flex mb-3 align-items-center gap-5">
                            <label class="col-2" for="email">Email</label>
                            <InputText class="col-10" type="email" name="email" v-model="form.email" required />
                        </div>
                        <div class="flex mb-3 align-items-center gap-5">
                            <label class="col-2" for="password">Contraseña</label>
                            <InputText class="col-10" type="password" name="password" v-model="form.password"
                                required />
                        </div>
                        <div v-if="isAdmin" class="flex mb-3 align-items-center gap-5">
                            <label class="col-2" for="is_admin">¿Es administrador?</label>
                            <Dropdown class="col-10" id="is_admin" name="is_admin" v-model="form.is_admin"
                                :options="selectOptions" optionLabel="label" optionValue="id"
                                placeholder="Es administrador" />
                        </div>
                        <Divider />
                        <div class="flex flex-column gap-3 mb-3">
                            <div class="flex md:flex-row mb-3">
                                <Button class="w-full" v-if="isUpdate" label="Modificar usuario" v-on:click="submit" />
                                <Button class="w-full" v-else label="Crear usuario" v-on:click="submit" />
                            </div>
                            <div v-if="!isLoggedIn" class="mt-3 text-center">
                                <router-link :to="APP_ROUTES.login">Ingresa a tu cuenta</router-link>
                            </div>
                        </div>
                    </template>
                </Card>
            </div>
            <img class="hidden md:block md:col-6 p-8 h-max" src="/images/login.jpg">
        </div>
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useGeneralStore } from '../stores/generalStore';
import { API_ROUTES } from '../utils/ApiRoutes';
import { APP_ROUTES } from '../utils/AppRoutes';
import { fetchGet, fetchPost, fetchPut } from '../utils/Fetch';
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
// Data
const form = ref({
    name: "", // Nombre.
    email: "", // Correo.
    password: "", // Contraseña.
    is_admin: "0"
}
);
const selectOptions = ref([
    { label: 'Si', value: 1 },
    { label: 'No', value: 0 }]
);
const user_id = ref(1);
const isUpdate = ref(false);
// Router.
const router = useRouter();
const route = useRoute();

// Estado
const store = useGeneralStore();
const { user, isLoggedIn, isAdmin } = storeToRefs(store);

// Métodos 
function submit() {
    if (!isUpdate.value)
        fetchPost(API_ROUTES.auth.register, { ...form.value })
            .then((response) => {
                if (response.status == 200)
                    router.push(APP_ROUTES.home);
            });
    else
        fetchPost(API_ROUTES.users.edit(user_id.value), { ...form.value })
            .then((response) => {
                if (response.status == 200)
                    router.push(APP_ROUTES.users.index);
            });
}

function getUser(id) {
    // Almacenar usuario en estado y volver a inicio.
    fetchGet(API_ROUTES.users.getOne(id))
        .then(json => {
            if (json.status === 200) {
                let { name, email } = { ...json.body.queried_user };
                let is_admin = json.body.queried_user.is_admin ? '1' : '0';

                form.value = { name, email, is_admin };
            }
        });
}

// onMounted
onMounted(() => {
    // No permitir a otros usuarios ver la información si no es propia o no son administradores
    if (route.params.id) {
        isUpdate.value = true;
        user_id.value = route.params.id;
        if (!isLoggedIn.value || (user.value.id !== user_id.value && !isAdmin.value)) {
            router.push(APP_ROUTES.home);
        }
        getUser(user_id.value);
    }

})

</script>