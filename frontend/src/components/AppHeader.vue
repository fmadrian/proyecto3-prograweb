<template>
    <header>
        <nav>
            <Menubar :model="items">
                <template #item="{ item, props }">
                    <router-link class="no-underline" v-if="item.route && item.display" v-slot="{ href, navigate }"
                        :to="item.route" :class="{
                            'font-bold': item.isLogo
                        }">
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
                        <Button v-if="isLoggedIn" type="button" @click="toggle" aria-haspopup="true"
                            aria-controls="overlay_menu" severity="info" rounded>{{ user.name }}</Button>
                        <router-link v-else class="block" :to="APP_ROUTES.login"><Button severity="contrast" rounded
                                label="Login" /></router-link>
                        <Menu ref="menu" id="overlay_menu" :model="userMenu" :popup="true">
                            <template #item="{ item, props }">
                                <router-link class="no-underline" v-if="item.route && item.display"
                                    v-slot="{ href, navigate }" :to="item.route">
                                    <a :href="href" v-bind="props.action" @click="navigate">
                                        <span>{{ item.label }}</span>
                                    </a>
                                </router-link>
                            </template>
                            <template #end>
                                <Button class="w-full text-left" v-if="isLoggedIn" severity="danger" outlined
                                    v-on:click="logout" label="Cerrar sesión" />
                            </template>
                        </Menu>
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
import { ref, toRaw } from 'vue';

// Router (permite redirigir)
const router = useRouter();
// Estado
const store = useGeneralStore();
// Data

// Obtiene objeto 'user' de estado y lo conecta a constante 'user'
const { user, isLoggedIn, isAdmin } = storeToRefs(store);

const items = ref([
    { label: 'InnovaGestión', route: APP_ROUTES.home, display: true, isLogo: true },
    { label: 'Hoteles', route: APP_ROUTES.hotels.index, display: true },
    { label: 'Habitaciones', route: APP_ROUTES.rooms.index, display: true },
    { label: 'Mis reservaciones', route: APP_ROUTES.users.view.link(user.value.id), display: isLoggedIn },
    { label: 'Usuarios', route: APP_ROUTES.users.index, display: isAdmin },

])

const userMenu = ref([
    { label: 'Editar mi información', route: APP_ROUTES.users.edit.link(user.value.id), display: isLoggedIn },
]);

// Métodos
let logout = function () {
    // Cerrar sesión y volver a inicio.
    fetchGet(API_ROUTES.auth.logout)
        .then((json) => {
            if (json !== null && json.status == 200) {
                // Cambiar estado.
                store.logout();
                // Cerrar menú.
                menu.value.toggle();
                // Redirigir.
                router.push(APP_ROUTES.home)
            }
        }
        );
}
const menu = ref();
const toggle = (event) => {
    menu.value.toggle(event);
};

</script>