<template>
    <header>
        <nav>
            <Menubar :model="items">
                <template class="w-max" #item="{ item }">
                    <span class="block" v-if="item.order === 0"><router-link :to="APP_ROUTES.home">{{ item.label
                            }}</router-link></span>
                    <router-link v-if="item.order === 1" class="block" :to="APP_ROUTES.hotels.index">{{
                        item.label }}</router-link>
                    <router-link v-if="item.order === 2" class="block" :to="APP_ROUTES.rooms.index">{{
                        item.label }}</router-link>
                    <router-link v-if="item.order === 3 && isAdmin" class="block" :to="APP_ROUTES.users.index">{{
                        item.label }}</router-link>
                    <div v-if="item.order === 4" class="flex">
                        <Searchbar />
                    </div>
                    <router-link v-if="item.order === 5 && !isLoggedIn" class="block" :to="APP_ROUTES.login">{{
                        item.label }}</router-link>
                    <router-link v-if="item.order === 6 && isLoggedIn" class="block"
                        :to="APP_ROUTES.users.view.link(user.id)">{{ item.label }} ({{
                            user.name
                        }})</router-link>
                    <router-link v-if="item.order === 7 && isLoggedIn" class="block"
                        :to="APP_ROUTES.users.edit.link(user.id)"></router-link>
                    <button v-if="item.order === 8 && isLoggedIn" class="block text-danger"
                        v-on:click="logout"></button>
                </template>
            </Menubar>
        </nav>
    </header>
</template>
<script setup>
import { storeToRefs } from 'pinia';
import { APP_ROUTES } from '../utils/AppRoutes';
import { API_ROUTES } from '../utils/ApiRoutes';
import { useRouter } from 'vue-router';
import { useGeneralStore } from '../stores/generalStore';
import { fetchGet } from '../utils/Fetch';
import Searchbar from './Searchbar.vue';
import { ref } from 'vue';

// Router (permite redirigir)
const router = useRouter();
// Estado
const store = useGeneralStore();
// Data

// Obtiene objeto 'user' de estado y lo conecta a constante 'user'
const { user, isLoggedIn, isAdmin } = storeToRefs(store);

let items = ref([
    { order: 0, label: 'InnovaGestión' },
    { order: 1, label: 'Hoteles' },
    { order: 2, label: 'Habitaciones' },
    { order: 3, label: 'Usuarios' },
    { order: 4, label: '' },
    { order: 5, label: 'Login' },
    { order: 6, label: 'Mis reservaciones' },
    { order: 7, label: 'Editar mi informacion' },
    { order: 8, label: 'Cerrar sesión' },
])

// Métodos
let logout = function () {
    // Cerrar sesión y volver a inicio.
    fetchGet(API_ROUTES.auth.logout)
        .then((json) => {
            if (json !== null && json.status == 200) {
                store.logout();
                router.push(APP_ROUTES.home)
            }
        }
        );
}

</script>