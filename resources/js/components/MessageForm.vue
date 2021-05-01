<template>
  <b-form @submit="onSubmit">
      <b-form-group
        id="input-group-1"
      >
        <b-form-textarea
            id="input-1"
            v-model="form.content.value"
            placeholder="Write a message..."
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
        :disabled="loading || !isFormValid">Send</b-button>
  </b-form>
</template>

<script>
import { formValidationMixin, errorHandlerMixin, toastMixin } from '../mixins';
import messagesApi from '../api/messages';

export default {
    props: {
        receiverId: {
            type: Number,
            required: true,
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
            const { receiverId } = this;
            try {
                const { data: { data: message } } = await messagesApi.send({ content, receiverId });
                this.$emit('message-sent', message);
                this.makeToast('Message is sent', 'Success', 'success');
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