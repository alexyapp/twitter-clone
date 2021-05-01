<template>
    <div class="d-flex align-items-center">
        <div class="mr-2">
            <a
                @click="e => $emit('edit-button-clicked', e)"
                href="#">
                <b-icon icon="pencil"></b-icon>
            </a>
        </div>

        <div>
            <a
                @click="e => $emit('delete-button-clicked', e)"
                href="#">
                <b-icon icon="trash"></b-icon>
            </a>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>