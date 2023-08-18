export default async (url, id, token, exited = null) => {
    const response = await fetch(`${url}`, {
        method: 'POST', body: JSON.stringify({id, exited, '_token': token}),
    })

    return await response.json();
}