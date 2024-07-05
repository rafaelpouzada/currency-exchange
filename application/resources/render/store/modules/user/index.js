export default {
    namespaced: true,
    state: {
        userData: null,
    },
    mutations: {
        SET_USER_DATA(state, userData) {
            state.userData = userData;
        },
    },
    actions: {
        setUserData({ commit }, userData) {
            commit('SET_USER_DATA', userData);
        }
    },
    getters: {
        userData: (state) => state.userData,
    },
};
