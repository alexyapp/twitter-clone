<template>
    <b-container>
        <b-row class="vh-100" align-v="center">
            <b-col cols="6" offset="3">
                <login-form></login-form>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import LoginForm from '../components/LoginForm';

export default {
    components: {
        LoginForm,
    }
}
</script>

<style>

</style>