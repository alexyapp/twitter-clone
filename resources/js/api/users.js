import axios from '../axios';

export const getOne = id => axios.get(`/users/${id}`);

export default {
    getOne,
}