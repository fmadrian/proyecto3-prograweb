
export async function fetchPost(url, body) {
    return fetch(url, {
        method: "POST",
        credentials: 'include', // Incluir para retener credenciales enviadas por backend en 'Set-Cookie'.
        headers:
        {
            'Accept': 'application/json',  // Petición devuelve un JSON.
            'Content-Type': 'application/json',// Solo en POST. Estamos enviando un JSON. 
        }, body: JSON.stringify(body)
    }).then(res => (res.ok ? res.json() : null))
        .catch(error => console.error(error));
}

export async function fetchGet(url) {
    return fetch(url, {
        method: "GET",
        credentials: 'include', // Incluir para retener credenciales enviadas por backend en 'Set-Cookie'.
        headers:
        {
            'Accept': 'application/json',  // Petición devuelve un JSON.
        }
    }).then(res => {
        return res.ok ? res.json() : null
    })
        .catch(error => console.error(error));
}

export async function fetchDelete(url) {

    // Suplantación.
    return fetch(url, {
        method: 'POST',
        credentials: 'include', // Incluir para retener credenciales enviadas por backend en 'Set-Cookie'.
        headers:
        {
            'X-HTTP-Method-Override': 'DELETE', // Sobreescribe el POST.
            'Accept': 'application/json',  // Petición devuelve un JSON.

        }
    }).then(res => {
        return res.ok ? res.json() : null
    })
        .catch(error => console.error(error));

    /*
        // Sin suplantación
        return fetch(url, {
            method: "DELETE",
            credentials: 'include', // Incluir para retener credenciales enviadas por backend en 'Set-Cookie'.
            headers:
            {
                'Accept': 'application/json',  // Petición devuelve un JSON.
            }
        }).then(res => {
            return res.ok ? res.json() : null
        })
        */
}

export async function fetchPut(url, body) {
    // Suplantación.
    return fetch(url, {
        method: 'POST',
        credentials: 'include', // Incluir para retener credenciales enviadas por backend en 'Set-Cookie'.
        headers:
        {
            'X-HTTP-Method-Override': 'PUT', // Sobreescribe el POST.
            'Accept': 'application/json',  // Petición devuelve un JSON.
            'Content-Type': 'application/json'// Solo en POST. Estamos enviando un JSON. 
        }, body: JSON.stringify(body)
    }).then(res => {
        return res.ok ? res.json() : null
    }).catch(error => console.error(error));

    /*
        return fetch(url, {
            method: "PUT",
            credentials: 'include', // Incluir para retener credenciales enviadas por backend en 'Set-Cookie'.
            headers:
            {
                'Accept': 'application/json',  // Petición devuelve un JSON.
                'Content-Type': 'application/json',// Solo en POST. Estamos enviando un JSON. 
            }, body: JSON.stringify(body)
        }).then(res => (res.ok ? res.json() : null))
            .catch(error => console.error(error));
            */

}
