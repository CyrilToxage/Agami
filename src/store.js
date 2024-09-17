// store/index.js
import { createStore } from 'vuex';

export default createStore({
    state: {
        loggedIn: false,
        userInfo: { }
    },
    mutations: {
        setLoggedIn(state, value) {
            state.loggedIn = value;
        },
        setUserInfo(state, value) {
            state.userInfo = value;
        },
    }
});