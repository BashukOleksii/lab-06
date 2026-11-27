import axios from 'axios';

const config = {
    withCredentials: true,

    withXSRFToken: true,

    headers: {
        Accept: 'application/json',
    },

    timeout: 15000,
};

export const sessionHttp = axios.create({
    ...config,

    baseURL: '/',
});

export const api = axios.create({
    ...config,

    baseURL: '/api',
});