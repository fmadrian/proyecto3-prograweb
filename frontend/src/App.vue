<!-- src/App.vue -->
<template>
    <app-header></app-header>
    <main class="container-fluid px-5 py-4">
        <router-view></router-view>
    </main>
    <app-footer></app-footer>
</template>

<script>
import AppHeader from './components/AppHeader.vue';
import AppFooter from './components/AppFooter.vue';

export default {
    data() {
        return {}
    },
    components: {
        AppHeader,
        AppFooter
    }
}
</script>