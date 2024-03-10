/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: Echo } = require('laravel-echo');

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        messages: [],
        room_id: document.getElementById('room_id').value,
    },

    created() {
        this.fetchMessages();

        window.Echo.private(this.room_id)
            .listen('MessageSent', (e) => {
                this.messages.push({
                    question: e.message.question,
                    answer: e.message.answer,
                    user: e.user,
                    animation: true
                });

                this.disableInputs();
                setTimeout(this.enableInputs, 5000);
            });
    },

    methods: {
        fetchMessages() {
            axios.get(`/messages/${this.room_id}`).then(response => {
                this.messages = response.data;
            }).catch(error => {
                console.error('Error fetching messages:', error);
            });
        },

        addMessage(data) {
            this.disableInputs();
            setTimeout(this.enableInputs, 5000);

            axios.post('/messages', data).then(response => {
                data.answer = response.data.answer
                this.messages.push(data);
            });
        },

        disableInputs() {
            $('#btn-chat, #btn-input').prop('disabled', true);
        },

        enableInputs() {
            $('#btn-chat, #btn-input').prop('disabled', false);
        }
    }
});
