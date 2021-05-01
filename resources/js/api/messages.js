import axios from '../axios';

const send = ({ receiverId, content }) => axios.post(`/users/${receiverId}/messages`, { content });

export default {
    send,
}