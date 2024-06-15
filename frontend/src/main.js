// src/main.js

// Vue
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
// Utils.
import { APP_ROUTES } from './utils/AppRoutes';
// Pinia
import { createPinia } from 'pinia';
// PrimeVue
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/aura-light-green/theme.css';
// PrimeFlex
import '/node_modules/primeflex/primeflex.css';
// Componentes aplicación.
import Home from './pages/Home.vue';
import NotFound from './pages/NotFound.vue';
import Login from './pages/Login.vue';
import Registration from './pages/Registration.vue';
import HotelsIndex from './pages/hotels/HotelsIndex.vue';
import HotelsShow from './pages/hotels/HotelsShow.vue';
import HotelsManage from './pages/hotels/HotelsManage.vue';
import RoomsIndex from './pages/rooms/RoomsIndex.vue';
import RoomsManage from './pages/rooms/RoomsManage.vue';
import RoomsShow from './pages/rooms/RoomsShow.vue';
import UsersIndex from './pages/users/UsersIndex.vue';
import UsersShow from './pages/users/UsersShow.vue';
const routes = [

    // Home
    { path: APP_ROUTES.home, component: Home },
    // Login
    { path: APP_ROUTES.login, component: Login },
    { path: APP_ROUTES.register, component: Registration },
    // Hoteles
    { path: APP_ROUTES.hotels.index, component: HotelsIndex },
    { path: APP_ROUTES.hotels.view.component, component: HotelsShow },
    { path: APP_ROUTES.hotels.create, component: HotelsManage },
    { path: APP_ROUTES.hotels.edit.component, component: HotelsManage },
    // Habitaciones
    { path: APP_ROUTES.rooms.index, component: RoomsIndex },
    { path: APP_ROUTES.rooms.create, component: RoomsManage },
    { path: APP_ROUTES.rooms.edit.component, component: RoomsManage },
    { path: APP_ROUTES.rooms.view.component, component: RoomsShow },
    // Usuarios
    { path: APP_ROUTES.users.index, component: UsersIndex },
    { path: APP_ROUTES.users.view.component, component: UsersShow },
    { path: APP_ROUTES.users.edit.component, component: Registration },
    // Not found component.
    // Path not found.
    { path: '/:pathMatch(.*)*', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

const app = createApp(App);

app.use(router);
// PrimeVue
app.use(PrimeVue);
// Pinia.
app.use(createPinia());

app.mount('#app');