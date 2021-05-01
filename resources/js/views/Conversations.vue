<template>
    <b-container fluid class="px-0 conversations-container">
        <b-row class="mx-0">
            <b-col cols="3" class="px-0">
                <b-list-group class="rounded-0">
                    <b-list-group-item
                        v-for="conversation in conversations.data"
                        :key="conversation.id"
                        button
                        @click="loadMessages(conversation.id)"
                        class="border-right-0"
                        :active="conversationId == conversation.id">
                        <div>
                            <div>
                                <div mb-2>
                                    <div class="d-flex align-items-center">
                                        <b-img 
                                            :src="conversation.lastMessage.author.avatar"
                                            class="thumbnail mr-2"
                                            rounded="circle"></b-img>
                                        <div>
                                            <p class="mb-0 font-weight-bold">{{ conversation.lastMessage.author.name }}</p>
                                            <small class="mb-0">{{ conversation.lastMessage.created_at }}</small>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 last-message-content">{{ conversation.lastMessage.content }}</p>
                            </div>
                        </div>
                    </b-list-group-item>
                </b-list-group>
            </b-col>

            <b-col 
                cols="9"
                class="border border-right-0 p-0 messages-container">
                <div 
                    class="p-3"
                    :style="{height: 'calc(100vh - (86px + 62px))', overflowY: 'auto'}"
                    ref="messagesWrapper">
                    <div 
                        v-for="message in messages"
                        :key="message.id"
                        class="mb-2">
                            <div class="w-50" :class="{'ml-auto': message.author.id == $store.state.user.id}">
                                <div class="d-flex align-items-center">
                                <b-img class="thumbnail mr-2" :src="message.author.avatar" rounded="circle"></b-img>
                                <p class="mb-0 font-weight-bold">
                                    <a @click="e => goToProfile(e, message.author.id)" href="#">{{ message.author.name }}</a>
                                </p>
                            </div>
                            <div class="message-content">
                                <p class="mb-0">{{ message.content }}</p>
                                <div class="d-flex align-items-end flex-column">
                                    <small class="text-muted">{{ message.created_at }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div 
                    v-if="messages.length"
                    class="reply-form-container">
                    <reply-form 
                        :conversation-id="conversationId"
                        @reply-sent="onReplySent"></reply-form>
                </div>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import conversationsApi from '../api/conversations';
import ReplyForm from '../components/ReplyForm';
import { toastMixin } from '../mixins';

export default {
    data() {
        return {
            conversations: {},
            messages: [],
            conversationId: null,
        }
    },

    components: {
        ReplyForm,
    },

    mixins: [
        toastMixin,
    ],

    async mounted() {
        const { data: conversations } = await conversationsApi.getAll();
        this.conversations = {...this.conversations, ...conversations};
    },

    created() {
        window.Echo.private(`users.${this.$store.state.user.id}`)
            .notification((notification) => {
                if (notification.type == 'App\\Notifications\\NewMessage') {
                    const { message } = notification;

                    let index = this.conversations.data.findIndex(conversation => conversation.id == message.conversation_id);
                    this.conversations.data[index].lastMessage = {...this.conversations.data[index].lastMessage, ...message};
                    
                    if (this.conversationId == message.conversation_id) {
                        this.messages.push(message);
                    } else {
                        this.conversations.data[index].messages.push(message);
                    }

                    this.$nextTick(() => {
                        const { messagesWrapper } = this.$refs
                        messagesWrapper.scrollTop = messagesWrapper.scrollHeight;
                        this.makeToast('You have a new message', 'New Message', 'primary');
                    });
                }
            });
    },

    methods: {
        loadMessages(conversationId) {
            this.conversationId = conversationId;
            let index = this.conversations.data.findIndex(conversation => conversation.id == conversationId);

            if (index > -1) {
                this.messages = this.conversations.data[index].messages;

                this.$nextTick(() => {
                    const { messagesWrapper } = this.$refs
                    messagesWrapper.scrollTop = messagesWrapper.scrollHeight;
                });
            }
        },

        goToProfile(e, userId) {
            e.preventDefault();
            this.$router.push({name: 'profile', params: { userId }});
        },

        onReplySent(message) {
            this.messages.push(message);

            let index = this.conversations.data.findIndex(conversation => conversation.id == this.conversationId);
            this.conversations.data[index].lastMessage = {...this.conversations.data[index].lastMessage, ...message};

            this.$nextTick(() => {
                const { messagesWrapper } = this.$refs
                messagesWrapper.scrollTop = messagesWrapper.scrollHeight
            });
        }
    }
}
</script>

<style>
    .last-message-content, .message-content {
        margin-left: calc(30px + 0.5rem);
    }

    .conversations-container {
        margin-top: -1rem;
    }

    .messages-container {
        height: calc(100vh - 62px);
    }
</style>