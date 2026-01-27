import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import About from './components/About.vue'
import Login from "./components/Authentification/Login.vue";

const routes = [
    {
        path: '/',
        name: 'index',
        component: Home
    },
    {
        path: '/about',
        name: 'about',
        component: About
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { layout:false },
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
