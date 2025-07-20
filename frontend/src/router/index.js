import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';
import PageA from '../components/PageA.vue';
import PageB from '../components/PageB.vue';
import Stats from '../components/Stats.vue';
import Reports from '../components/Reports.vue';

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/page-a', component: PageA, meta: { requiresAuth: true } },
    { path: '/page-b', component: PageB, meta: { requiresAuth: true } },
    { path: '/stats', component: Stats, meta: { requiresAuth: true, requiresAdmin: true } },
    { path: '/reports', component: Reports, meta: { requiresAuth: true, requiresAdmin: true } }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const user = JSON.parse(localStorage.getItem('user'));

    if (to.meta.requiresAuth && !user) {
        return next('/login');
    }

    if (to.meta.requiresAdmin && user?.role !== 'admin') {
        return next('/page-a');
    }

    next();
});

export default router;