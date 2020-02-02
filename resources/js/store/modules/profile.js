const state = {
    user: null,
    userStatus: null
};
const getters = {
    authUser: state => state.user,
    userStatus: state => state.userStatus
};

const actions = {
    fetchProfile({commit, state}) {
        axios.get('/api/v1/profile')
            .then(res => res.data)
            .then(user => {
                commit('setProfile', user.data);
                commit('setProfileStatus', 'success');
            })
            .catch(error => {
                commit('setProfileStatus', 'error');
            });
    }
};

const mutations = {
    setProfileStatus(state, userStatus) {
        state.userStatus = userStatus;
    },
    setProfile(state, user) {
        state.user = user;
    }
};

export default {state, getters, actions, mutations};