import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import About from './components/About.vue'
import Login from "./components/Authentification/Login.vue";
import Register from "./components/Authentification/Register.vue";
import Hotels from "./components/Hotels.vue";
import HotelShow from "./components/HotelShow.vue";
import Profile from "./components/Authentification/Profile.vue";
import Tours from "./components/Tours.vue";
import TourShow from "./components/TourShow.vue";
import Insurance from "./components/Insurance.vue";
import Contacts from "./components/Contacts.vue";
import Blog from "./components/Blog.vue";

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
    },
    {
        path: '/tours',
        name: 'tours',
        component: Tours,
    },
    {
        path: '/tours/:id',
        name: 'tours.show',
        component: TourShow,
    },
    {
        path: '/insurance',
        name: 'insurance',
        component: Insurance
    },
    {
        path: '/contacts',
        name: 'contacts',
        component: Contacts
    },
    {
        path: '/blog',
        name: 'blog',
        component: Blog
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        return { top: 0 };
    }
});

export default router;
