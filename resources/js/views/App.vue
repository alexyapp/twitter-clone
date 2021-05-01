<template>
    <div>
        <navbar></navbar>
        <div class="container">
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

</style>