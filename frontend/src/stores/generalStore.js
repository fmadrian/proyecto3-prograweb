import { defineStore } from 'pinia'

// Crea un store con el nombre 'general'
export const useGeneralStore = defineStore('general', {
    // Se define estado aquí
    state: () => (
        { user: {}, isLoggedIn: false, isAdmin: false }
    ),
    // Como y qué leer del estado
    getters: {
        getUser: (state) => state.user,
        getIsLoggedIn: (state) => state.isLoggedIn,
        getIsAdmin: (state) => state.isAdmin
    },
    // Cómo modificar el estado
    actions: {
        setUser(user) {
            this.user = { ...user };
            this.isLoggedIn = true;
            this.isAdmin = user.is_admin === "1";
        },
        logout() {
            this.user = {};
            this.isLoggedIn = false;
            this.isAdmin = false;
        }
    }
});

