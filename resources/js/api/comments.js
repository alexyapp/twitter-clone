import axios from '../axios';

const create = ({ tweetId, content }) => axios.post(`/tweets/${tweetId}/comments`, { content });
const reply = ({ tweetId, commentId, content }) => axios.put(`/tweets/${tweetId}/comments/${commentId}`, { content });

export default {
    create,
    reply,
}