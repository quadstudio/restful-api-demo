import router from '../../router';

const state = {
    news: null,
    newsShow: null,
    newsStatus: null,
    image: null
};

const getters = {
    news: state => state.news,
    newsShow: state => state.newsShow,
    newsStatus: state => state.newsStatus
};

const actions = {
    async fetchNews({commit, state}) {
        commit('setNewsStatus', 'loading');
        await axios
            .get('/api/v1/news')
            .then(res => {
                commit('setNews', res.data);
                commit('setNewsStatus', 'success');
            })
            .catch(error => {
                commit('setNewsStatus', 'error');
            })
    },
    async fetchAuthorNews({commit}, authorId) {
        commit('setNewsStatus', 'loading');
        await axios
            .get('/api/v1/authors/' + authorId + '/news')
            .then(res => {
                commit('setNews', res.data);
                commit('setNewsStatus', 'success');
            })
            .catch(error => {
                commit('setNewsStatus', 'error');
            });
    },
    async fetchSingleNews({commit, state}, newsId) {
        commit('setNewsStatus', 'loading');
        await axios
            .get('/api/v1/news/' + newsId)
            .then(res => {
                commit('setNewsShow', res.data);
                commit('setNewsStatus', 'success');
            })
            .catch(error => {
                commit('setNewsStatus', 'error');
            })
    },
    async storeSingleNews({dispatch, commit}, attributes) {
        commit('setNewsStatus', 'pending');
        axios
            .post('/api/v1/news', {data: {type: 'news', attributes}})
            .then(res => {
                router.push({name: 'news.show', params: {newsId: res.data.data.id}});
            })
            .catch(error => {
                commit('setNewsStatus', 'error');
            });
    },
    async updateNews({dispatch, commit}, {newsId, attributes}) {
        commit('setNewsStatus', 'pending');
        //console.log(attributes);
        await axios
            .patch('/api/v1/news/' + newsId, {data: {type: 'news', attributes}})
            .then(res => {
                console.log(res.data);
                //commit('setNews', res.data);
                commit('setNewsStatus', 'success');
            })
            .catch(error => {
                commit('setNewsStatus', 'error');
                console.log(error);
            });
    },
};

const mutations = {

    setNewsStatus(state, newsStatus) {
        state.newsStatus = newsStatus;
    },
    setNews(state, news) {
        state.news = news;
    },
    setNewsShow(state, newsShow) {
        state.newsShow = newsShow;
    },
};

export default {state, getters, actions, mutations};