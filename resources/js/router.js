import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import About from './components/About.vue'
import Login from "./components/Authentification/Login.vue";
import Register from "./components/Authentification/Register.vue";
import Hotels from "./components/Hotels.vue";
import HotelShow from "./components/HotelShow.vue";
import Profile from "./components/Authentification/Profile.vue";

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
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { layout:false },
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: { layout:false },
    },
    {
        path: '/hotels',
        name: 'hotels',
        component: Hotels,
    },
    {
        path: '/hotels/:id',
        name: 'hotels.show',
        component: HotelShow,
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
