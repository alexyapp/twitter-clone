<template>
    <b-container v-if="user">
        <b-row>
            <b-col cols="6" offset="3">
                <h3>{{ user.name }}'s Profile</h3>
                
                <div v-if="shouldShowActionButtons">
                    <b-button v-b-modal.modal-1>Send DM</b-button>
                    <b-button>Follow</b-button>
                </div>
            </b-col>
        </b-row>
        <b-modal id="modal-1" title="Send DM" hide-footer>
            <message-form :receiverId="user.id"></message-form>
        </b-modal>
    </b-container>
</template>

<script>
import usersApi from '../api/users';
import { errorHandlerMixin } from '../mixins';
import MessageForm from '../components/MessageForm';

export default {
    data() {
        return {
            user: null
        }
    },

    components: {
        MessageForm
    },

    mixins: [
        errorHandlerMixin,
    ],

    computed: {
        shouldShowActionButtons() {
            if (this.user) {
                return this.$store.state.user.id != this.user.id
            }
            return false;
        }
    },

    async mounted() {
        const { userId } = this.$route.params;
        try {
            const { data: { data: user } } = await usersApi.getOne(userId);
            this.user = user;
        } catch (error) {
            this.handleGeneralError(error);
        }
    }
}
</script>

<style>

</style>