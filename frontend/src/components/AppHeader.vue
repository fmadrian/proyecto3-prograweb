<template>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-secondary">
            <div class="container-fluid">
                <span class="navbar-brand"><router-link class="text-reset text-decoration-none"
                        :to="APP_ROUTES.home">InnovaGestión</router-link></span>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar"
                    aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav flex-grow-1">
                        <div class="d-flex justify-content-between flex-grow-1 ">
                            <div class="d-flex justify-content-evenly">
                                <li class="nav-item d-block">
                                    <router-link class="nav-link text-reset text-decoration-none"
                                        :to="APP_ROUTES.hotels.index">Hoteles</router-link>
                                </li>
                                <li class="nav-item d-block">
                                    <router-link class="nav-link text-reset text-decoration-none"
                                        :to="APP_ROUTES.rooms.index">Habitaciones</router-link>
                                </li>
                                <li v-if="isAdmin" class="nav-item d-block">
                                    <a class="nav-link"><router-link class="text-reset text-decoration-none"
                                            :to="APP_ROUTES.users.index">Usuarios</router-link></a>
                                </li>
                            </div>

                            <div class="d-flex">
                                <Searchbar />
                            </div>
                            <div class="d-flex justify-content-evenly">
                                <li v-if="!isLoggedIn" class="nav-item d-block">
                                    <a class="nav-link"><router-link class="text-reset text-decoration-none"
                                            :to="APP_ROUTES.login">Login</router-link></a>
                                </li>

                                <li v-if="isLoggedIn" class="nav-item d-block">
                                    <a class="nav-link"><router-link class="text-reset text-decoration-none"
                                            :to="APP_ROUTES.users.view.link(user.id)">Mis reservaciones ({{ user.name
                                            }})</router-link></a>
                                </li>
                                <li v-if="isLoggedIn" class="nav-item d-block">
                                    <a class="nav-link"><router-link class="text-reset text-decoration-none"
                                            :to="APP_ROUTES.users.edit.link(user.id)">Editar mi
                                            informacion</router-link></a>
                                </li>
                                <li v-if="isLoggedIn" class="nav-item d-block">
                                    <button class="nav-link text-danger" v-on:click="logout">Cerrar sesión</button>
                                </li>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
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

// Router (permite redirigir)
const router = useRouter();
// Estado
const store = useGeneralStore();
// Data

// Obtiene objeto 'user' de estado y lo conecta a constante 'user'
const { user, isLoggedIn, isAdmin } = storeToRefs(store);

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