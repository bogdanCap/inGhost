<template>
    <div class="input-group">
        <input id="btn-input" type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="newMessage" @keyup.enter="sendMessage">

        <span class="input-group-btn">
            <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
                Send
            </button>
        </span>
    </div>
</template>

<script>
    export default {
        props: ['user', 'toUser'],

        data() {
            return {
                newMessage: ''
            }
        },


        methods: {
            sendMessage() {
                //reset active user selector;
                //refs userlist set in chat.blade.php
                //get access to any components via reds
                this.$root.$refs.userlist.activeItemId = '';
                //send message
                this.$emit('messagesent', {
                    user: this.user,
                    message: this.newMessage,
                    toUser: this.toUser
                });
                //reset message
                this.newMessage = ''
            }
        }
    }
</script>