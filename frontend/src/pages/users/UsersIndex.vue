<template>
    <div class="container">
        <div class="row">
            <h1>Gestión de usuarios</h1>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-striped table-primary">
                    <thead>
                        <tr>
                            <th scope="col">Nombre</th>
                            <th scope="col">Email</th>
                            <th scope="col">Es administrador</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users">
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.is_admin === "1" ? "Si" : "No" }}</td>
                            <td>
                                <router-link class="btn btn-success"
                                    :to="APP_ROUTES.users.view.link(user.id)">Ver</router-link>
                                <router-link v-if="isAdmin" class="btn btn-primary"
                                    :to="APP_ROUTES.users.edit.link(user.id)">Modificar</router-link>
                                <button v-if="isAdmin" type="button" class="btn btn-danger"
                                    v-on:click="deleteUser(user.id)"><i class='bx bx-trash'></i>Eliminar</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script setup>
import { useGeneralStore } from '../../stores/generalStore';
import { useRouter } from 'vue-router'
import { API_ROUTES } from '../../utils/ApiRoutes';
import { APP_ROUTES } from '../../utils/AppRoutes';
import { fetchGet, fetchDelete } from '../../utils/Fetch';
import { onMounted, ref } from 'vue';
import { storeToRefs } from 'pinia';

// Router.
const router = useRouter();

// Data.
const users = ref([]);

// Estado.
const store = useGeneralStore();
const { isAdmin } = storeToRefs(store);

// Métodos 
function getUsers() {
    // Almacenar usuario en estado y volver a inicio.
    // Hace request a /info para obtener información de usuario.
    fetchGet(API_ROUTES.users.get)
        .then(json => {
            if (json.status === 200)
                users.value = json.body.users;
        });
}

function deleteUser(id) {
    fetchDelete(API_ROUTES.users.delete(id))
        .then(json => {
            // Recargar hoteles
            if (json && json.status === 200)
                getUsers();
        });
}

// onMounted
onMounted(() => {
    if (!isAdmin.value) {
        router.push(APP_ROUTES.home);
    }
    getUsers();
})
</script>