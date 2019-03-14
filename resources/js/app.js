
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);
Vue.component('users-list', require('./components/OnlineUsersList.vue').default);


const app = new Vue({
    el: '#app',

    data: {
        messages: [],
        chatUsers: [],
        toUser: [],
        publicChannel: 'private-chat',
        channels: [],
        activeItem: 'test'
    },
    mounted() {

    },
    created() {
        //get user online list
        this.getOnlineUsers();
        //check online users
        this.interval = setInterval(() => this.getOnlineUsers(), 20000);
        //get message from db
        this.fetchMessages();
        //get channels list
        this.getChannelList().then(response => {
            var self = this;
            var privateChannel = response.data.channels;
            self.channels.push(self.publicChannel);
            if(privateChannel.length > 0) {
                privateChannel.forEach(function(chatSession) {
                    self.channels.push(self.publicChannel+chatSession);
                });
            }
            //listening socket channels
            this.updateSocketMessage(self.channels);
        });
    },

    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data.message;
            });
        },
        getOnlineUsers() {
            axios.get('/activeUsers').then(response => {
                this.chatUsers = response.data;
            });
        },
        addMessage(message) {
            //display only 5 message
            if(this.messages.length > 4) {
                let isDelete = false;
                for (var key in this.messages) {
                    if (this.messages.hasOwnProperty(key) && !isDelete) {
                        this.messages.splice(key, 1);
                        isDelete = true;
                    }
                }
            }
            var self = this;

            let toUser = {};
            if(typeof message.toUser.id !== 'undefined') {
                //display a private message for user who sent it
                toUser = message.toUser.id;
                self.messages.push({
                    message: message.message,
                    user:{
                        name:message.user.name,
                        id: message.user.id
                    },
                    parent_user_id: toUser,
                    user_id: message.user.id
                });

            }

            axios.post('/messages', message).then(response => {
                //reset selected user
                this.toUser = [];
            });
        },
        getChannelList() {
            return axios.get('/channel/list');
        },
        //save user wich we will send message
        sendPrivateMessage(user) {
           // console.log(this.activeItem);
            this.toUser = user.data;
        },
        updateSocketMessage(channels) {
            var self = this;
            //listening all channels
            channels.forEach(function(channel) {
                var pusher = PP.subscribe(channel);
                pusher.bind('my-event', function(data) {
                    //display only 5 message
                    if(self.messages.length > 4) {
                        let isDelete = false;
                        for (var key in self.messages) {
                            if (self.messages.hasOwnProperty(key) && !isDelete) {
                                self.messages.splice(key, 1);
                                isDelete = true;
                            }
                        }
                    }
                    let toUser = {};
                    //private message check
                    if(data.parent_user_id !== 'undefined') {
                        toUser = data.parent_user_id;
                    }
                    self.messages.push({
                        message: data.message.message,
                        user:{
                            name:data.user.name,
                            id: data.user.id
                        },
                        parent_user_id: toUser,
                        user_id: data.message.user_id
                    });
                });
            });
        }
    }
});
