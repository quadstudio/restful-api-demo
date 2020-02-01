const state = {
    author: null,
    authorStatus: null
};
const getters = {
    author: state => state.author,

    authorStatus: state => ({
        author: state.authorStatus
    }),
};

const actions = {
    fetchAuthor({commit, dispatch}, authorId) {
        commit('setAuthorStatus', 'loading');
        axios
            .get('/api/v1/authors/' + authorId)
            .then(res => {
                commit('setAuthor', res.data);
                commit('setAuthorStatus', 'success');
            })
            .catch(error => {
                commit('setAuthorStatus', 'error');
            });
    },
};

const mutations = {
    setAuthor(state, author) {
        state.author = author;
    },
    setAuthorStatus(state, authorStatus) {
        state.authorStatus = authorStatus;
    }
};

export default {state, getters, actions, mutations};