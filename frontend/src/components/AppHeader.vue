<template>
    <header>
        <nav>
            <Menubar :model="items">
                <template #item="{ item, props }">
                    <router-link class="text-sm" v-if="item.route && item.display" v-slot="{ href, navigate }"
                        :to="item.route">
                        <a :href="href" v-bind="props.action" @click="navigate">
                            <span>{{ item.label }}</span>
                        </a>
                    </router-link>
                </template>
                <template #end>
                    <div class="flex gap-2">
                        <div class="hidden md:block">
                            <Searchbar />
                        </div>
                        <Button v-if="isLoggedIn" severity="danger" outlined v-on:click="logout"
                            label="Cerrar sesión" />
                        <router-link v-else class="block" :to="APP_ROUTES.login"><Button severity="primary" outlined
                                label="Login" /></router-link>
                    </div>

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

const items = ref([
    { label: 'InnovaGestión', route: APP_ROUTES.home, display: true },
    { label: 'Hoteles', route: APP_ROUTES.hotels.index, display: true },
    { label: 'Habitaciones', route: APP_ROUTES.rooms.index, display: true },
    { label: 'Usuarios', route: APP_ROUTES.users.index, display: isAdmin },
    { label: 'Mis reservaciones', route: APP_ROUTES.users.view.link(user.id), display: isLoggedIn },
    { label: 'Editar mi información', route: APP_ROUTES.users.edit.link(user.id), display: isLoggedIn },
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