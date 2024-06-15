<template>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">{{ isUpdate ? "Modificar usuario" : "Crear usuario" }}</h5>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="email">Nombre</label>
                                <input class="form-control" type="text" name="name" v-model="form.name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="email" name="email" v-model="form.email" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div>
                                <label class="form-label" for="password">Contraseña</label>
                                <input class="form-control " type="password" name="password" v-model="form.password"
                                    required>
                            </div>
                        </div>
                        <div v-if="isAdmin" class="row mb-3">
                            <div>
                                <label class="form-label" for="is_admin">¿Es administrador?</label>
                                <select class="form-select" id="is_admin" name="is_admin" v-model="form.is_admin">
                                    <option value="0" selected>No</option>
                                    <option value="1" selected>Si</option>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="d-grid">
                                <input class="btn btn-primary" type="button" v-on:click="submit"
                                    :value="isLoggedIn ? 'Actualizar usuario' : 'Crear usuario'">
                            </div>
                            <div v-if="!isLoggedIn" class="mt-3  text-center">
                                <a href="/login">Ingresa a tu cuenta</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <img class="d-none d-md-block col-md-6" src="/images/login.jpg">
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
const form = defineModel("form", {
    default: {
        name: "", // Nombre.
        email: "", // Correo.
        password: "", // Contraseña.
        is_admin: "0"
    }
});
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