import Vue from 'vue'
import Vuelidate from 'vuelidate'
import router from './router'
import App from './components/App'
import store from './store'
Vue.use(Vuelidate);

require('./bootstrap');

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router,
    store
});
