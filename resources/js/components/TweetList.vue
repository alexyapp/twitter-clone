<template>
    <div>
        <div class="mb-3">
            <tweet-form @tweet-created="onTweetCreated"></tweet-form>
        </div>
        <tweet
            v-for="tweet in tweets.data"
            :key="tweet.id"
            :tweet-data="tweet"
            @tweet-updated="onTweetUpdated"
            @tweet-deleted="onTweetDeleted"
            @comment-created="onCommentCreated"
            @reply-created="onReplyCreated">
        </tweet>

        <div v-if="showShouldLoadMoreButton" class="text-center mb-3">
            <b-button @click="loadMoreTweets">Load More</b-button>
        </div>
    </div>
</template>

<script>
import Tweet from './Tweet';
import TweetForm from './TweetForm';
import tweetsApi from '../api/tweets';
import { toastMixin } from '../mixins';
import axios from '../axios';

export default {
    data() {
        return {
            tweets: {},
        }
    },

    mixins: [
        toastMixin,
    ],

    components: {
        Tweet,
        TweetForm,
    },

    computed: {
        showShouldLoadMoreButton() {
            if (this.tweets.links) {
                return !!this.tweets.links.next;
            }

            return false;
        }
    },

    async mounted() {
        try {
            const response = await tweetsApi.get();
            this.tweets = response.data;
        } catch (error) {
            // TODO: handle error
        }
    },

    methods: {
        onTweetCreated(tweet) {
            this.tweets.data = [tweet].concat(this.tweets.data);
        },

        onTweetUpdated(tweet) {
            let index = this.tweets.data.findIndex(t => t.id == tweet.id);
            this.tweets.data[index].content = tweet.content;
            this.makeToast('Tweet has been updated', 'Success', 'success');
        },

        onTweetDeleted(tweet) {
            let index = this.tweets.data.findIndex(t => t.id == tweet.id);
            this.tweets.data = [...this.tweets.data.slice(0, index), ...this.tweets.data.slice(index + 1)];
            this.makeToast('Tweet has been deleted', 'Success', 'success');
        },

        onCommentCreated({ comment, tweetId }) {
            let index = this.tweets.data.findIndex(tweet => tweet.id == tweetId);

            if (index > -1) {
                this.tweets.data[index].comments.push(comment);
            }
        },

        onReplyCreated({ reply, tweetId, parentCommentId }) {
            let index = this.tweets.data.findIndex(tweet => tweet.id == tweetId);

            if (index > -1) {
                let i = this.tweets.data[index].comments.findIndex(comment => comment.id == parentCommentId);
                
                if (i > -1) {
                    this.tweets.data[index].comments[i].replies.push(reply);
                    console.log('test', this.tweets.data[index].comments[i].replies);

                }
            }
        },

        async loadMoreTweets() {
            try {
                if (this.tweets.links.next) {
                    const { data: { data: tweets, links, meta } } = await axios.get(this.tweets.links.next);

                    this.tweets.data = [...this.tweets.data, ...tweets];
                    this.tweets.links = {...this.tweets.links, ...links};
                    this.tweets.meta = {...this.tweets.links, ...meta};

                    console.log(this.tweets)
                }
            } catch (error) {
                // TODO: handle error
            }
        }
    }
}
</script>

<style>

</style>