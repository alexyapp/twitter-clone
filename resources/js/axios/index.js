import axios from 'axios';
import { router } from '../router';
import store from '../store';

const instance = axios.create({
    baseURL: 'http://localhost/api/v1',
});

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
        // const originalRequest = error.config;
        // if (error.response.status === 403 && !originalRequest._retry) {
        //     originalRequest._retry = true;
        //     // const access_token = await refreshAccessToken();            
        //     axios.defaults.headers.common['Authorization'] = 'Bearer ' + access_token;
        //     return instance(originalRequest);
        // }

        if (error.response && error.response.status == 401 && error.response.data.status && error.response.data.status == 'Token is Expired') {
            store.commit('logout');
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            router.push({name: 'login'});
        }

        return Promise.reject(error);
    }
);

export default instance;