<template>
    <div class="container">
        <h1>¡Bienvenido a InnovaGestión! {{ isLoggedIn ? user.name : '' }}</h1>
        <section class="mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-column row-gap-3">
                        <span class="block text-center mt-5 mb-5">Estoy buscando...</span>
                        <div class="px-8">
                            <Searchbar />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <Divider />
        <section class="mt-6">
            <header>
                <h3 class="text-2xl">Ayuda (preguntas frecuentes)</h3>
            </header>
            <Panel header="¿Cómo reservar una habitación?" toggleable collapsed>
                <div class="grid">
                    <div class="col-12 md:col-4">
                        <p>Pasos para reservar una habitación:</p>
                        <ol>
                            <li v-if="!isLoggedIn"><router-link :to="APP_ROUTES.login">Login</router-link>.</li>
                            <li><router-link :to="APP_ROUTES.hotels.index">Buscar el
                                    hotel</router-link>.</li>
                            <li><router-link :to="APP_ROUTES.rooms.index">Escoger una
                                    habitacion</router-link>.</li>
                            <li>Click en 'Reservar'.</li>
                        </ol>
                    </div>
                    <img class="block md:col-8 max-h-screen" src="/images/help1.png">
                </div>
            </Panel>
            <Panel header="¿Cómo eliminar una reservación?" toggleable collapsed>
                <div class="grid">
                    <div class="col-12 md:col-4">
                        <p>Pasos:</p>
                        <ul>
                            <li v-if="!isLoggedIn"><router-link class="" :to="APP_ROUTES.login">Login</router-link>.
                            </li>
                            <li><router-link :to="APP_ROUTES.users.view.link(user.id)">Ver mis
                                    reservaciones</router-link>.</li>
                            <li>Click en 'Eliminar' la reservación a eliminar.</li>
                        </ul>
                    </div>
                    <img class="block md:col-8" src="/images/help2.png">
                </div>
            </Panel>
            <Panel v-if="isAdmin" header="¿Cómo agregar una habitación?" toggleable collapsed>
                <div class="grid">
                    <div class="col-12 md:col-6">
                        <p>Pasos:</p>
                        <ul>
                            <li v-if="isLoggedIn"><router-link :to="APP_ROUTES.rooms.create">Ingresar a 'Añadir
                                    habitación'</router-link>.</li>
                            <li>Rellenar formulario.</li>
                            <li>Click en 'Guardar'.</li>
                        </ul>
                    </div>
                    <img class="block md:col-6" src="/images/help3.png">
                </div>
            </Panel>
        </section>
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import Searchbar from '../components/Searchbar.vue';
import { useGeneralStore } from '../stores/generalStore';
import { APP_ROUTES } from '../utils/AppRoutes';
// Estado
const store = useGeneralStore();
// Elementos de estado.
const { user, isLoggedIn, isAdmin } = storeToRefs(store);

</script>