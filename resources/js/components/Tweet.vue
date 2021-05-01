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

        <div class="tweet-content mt-3">
            <div v-if="editing">
                <tweet-form 
                    :tweet-data="tweetData"
                    @tweet-updated="onTweetUpdated"></tweet-form>
            </div>

            <div v-else>
                <h5>{{ tweetData.content }}</h5>
            </div>
        </div>
    </div>
</template>

<script>
import ActionButtons from './ActionButtons';
import TweetForm from './TweetForm';
import tweetsApi from '../api/tweets';
import { toastMixin, errorHandlerMixin } from '../mixins';

export default {
    data() {
        return {
            editing: false,
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
                    // TODO: handle error
                });
        },

        onTweetUpdated(tweet) {
            this.editing = false;
            this.$emit('tweet-updated', tweet);
        },

        goToProfile(e) {
            e.preventDefault();
            this.$router.push({ name: 'profile', params: { userId: this.tweetData.author.id } });
        }
    }
}
</script>

<style>
    .tweet-content {
        margin-left: calc(30px + 0.5rem);
    }
</style>