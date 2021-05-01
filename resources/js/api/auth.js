import axios from '../axios';

export const fetchUser = () => axios.get('/auth/me');
export const login = ({ email, password }) => axios.post('/auth/login', { email, password });
export const register = formData => axios.post('/auth/register', formData, { headers: { 'content-type': 'multipart/form-data' }});
export const logout = () => axios.post('/auth/logout');
export const refresh = () => axios.post('/auth/refresh');

export default {
    fetchUser,
    login,
    register,
    refresh,
}