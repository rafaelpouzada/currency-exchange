import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        userData: null,
    },
    mutations: {
        SET_USER_DATA(state, userData) {
            state.userData = userData;
            localStorage.setItem('user', JSON.stringify(userData));
        },
    },
    actions: {},
    modules: {},
});
