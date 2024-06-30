import Vue from 'vue';
import VueRouter from 'vue-router';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import Toast from 'vue-toastification';
import store from './store';
import './plugins/vuetify';
import App from './App';
import routes from './route/routes';

import './../sass/app.scss'
import 'vue-toastification/dist/index.css';

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(VueRouter);
Vue.use(Toast);

const router = new VueRouter({
    mode: 'history',
    routes: routes.routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth || false)) {
        if (!localStorage.getItem('user-token')) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export { router };

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
});
