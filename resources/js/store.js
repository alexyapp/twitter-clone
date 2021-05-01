import Vue from 'vue';
import Vuex from 'vuex';
import * as authApi from './api/auth';
import axios from './axios'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        status: '',
        token: localStorage.getItem('token') || '',
        user: localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')) : null,
    },
    mutations: {
        auth_request(state){
            state.status = 'loading';
        },
        auth_success(state, { access_token, user }){
            state.status = 'success';
            state.token = access_token;
            state.user = user;
        },
        auth_error(state){
            state.status = 'error';
        },
        logout(state){
            state.status = '';
            state.token = '';
            state.user = null;
        },
    },
    actions: {
        login({ commit }, credentials){
            return new Promise(async (resolve, reject) => {
                commit('auth_request');
                try {
                    const { data: { access_token } } = await authApi.login(credentials);
                    localStorage.setItem('token', access_token);

                    const { data: { data: user } } = await authApi.fetchUser();
                    localStorage.setItem('user', JSON.stringify(user));
                    commit('auth_success', { access_token, user });
                    resolve();
                } catch (error) {
                    commit('auth_error');
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    reject(error);
                }
            });
        },

        register({ commit }, formData) {
            return new Promise(async (resolve, reject) => {
                commit('auth_request');
                try {
                    const { data: { access_token } } = await authApi.register(formData);
                    localStorage.setItem('token', access_token);
                    
                    const { data: { data: user } } = await authApi.fetchUser();
                    localStorage.setItem('user', JSON.stringify(user));

                    commit('auth_success', { access_token, user });
                    resolve();
                } catch (error) {
                    commit('auth_error', error);
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    reject(error);
                }
            });
        },

        logout({ commit }){
            return new Promise(async (resolve, reject) => {
                try {
                    await authApi.logout();
                    commit('logout');
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    resolve();
                } catch (error) {
                    reject(error);
                }
            });
        }
    },
    getters : {
        isLoggedIn: state => !!state.token,
    },
});