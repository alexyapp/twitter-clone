<template>
    <b-navbar toggleable="lg" type="dark" variant="primary" fixed="top">
        <b-navbar-brand :to="{name: 'home'}">Twitter Clone</b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav class="ml-auto">
                <b-nav-item-dropdown v-if="!!$store.getters.isLoggedIn">
                    <template #button-content>
                        <em>{{ $store.state.user.name }}</em>
                    </template>
                    <b-dropdown-item href="#">Profile</b-dropdown-item>
                    <b-dropdown-item @click="logout">Sign Out</b-dropdown-item>
                </b-nav-item-dropdown>

                <template v-else>
                    <b-nav-item :to="{name: 'login'}">Login</b-nav-item>
                    <b-nav-item :to="{name: 'register'}">Register</b-nav-item>
                </template>
            </b-navbar-nav>
        </b-collapse>
    </b-navbar>
</template>

<script>
export default {
    methods: {
        async logout() {
            await this.$store.dispatch('logout');
            this.$router.push({name: 'login'});
        },
    }
}
</script>

<style>

</style>