import axios from '../axios';

export const fetchUser = () => axios.get('/auth/me');
export const login = ({ email, password }) => axios.post('/auth/login', { email, password });
export const register = ({ name, email, password, password_confirmation }) => axios.post('/auth/register', { name, email, password, password_confirmation });
export const logout = () => axios.post('/auth/logout');
export const refresh = () => axios.post('/auth/refresh');

export default {
    fetchUser,
    login,
    register,
    refresh,
}