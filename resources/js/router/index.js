import {
    createRouter,
    createWebHistory,
} from 'vue-router';

import AppLayout from '../layouts/AppLayout.vue';

import HomePage from '../pages/HomePage.vue';

import BikeRackIndexPage
    from '../pages/bike-racks/BikeRackIndexPage.vue';

import NotFoundPage from '../pages/NotFoundPage.vue';

const routes = [
    {
        path: '/',

        component: AppLayout,

        children: [
            {
                path: '',
                name: 'home',
                component: HomePage,
            },

            {
                path: 'bike-racks',
                name: 'bike-racks.index',
                component: BikeRackIndexPage,
            },

            {
                path: ':pathMatch(.*)*',
                name: 'not-found',
                component: NotFoundPage,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory('/app/'),

    routes,
});

export default router;