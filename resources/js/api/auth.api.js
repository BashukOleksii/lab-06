import { api, sessionHttp } from './http';

async function initializeCsrf() {
    await sessionHttp.get('/sanctum/csrf-cookie');
}

export const authApi = {
    async login(credentials) {
        await initializeCsrf();

        await sessionHttp.post('/login', credentials);
    },

    async currentUser() {
        const response = await api.get('/user');

        return response.data;
    },

    async logout() {
        await initializeCsrf();

        await sessionHttp.post('/logout');
    },
};