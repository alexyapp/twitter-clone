<template>
  <b-form @submit="onSubmit">
      <b-form-group
        id="input-group-1"
      >
        <b-form-textarea
            id="input-1"
            v-model="form.content.value"
            placeholder="Write a comment..."
            rows="3"
            max-rows="6"
            :state="form.content.valid"
            :disabled="loading"
            required
            autofocus
        ></b-form-textarea>

        <b-form-invalid-feedback :state="form.content.valid">
            {{ form.content.errorMessage }}
        </b-form-invalid-feedback>
      </b-form-group>

      <b-button
        type="submit"
        variant="primary" 
        :disabled="loading || !isFormValid">Add Comment</b-button>
  </b-form>
</template>

<script>
import { formValidationMixin, errorHandlerMixin, toastMixin } from '../mixins';
import commentsApi from '../api/comments';

export default {
    props: {
        tweetId: {
            type: Number,
            required: true,
        },
        commentId: {
            type: Number,
            required: false,
        }
    },

    data() {
        return {
            form: {
                content: {
                    value: '',
                    valid: null,
                    errorMessage: ''
                },
            },
            loading: false,
        }
    },

    mixins: [
        formValidationMixin,
        errorHandlerMixin,
        toastMixin,
    ],

    watch: {
        'form.content.value': function(value) {
            this.form.content.value = value;
            const key = 'content';
            if (this.minLength(value, 3)) {
                this.setValidity(true, key);
                this.setErrorMessage('', key);
            } else {
                this.setValidity(false, key);
                this.setErrorMessage('Please enter at least 3 characters', key);
            }
        },
    },

    methods: {
        async onSubmit(e) {
            e.preventDefault();
            this.loading = true;
            const { value: content } = this.form.content;
            const { tweetId, commentId } = this;
            try {
                let commentData = { content, tweetId };
                if (commentId) {
                    commentData = {...commentData, commentId};
                    const { data: { data: reply } } = await commentsApi.reply(commentData);
                    this.$emit('reply-created', reply);
                } else {
                    console.log('bar')

                    const { data: { data: comment } } = await commentsApi.create(commentData);
                    this.$emit('comment-created', comment);
                }
                
                this.makeToast('Comment added', 'Success', 'success');
            } catch (error) {
                this.handleGeneralError(error);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style>

</style>