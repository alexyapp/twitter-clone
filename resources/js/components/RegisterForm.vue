<template>
    <b-card title="Register">
        <b-card-text>
            <b-form @submit="onSubmit">
                <b-form-group
                    id="input-group-1"
                    label="Name:"
                    label-for="input-1"
                >
                    <b-form-input
                        id="input-1"
                        v-model="form.name.value"
                        type="text"
                        placeholder="Enter name"
                        required
                        :state="form.name.valid"
                    ></b-form-input>

                    <b-form-invalid-feedback :state="form.name.valid">
                        {{ form.name.errorMessage }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group 
                    v-show="shouldShowAvatarPreview"
                    label="Avatar Preview:">
                    <div>
                        <div class="mb-1">
                            <a @click="avatarPreview = ''" href="#">Delete</a>
                        </div>
                        <b-img 
                            thumbnail
                            :src="avatarPreview" 
                            alt="Avatar Preview"
                            class="avatar-preview"></b-img>

                        <b-form-invalid-feedback :state="form.avatar.valid">
                            {{ form.avatar.errorMessage }}
                        </b-form-invalid-feedback>
                    </div>
                </b-form-group>

                <b-form-group
                    v-show="!shouldShowAvatarPreview"
                    id="input-group-2"
                    label="Avatar (Optional):"
                    label-for="input-2">
                    <b-button
                        variant="primary"
                        @click="$refs.uploadAvatar.$refs.input.click()">Upload Avatar</b-button>
                    <b-form-file
                        id="input-2"
                        v-model="form.avatar.value"
                        :state="form.avatar.valid"
                        placeholder="Choose a file or drop it here..."
                        @change="onFileChange"
                        ref="uploadAvatar"
                        v-show="false"
                    ></b-form-file>
                </b-form-group>

                <b-form-group
                    id="input-group-3"
                    label="Email address:"
                    label-for="input-3"
                >
                    <b-form-input
                        id="input-3"
                        v-model="form.email.value"
                        type="email"
                        placeholder="Enter email"
                        required
                        :state="form.email.valid"
                    ></b-form-input>

                    <b-form-invalid-feedback :state="form.email.valid">
                        {{ form.email.errorMessage }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    id="input-group-4"
                    label="Password:"
                    label-for="input-4"
                >
                    <b-form-input
                        id="input-4"
                        v-model="form.password.value"
                        type="password"
                        placeholder="Enter password"
                        required
                        :state="form.password.valid"
                    ></b-form-input>

                    <b-form-invalid-feedback :state="form.password.valid">
                        {{ form.password.errorMessage }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group
                    id="input-group-5"
                    label="Confirm Password:"
                    label-for="input-5"
                >
                    <b-form-input
                        id="input-5"
                        v-model="form.password_confirmation.value"
                        type="password"
                        placeholder="Confirm password"
                        required
                        :state="form.password_confirmation.valid"
                    ></b-form-input>

                    <b-form-invalid-feedback :state="form.password_confirmation.valid">
                        {{ form.password_confirmation.errorMessage }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-button 
                    type="submit" 
                    variant="success" 
                    :disabled="loading || !isFormValid">Register</b-button>
                <span>or</span>
                <b-button 
                    :to="{name: 'login'}" 
                    variant="primary" 
                    :disabled="loading">Login</b-button>
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
                name: {
                    value: '',
                    valid: null,
                    errorMessage: '',
                },
                avatar: {
                    value: null,
                    valid: true,
                    errorMessage: '',
                },
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
                password_confirmation: {
                    value: '',
                    valid: null,
                    errorMessage: '',
                },
            },
            loading: false,
            avatarPreview: null,
        };
    },

    mixins: [
        formValidationMixin
    ],

    computed: {
        shouldShowAvatarPreview() {
            return !!this.avatarPreview;
        }
    },

    watch: {
        'form.name.value': function(value) {
            this.form.name.value = value;
            if (this.minLength(value, 3)) {
                this.setValidity(true, 'name');
                this.setErrorMessage('', 'name');
            } else {
                this.setValidity(false, 'name');
                this.setErrorMessage('Please enter at least 3 characters', 'name');
            }
        },

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
                this.setErrorMessage('Please enter at least 8 characters', 'password');
            }
        },

        'form.password_confirmation.value': function(value) {
            this.form.password_confirmation.value = value;
            if (this.isSame(value, this.form.password.value)) {
                this.setValidity(true, 'password_confirmation');
                this.setErrorMessage('', 'password_confirmation');
            } else {
                this.setValidity(false, 'password_confirmation');
                this.setErrorMessage('Password does not match', 'password_confirmation');
            }
        },
    },

    methods: {
        async onSubmit(e) {
            e.preventDefault();
            
            this.loading = true;
            const {
                name: { value: name },
                email: { value: email },
                password: { value: password },
                password_confirmation: { value: password_confirmation },
                avatar: { value: avatar },
            } = this.form;

            const formData = new FormData();

            formData.append('avatar', avatar);
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('password_confirmation', password_confirmation);
            
            try {
                await this.$store.dispatch('register', formData);
                // this.$router.push({ name: 'home' });
            } catch (error) {
                const { response } = error;
                console.log({
                    response
                })
                if (response.status == 422) {
                    const { errors } = response.data;
                    for (let key in errors) {
                        this.setValidity(false, key);
                        this.setErrorMessage(errors[key][0], key);
                    }
                }
            } finally {
                this.loading = false;
            }
        },

        onFileChange(e) {
            this.setValidity(true, 'avatar');
            this.setErrorMessage('', 'avatar');

            const file = e.target.files[0];
            this.avatarPreview = URL.createObjectURL(file);
        },
    }
}
</script>

<style>
    .avatar-preview {
        object-fit: cover;
        object-position: center;
        width: 150px;
        height: 150px !important;
    }
</style>