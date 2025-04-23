export async function request(method, url, data = {}) {
    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
        ...(method === 'get' ? {} : { body: JSON.stringify(data) }) // The spread operator (...) is used to conditionally include the body property in the request object. If the method is get, the body property is not included (since GET requests do not have a request body). Otherwise, the data object is converted to a JSON string using JSON.stringify and assigned to the body property.
    });
    if (response.status >= 200 && response.status < 300) { // OR response.ok
        return response.json();
    }
    throw await response.json();
}

export function get(url) {
    return request('get', url)
}

export function post(url, data) {
    return request('post', url, data)
}