<template>
    <b-form @submit="onSubmit">
        <div class="d-flex">
            <div>
                <b-form-input
                    id="input-1"
                    v-model="form.query.value"
                    type="text"
                    placeholder="Search"
                    required
                    :disabled="loading"
                    :state="form.query.valid"
                    autofocus
                    class="rounded-0"
                ></b-form-input>
            </div>

            <div>
                <b-button 
                    type="submit"
                    :disabled="loading || !isFormValid">
                    <b-icon icon="search"></b-icon>
                </b-button>
            </div>
        </div>
    </b-form>
</template>

<script>
import { formValidationMixin, errorHandlerMixin, toastMixin } from '../mixins';
import tweetsApi from '../api/tweets';
import { bus } from '../app';

export default {
    data() {
        return {
            form: {
                query: {
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
        'form.query.value': function(value) {
            this.form.query.value = value;
            const key = 'query';
            if (this.minLength(value, 1)) {
                this.setValidity(true, key);
                this.setErrorMessage('', key);
            } else {
                this.setValidity(false, key);
                this.setErrorMessage('Search can\'t be blank', key);
            }
        },
    },

    methods: {
        async onSubmit(e) {
            e.preventDefault();
            this.loading = true;
            const { value: q } = this.form.query;
            try {
                const response = await tweetsApi.get({ q });
                bus.$emit('searchResults', response);
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