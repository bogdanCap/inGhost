
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

const app = new Vue({
    el: '#app',

    data: {
        messages: []
    },
    created() {

        //this.interval = setInterval(() => this.fetchMessages(), 2000);
        this.fetchMessages();
        //read message data from pusher if we need to read message from pusher
        //but now we reed message from our local db in fetchMessages()
         /*
         Echo.private('private-chat')
         .listen('my-event', (e) => {
             this.messages.push({
                 message: e.message.message,
                 user: e.user
            });
         });
         */

        //get message from pusher -> PP define in bootstrap.js
        //live message updating
        var self = this;
        var channel = PP.subscribe('private-chat');
        channel.bind('my-event', function(data) {
            //display only 5 message
            if(self.messages.length > 4) {
                let isDelete = false;
                for (var key in self.messages) {
                    if (self.messages.hasOwnProperty(key) && !isDelete) {
                        // console.log(key + " -> " + this.messages[key]);
                        self.messages.splice(key, 1);
                        isDelete = true;
                    }
                }
            }
            self.messages.push({
                message: data.message,
                user:{name:data.user.name}
            });
        });
    },

    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data;
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

            axios.post('/messages', message).then(response => {
                console.log(response.data);
            });
        }
    }
});
