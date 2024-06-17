<template>
    <div class="container">
        <div class="flex flex-column md:flex-row align-items-center">
            <div class="col-12 md:col-6">
                <h2 class="text-5xl">Gestión de usuarios</h2>
            </div>
        </div>
        <div class="col">
            <h5 class="text-xl">Todos los {{ users.length }} usuarios:</h5>
        </div>
        <div class="col-12">
            <span class="text-center" v-if="users.length === 0">No hay...</span>
            <DataTable v-else :value="users" selectionMode="single" scrollable scrollHeight="300px">
                <Column sortable field="name" header="Nombre"></Column>
                <Column sortable field="email" header="Correo"></Column>
                <Column sortable header="¿Es administrador?">
                    <template #body="user">
                        <span>{{ user.data.is_admin === "1" ? "Si" : "No" }}</span>
                    </template>
                </Column>
                <Column header="">
                    <template #body="user">
                        <div class="flex gap-1">
                            <router-link :to="APP_ROUTES.users.view.link(user.data.id)">
                                <Button label="Ver" severity="success" />
                            </router-link>
                            <router-link v-if="isAdmin" :to="APP_ROUTES.users.edit.link(user.data.id)">
                                <Button label="Editar" severity="primary" />
                            </router-link>
                            <Button label="Eliminar" v-if="isAdmin" v-on:click="deleteUser(user.data.id)"
                                severity="danger" />
                        </div>
                    </template>

                </Column>
            </DataTable>
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