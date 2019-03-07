
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
    /*
    watch: {
        messages: {
            handler: function (after, before) {
                // Return the object that changed

                let changed = after.filter( function( p, idx ) {
                    return Object.keys(p).some( function( prop ) {
                        return p[prop] !== before[idx][prop];
                    })
                })

                //this.before.splice(this.before.indexOf(event), 1);
                // Log it
                console.log(changed);
                console.log("---------------");
            },
            deep: true
        }
    },*/

    created() {

        this.interval = setInterval(() => this.fetchMessages(), 2000);

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
        /*
        var self = this;
        var channel = PP.subscribe('private-chat');
        channel.bind('my-event', function(data) {
            let param = JSON.stringify(data);


            self.messages.push({
                message: param.message,
                user: {name:'test'}
            })
        });
        */



    },
    /*
    mounted: function () {
        this.$watch('messages', function () {
            console.log('a thing changed');
            console.log(this.messages);
            let isDelete = false;
            for (var key in this.messages) {
                if (this.messages.hasOwnProperty(key) && !isDelete) {
                   // console.log(key + " -> " + this.messages[key]);
                    this.messages.splice(key, 1);
                    isDelete = true;
                }
            }

            //this.$delete(this.messages, index);

        }, {deep:true})
    },*/

    methods: {
        fetchMessages() {
            axios.get('/messages').then(response => {
                this.messages = response.data;
        });
        },

        addMessage(message) {
            if(this.messages.length > 4) {
                let isDelete = false;
                for (var key in this.messages) {
                    if (this.messages.hasOwnProperty(key) && !isDelete) {
                        // console.log(key + " -> " + this.messages[key]);
                        this.messages.splice(key, 1);
                        isDelete = true;
                    }
                }
            }


            this.messages.push(message);

            axios.post('/messages', message).then(response => {
                console.log(response.data);
            });
        }
    },
});
