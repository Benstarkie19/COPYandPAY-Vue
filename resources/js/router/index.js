import {createWebHistory, createRouter} from "vue-router";

import Register from '../pages/Register';
import Login from '../pages/Login';

import Purchase from '../pages/Purchase';
import Confirmation from '../pages/Confirmation';

import Transactions from '../pages/Transactions';
import ShowTransaction from '../components/ShowTransaction';
import Refund from '../components/Refund';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Login
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'purchase',
        path: '/purchase',
        component: Purchase
    },
    {
        name: 'confirmation',
        path: '/confirmation',
        component: Confirmation
    },
    {
        name: 'transactions',
        path: '/transactions',
        component: Transactions
    },
    {
        name: 'showtransaction',
        path: '/transactions/show/:id',
        component: ShowTransaction
    },
    {
        name: 'refund',
        path: '/refund',
        component: Refund
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
