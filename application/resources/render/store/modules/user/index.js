export default {
    namespaced: true,
    state: {
        userData: null,
    },
    mutations: {
        SET_USER_DATA(state, userData) {
            console.log('Setting user data:', userData);
            state.userData = userData;
        },
    },
    actions: {
        initializeUserData({ commit }) {
            const storedUserData = localStorage.getItem('userData');
            if (storedUserData) {
                commit('SET_USER_DATA', JSON.parse(storedUserData));
            }
        },
        setUserData({ commit }, userData) {
            commit('SET_USER_DATA', userData);
        }
    },
    getters: {
        userData: (state) => state.userData,
    },

};
