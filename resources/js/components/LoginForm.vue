<template>
    <b-card title="Login">
        <b-card-text>
            <b-form @submit="onSubmit">
                <b-form-group
                    id="input-group-1"
                    label="Email address:"
                    label-for="input-1"
                >
                    <b-form-input
                        id="input-1"
                        v-model="form.email.value"
                        type="email"
                        placeholder="Enter email"
                        required
                        :disabled="loading"
                        :state="form.email.valid"
                        autofocus
                    ></b-form-input>
                    <b-form-invalid-feedback :state="form.email.valid">
                        {{ form.email.errorMessage }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    id="input-group-2"
                    label="Password:"
                    label-for="input-2"
                >
                    <b-form-input
                        id="input-2"
                        v-model="form.password.value"
                        type="password"
                        placeholder="Enter password"
                        required
                        :disabled="loading"
                        :state="form.password.valid"
                    ></b-form-input>

                    <b-form-invalid-feedback :state="form.password.valid">
                        {{ form.password.errorMessage }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-button 
                    type="submit" 
                    variant="success" 
                    :disabled="loading || !isFormValid">Login</b-button>
                <span>or</span>
                <b-button 
                        :to="{name: 'register'}" 
                        variant="primary" 
                        :disabled="loading">Register</b-button>
            </b-form>
        </b-card-text>
    </b-card>
</template>

<script>
import { formValidationMixin } from '../mixins';

export default {
    data() {
        return {
            form: {
                email: {
                    value: '',
                    valid: null,
                    errorMessage: '',
                },
                password: {
                    value: '',
                    valid: null,
                    errorMessage: '',
                },
            },
            loading: false,
        };
    },

    mixins: [
        formValidationMixin,
    ],

    watch: {
        'form.email.value': function(value) {
            this.form.email.value = value;
            if (this.isValidEmail(value)) {
                this.setValidity(true, 'email');
                this.setErrorMessage('', 'email');
            } else {
                this.setValidity(false, 'email');
                this.setErrorMessage('Invalid email address', 'email');
            }
        },

        'form.password.value': function(value) {
            this.form.password.value = value;
            if (this.minLength(value, 8)) {
                this.setValidity(true, 'password');
                this.setErrorMessage('', 'password');
            } else {
                this.setValidity(false, 'password');
                this.setErrorMessage('Invalid password', 'password');
            }
        }
    },

    methods: {
        async onSubmit(e) {
            e.preventDefault();
            this.loading = true;
            const { email: { value: email }, password: { value: password } } = this.form;

            try {
                await this.$store.dispatch('login', { email, password });

                this.$router.push({ name: 'home' });
            } catch (error) {
                const { response } = error;
                if (response && response.status == 422) {
                    const { errors } = response.data;
                    for (let key in errors) {
                        this.setValidity(false, key);
                        this.setErrorMessage(errors[key][0], key);
                    }
                }
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style>

</style>