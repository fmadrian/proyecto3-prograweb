<template>
    <div class="container">
        <div class="flex flex-wrap align-items-center">
            <div class="col-12 md:col-6">
                <Card class="px-2">
                    <template #header>
                        <h5 class="text-3xl px-5">Login</h5>
                    </template>
                    <template #content>
                        <div class="flex mb-3 align-items-center gap-5 pr-8 bg-red-100 px-3">
                            <p class="font-semibold" v-if="error">Usuario o contraseña inválida intente de nuevo.</p>
                        </div>
                        <div class="flex mb-3 align-items-center gap-5 pr-8">
                            <label class="col-3" for="email">Email</label>
                            <InputText class="col-9" type="email" name="email" v-model="form.email" required />
                        </div>
                        <div class="flex mb-3 align-items-center gap-5 pr-8">
                            <label class="col-3" for="password">Contraseña</label>
                            <InputText class="col-9" type="password" name="password" v-model="form.password" required />
                        </div>
                        <Divider />
                        <div class="flex flex-column gap-3 mt-5 mb-3">
                            <div class="flex md:flex-row mb-1">
                                <Button class="w-full" label="Login" v-on:click="login" severity="primary" />
                            </div>
                            <div class="text-center">
                                <router-link :to="APP_ROUTES.register">Crear usuario</router-link>
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
import { useGeneralStore } from '../stores/generalStore';
import { useRouter } from 'vue-router'
import { API_ROUTES } from '../utils/ApiRoutes';
import { APP_ROUTES } from '../utils/AppRoutes';
import { fetchGet, fetchPost } from '../utils/Fetch';
import { storeToRefs } from 'pinia';
import { onMounted, ref } from 'vue';
// Router.
const router = useRouter();

// Data.

let error = ref(false);
const form = ref({
    email: "", // Correo.
    password: "" // Contraseña.
})

// Estado.
const store = useGeneralStore();
const { isLoggedIn } = storeToRefs(store);
// Métodos
let login = function () {
    error.value = false;
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchPost(API_ROUTES.auth.login, { ...form.value })
        .then(json => {
            if (json !== null && json.status == 200) {
                return fetchGet(API_ROUTES.auth.info)
            }
            else {
                error.value = true;
            }
        }).then((json) => {
            if (json && json.status == 200) {
                store.setUser({ ...json.body });
                router.push(APP_ROUTES.home);
            }
            else {
                error.value = true;
            }
        });
}
// onMounted
onMounted(() => {
    // Redirigir si no está autenticado o no es administrador.
    if (isLoggedIn.value)
        router.push(APP_ROUTES.home);
});


</script>