<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <form>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Login</h5>
                            <div class="row">
                                <p v-if="error">Usuario o contraseña inválida intente de nuevo.</p>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" for="email">Email</label>
                                    <input v-model="form.email" class="form-control" type="email" name="email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="form-label" for="password">Contraseña</label>
                                    <input v-model="form.password" class="form-control " type="password"
                                        name="password">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="d-grid">
                                    <input class="btn btn-primary" type="button" value="Login" v-on:click="login">
                                </div>
                                <div class="mt-3 text-center">
                                    <router-link :to="APP_ROUTES.register">Crear usuario</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <img class="d-none d-md-block col-md-6" src="/images/login.jpg">
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
import { onMounted, toRaw } from 'vue';
// Router.
const router = useRouter();

// Data.

let error = defineModel("error", { default: false })
const form = defineModel("form", {
    default: {
        email: "", // Correo.
        password: "" // Contraseña.
    }
})

// Estado.
const store = useGeneralStore();
const { user, isLoggedIn, isAdmin } = storeToRefs(store);
// Métodos
let login = function () {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchPost(API_ROUTES.auth.login, { ...form.value })
        .then(json => {
            if (json !== null)
                return fetchGet(API_ROUTES.auth.info)
        }).then((json) => {
            if (json && json.status == 200) {
                store.setUser({ ...json.body });
                router.push(APP_ROUTES.home);
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