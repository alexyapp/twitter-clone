<template>
    <b-card :title="title">
        <b-card-text>
            <b-form @submit="onSubmit">
                <b-form-group
                    id="input-group-1"
                    :label="label"
                    label-for="input-1"
                >
                    <b-form-textarea
                        id="input-1"
                        v-model="form.content.value"
                        placeholder="Enter something..."
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
                        :disabled="loading || !isFormValid">{{ buttonText }}</b-button>
            </b-form>
        </b-card-text>
    </b-card>
</template>

<script>
import tweetsApi from '../api/tweets';
import { formValidationMixin, toastMixin } from '../mixins';

export default {
    data() {
        return {
            form: {
                content: {
                    value: '',
                    valid: null,
                    errorMessage: '',
                }
            },
            loading: false,
            title: 'Create Tweet',
            label: 'Say something:',
            buttonText: 'Create',
            tweetId: null,
        }
    },

    props: {
        tweetData: {
            type: Object,
            required: false,
        },
    },

    mixins: [
        formValidationMixin,
        toastMixin,
    ],

    mounted() {
        if (this.tweetData) {
            this.form.content.value = this.tweetData.content;
            this.title = '';
            this.label = '';
            this.buttonText = 'Update';
            this.tweetId = this.tweetData.id;
        }
    },

    watch: {
        'form.content.value': function(value) {
            const key = 'content';

            if (this.minLength(value, 3)) {
                this.setValidity(true, key);
                this.setErrorMessage('', key);
            } else {
                this.setValidity(false, key);
                this.setErrorMessage('Please enter at least three characters', key);
            }
        }
    },

    methods: {
        async onSubmit(e) {
            e.preventDefault();
            this.loading = true;
            const { content: { value: content } } = this.form;
            try {
                if (this.tweetId) {
                    const { data: { data: tweet } } = await tweetsApi.update({ content, id: this.tweetId });
                    this.$emit('tweet-updated', tweet);
                } else {
                    const { data: { data: tweet } } = await tweetsApi.create({ content });
                    this.$emit('tweet-created', tweet);
                }
            } catch (error) {
                if (error.response && error.response.data && error.response.data.message) {
                    this.makeToast(error.response.data.message, 'Error', 'danger');
                }
            } finally {
                this.loading = false;
            }
        }
    },
}
</script>

<style>

</style>