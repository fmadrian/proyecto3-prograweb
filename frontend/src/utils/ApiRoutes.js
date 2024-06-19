const url = "https://eif507-una-proyecto3-tema13.000webhostapp.com/api";
export const API_ROUTES = {
    "auth": {
        "login": `${url}/login`,
        "register": `${url}/register`,
        "logout": `${url}/logout`,
        "info": `${url}/info`
    },
    hotels: {
        get: `${url}/hotels`,
        getOne: (id) => `${url}/hotels/${id}`,
        delete: (id) => `${url}/hotels/${id}`,
        create: `${url}/hotels`,
        edit: (id) => `${url}/hotels/${id}`,
    },
    rooms: {
        get: `${url}/rooms`,
        getOne: (id) => `${url}/rooms/${id}`,
        delete: (id) => `${url}/rooms/${id}`,
        create: `${url}/rooms`,
        edit: (id) => `${url}/rooms/${id}`,
    },
    reservations: {
        create: `${url}/reservations`,
        delete: (id) => `${url}/reservations/${id}`,
    },
    users: {
        get: `${url}/users`,
        getOne: (id) => `${url}/users/${id}`,
        delete: (id) => `${url}/users/${id}`,
        edit: (id) => `${url}/update/${id}`,
    },
    search: `${url}/search`
};