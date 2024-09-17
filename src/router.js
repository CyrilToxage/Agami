// router.js

import { createRouter, createWebHistory } from 'vue-router'; // Import createRouter and createWebHistory
import Admin from './components/Admin.vue';
import Mentions from './components/Mentions.vue';
import Home from './components/Home.vue';
import store from './store.js';
import Connexion from './components/Connexion.vue';

const routes = [

    {
        path: '/home',
        name: 'Home',
        component: Home
    },
    {
        path: '/connexion',
        name: 'Connexion',
        component: Connexion
    },
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/mentions',
        name: 'Mentions',
        component: Mentions
    },
    {
        path: '/admin',
        name: 'Admin',
        component: Admin,
        meta: { requiresAuth: true } // Indique que cette page nécessite une authentification
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.state.loggedIn) {
            next('/connexion'); // Redirige vers la page de connexion si l'utilisateur n'est pas authentifié
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;