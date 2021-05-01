<template>
    <b-container>
        <b-row>
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