import axios from '../axios';

const getAll = () => axios.get('conversations');
const reply = ({ conversationId, content }) => axios.post(`/conversations/${conversationId}`, { content });

export default {
    getAll,
    reply,
}