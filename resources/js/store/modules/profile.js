const state = {
    user: null,
    userStatus: null
};
const getters = {
    authUser: state => state.user
};

const actions = {
    fetchProfile({commit, state}) {
        axios.get('/api/v1/profile')
            .then(res => res.data)
            .then(user => {
                commit('setProfile', user.data);
            })
            .catch(error => {
                console.log('Unable to fetch the user from the server');
            });
    }
};

const mutations = {
    setProfile(state, profile) {
        state.user = profile;
    }
};

export default {state, getters, actions, mutations};