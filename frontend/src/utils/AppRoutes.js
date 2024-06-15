export const APP_ROUTES = {
    home: "/",
    login: "/login",
    register: "/register",
    hotels: {
        index: "/hotels",
        create: "/hotels/manage",
        edit:
        {
            component: `/hotels/manage/:id`,
            link: (id) => `/hotels/manage/${id}`
        },
        view:
        {
            component: `/hotels/:id`,
            link: (id) => `/hotels/${id}`
        }
    },
    rooms: {
        index: "/rooms",
        create: "/rooms/manage",
        edit:
        {
            component: `/rooms/manage/:id`,
            link: (id) => `/rooms/manage/${id}`
        },
        view:
        {
            component: `/rooms/:id`,
            link: (id) => `/rooms/${id}`
        }
    },
    users: {
        index: "/users",
        edit:
        {
            component: `/users/edit/:id`,
            link: (id) => `/users/edit/${id}`
        },
        view:
        {
            component: `/users/:id`,
            link: (id) => `/users/${id}`
        }
    }
}