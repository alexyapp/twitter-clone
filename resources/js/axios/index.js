import axios from 'axios';
import store from '../store';
import * as authApi from '../api/auth';

const instance = axios.create({
    baseURL: 'http://localhost/api/v1',
});

async function refreshAccessToken() {
    try {
        const response = await authApi.refresh();
        const { data: { access_token } } = response;
        return access_token;
    } catch (error) {
        
    }
}

instance.interceptors.request.use(
    async config => {
        let token = localStorage.getItem('token');
        if (token) {
            config.headers = { 
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
            };
        }
        return config;
    },
    error => {
        Promise.reject(error);
    }
);

instance.interceptors.response.use(
    response => response, 
    function (error) {
        const originalRequest = error.config;
        // if (error.response.status === 403 && !originalRequest._retry) {
        //     originalRequest._retry = true;
        //     // const access_token = await refreshAccessToken();            
        //     axios.defaults.headers.common['Authorization'] = 'Bearer ' + access_token;
        //     return instance(originalRequest);
        // }
        return Promise.reject(error);

        // return new Promise(async (resolve, reject) => {
        //     if (error.status === 401 && error.config && !error.config.__isRetryRequest) {
        //         // await refreshAccessToken();
        //         await store.dispatch('logout');
        //     }
        //     throw error;
        // });
    }
);

export default instance;