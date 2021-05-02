<template>
    <div class="mb-3 border p-3">
        <div
            v-if="isOwner"
            class="d-flex flex-column align-items-end">

            <action-buttons
                @edit-button-clicked="editTweet"
                @delete-button-clicked="deleteTweet"></action-buttons>
        </div>

        <div class="d-flex align-items-center">
            <div class="mr-2">
                <b-img 
                    :src="tweetData.author.avatar" 
                    rounded="circle"
                    class="thumbnail"></b-img>
            </div>

            <div>
                <p class="mb-0 ">
                    <a @click="goToProfile" href="#">{{ tweetData.author.name }} {{ isOwner ? '(You)' : '' }}</a>
                </p>
                <small class="text-muted">{{ tweetData.created_at }}</small>
            </div>
        </div>

        <div class="tweet-content mt-2">
            <div v-if="editing">
                <tweet-form 
                    :tweet-data="tweetData"
                    @tweet-updated="onTweetUpdated"></tweet-form>
            </div>

            <div v-else>
                <h5>{{ tweetData.content }}</h5>
            </div>

            <div>
                <div class="mb-2">
                    <a @click="displayCommentForm" href="#">
                        <small>Comment</small>
                    </a>
                </div>

                <div
                    class="mb-4"
                    v-show="showCommentForm">
                    <comment-form
                        @comment-created="onCommentCreated"
                        :tweet-id="tweetData.id">
                    </comment-form>
                </div>

                <!-- Comments -->
                <div class="mt-4">
                    <div 
                        v-for="comment in tweetData.comments"
                        :key="comment.id"
                        class="border p-3 mb-4">
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="mr-2">
                                    <b-img 
                                        :src="comment.author.avatar" 
                                        rounded="circle"
                                        class="thumbnail"></b-img>
                                </div>

                                <div>
                                    <p class="mb-0 ">
                                        <a @click="goToProfile" href="#">{{ comment.author.name }} {{ isOwner ? '(You)' : '' }}</a>
                                    </p>
                                    <small class="text-muted">{{ comment.created_at }}</small>
                                </div>
                            </div>

                            <div class="comment-content mt-2">
                                <div>
                                    <h5>{{ comment.content }}</h5>
                                </div>

                                <div class="mb-2">
                                    <a @click="displayReplyForm" href="#">
                                        <small>Reply</small>
                                    </a>
                                </div>

                                <div
                                    class="mb-4"
                                    v-show="showReplyForm">
                                    <comment-form
                                        @comment-created="onCommentCreated"
                                        @reply-created="reply => onReplyCreated(reply, comment.id)"
                                        :tweet-id="tweetData.id"
                                        :comment-id="comment.id">
                                    </comment-form>
                                </div>

                                <!-- Replies -->
                                <div class="mt-4">
                                    <div 
                                        v-for="reply in comment.replies"
                                        :key="reply.id"
                                        class="mb-4 border p-3">
                                        <div>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <b-img 
                                                        :src="reply.author.avatar" 
                                                        rounded="circle"
                                                        class="thumbnail"></b-img>
                                                </div>

                                                <div>
                                                    <p class="mb-0 ">
                                                        <a @click="goToProfile" href="#">{{ reply.author.name }} {{ isOwner ? '(You)' : '' }}</a>
                                                    </p>
                                                    <small class="text-muted">{{ reply.created_at }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="reply-content mt-2">
                                            <div>
                                                <h5>{{ reply.content }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ActionButtons from './ActionButtons';
import TweetForm from './TweetForm';
import CommentForm from './CommentForm';
import tweetsApi from '../api/tweets';
import { toastMixin, errorHandlerMixin } from '../mixins';

export default {
    data() {
        return {
            editing: false,
            showCommentForm: false,
            showReplyForm: false,
        }
    },

    props: {
        tweetData: {
            type: Object,
            required: true
        }
    },

    mixins: [
        toastMixin,
        errorHandlerMixin,
    ],

    computed: {
        isOwner() {
            return this.$store.state.user.id == this.tweetData.author.id;
        }
    },

    components: {
        TweetForm,
        ActionButtons,
        CommentForm,
    },

    methods: {
        editTweet(e) {
            e.preventDefault();
            this.editing = true;
        },

        deleteTweet(e) {
            e.preventDefault();
            this.$bvModal.msgBoxConfirm('Are you sure you want to delete this tweet?', {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true
            })
                .then(value => {
                    if (value) {
                        tweetsApi.destroy(this.tweetData.id)
                            .then(response => {
                                const { data: tweet } = response.data;
                                this.$emit('tweet-deleted', tweet);
                            })
                            .catch(error => {
                                this.handleGeneralError(error);
                            });
                    }
                })
                .catch(error => {
                    this.handleGeneralError(error);
                });
        },

        onTweetUpdated(tweet) {
            this.editing = false;
            this.$emit('tweet-updated', tweet);
        },

        goToProfile(e) {
            e.preventDefault();
            this.$router.push({ name: 'profile', params: { userId: this.tweetData.author.id } });
        },

        onCommentCreated(comment) {
            this.$emit('comment-created', { comment, tweetId: this.tweetData.id });
        },

        onReplyCreated(reply, parentCommentId) {
            this.$emit('reply-created', { reply, tweetId: this.tweetData.id, parentCommentId });
        },

        displayCommentForm(e) {
            e.preventDefault();
            this.showCommentForm = true;
        },

        displayReplyForm(e) {
            e.preventDefault();
            this.showReplyForm = true;
        }
    }
}
</script>

<style>
    .tweet-content, .comment-content, .reply-content {
        margin-left: calc(30px + 0.5rem);
    }
</style>