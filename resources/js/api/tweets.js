import axios from '../axios';

const get = (params = {}) => axios.get('/tweets', { params });
const create = (data) => axios.post('/tweets', data);
const destroy = (id) => axios.delete(`/tweets/${id}`);
const update = (tweet) => axios.put(`/tweets/${tweet.id}`, tweet);

export default {
    get,
    create,
    destroy,
    update
}
