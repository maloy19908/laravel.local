import {createWebHashHistory,createRouter} from 'vue-router';
import Home from './views/Home.vue';
import Towns from './views/Towns.vue';
import Prices from './views/Prices.vue';
import Districts from './views/Districts.vue';
import Login from './views/Login.vue';

const routes = [{
        path: '/',
        component: Home
    },
    {
        path: '/prices',
        component: Prices
    },
    {
        path: '/towns',
        component: Towns
    },
    {
        path: '/districts',
        component: Districts
    },
    {
        path: '/login',
        component: Login
    },
]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
})

export default router