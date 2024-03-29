import './bootstrap';
import Vue from 'vue';
import { router } from './router';
import App from './views/App';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import store from './store';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

export const bus = new Vue();

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router,
    store,
});
