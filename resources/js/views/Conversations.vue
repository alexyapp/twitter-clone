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
                        class="border-right-0">
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
                                            <small class="mb-0 text-muted">{{ conversation.lastMessage.created_at }}</small>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0 text-muted last-message-content">{{ conversation.lastMessage.content }}</p>
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

    async mounted() {
        const { data: conversations } = await conversationsApi.getAll();
        this.conversations = {...this.conversations, ...conversations};
    },

    methods: {
        loadMessages(conversationId) {
            const { messagesWrapper } = this.$refs
            messagesWrapper.scrollTop = messagesWrapper.scrollHeight

            this.conversationId = conversationId;
            let index = this.conversations.data.findIndex(conversation => conversation.id == conversationId);

            if (index > -1) {
                this.messages = this.conversations.data[index].messages;
            }
        },

        goToProfile(e, userId) {
            e.preventDefault();
            this.$router.push({name: 'profile', params: { userId }});
        },

        onReplySent(message) {
            this.messages.push(message);
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