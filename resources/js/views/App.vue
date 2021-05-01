<template>
    <div>
        <navbar></navbar>
        <div id="main">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import Navbar from '../components/Navbar';

export default {
    components: {
        Navbar
    },
}
</script>

<style>
    #main {
        margin-top: calc(62px + 1rem);
    }
</style>