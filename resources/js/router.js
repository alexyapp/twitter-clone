import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from './views/Home';
import Login from './views/Login';
import Register from './views/Register';
import store from './store';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            guest: true,
        },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            guest: true,
        },
    }
];

export const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!store.getters.isLoggedIn) {
            next({ name: 'login' });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (store.getters.isLoggedIn) {
            next({ name: 'home' });
        } else {
            next();
        }
    } else {
        next();
    }
});
  