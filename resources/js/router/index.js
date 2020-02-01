import Vue from 'vue'
import VueRouter from 'vue-router'
import NewsIndex from '../pages/News/Index'
import NewsShow from '../pages/News/Show'
import NewsAdd from '../pages/News/Add'
import NewsEdit from '../pages/News/Edit'
import AuthorShow from '../pages/Author/Show'
import NotFound from '../pages/NotFound'
import Home from '../pages/Home'

Vue.use(VueRouter);


export default new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home,
            meta: {title: 'Home'}
        },
        {
            path: '/news',
            name: 'news.index',
            component: NewsIndex,
            meta: {title: 'NewsIndex'},
        },
        {
            path: '/news/add',
            name: 'news.add',
            component: NewsAdd,
            meta: {title: 'NewsAdd'},
        },
        {
            path: '/news/:newsId',
            name: 'news.show',
            component: NewsShow,
            meta: {title: 'NewsShow'},
        },
        {
            path: '/news/:newsId/edit',
            name: 'news.edit',
            component: NewsEdit,
            meta: {title: 'NewsEdit'},
        },
        {
            path: '/authors/:authorId',
            name: 'authors.show',
            component: AuthorShow,
            meta: {title: 'AuthorShow'},
        },
        {
            path: '/404',
            name: '404',
            component: NotFound,
        }, {
            path: '*',
            redirect: '/404'
        }
    ]
});