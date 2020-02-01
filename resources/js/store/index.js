import Vue from 'vue'
import Vuex from 'vuex'

import News from './modules/news'
import Profile from './modules/profile'
import Author from './modules/author'

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        News,
        Profile,
        Author,
    }
});