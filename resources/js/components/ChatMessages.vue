// resources/assets/js/components/ChatMessages.vue

<template>
    <ul>
        <li
            class="left clearfix"
            v-for="message in messages"
            :key="message.id"
            :class="{ 'fade-in': message.animation }"
        >
            👨‍💻 <strong>{{ message.user.name }}</strong>
            <div v-if="message.animation" class="message-with-animation">
                {{ message.question }}
            </div>
            <div v-else>{{ message.question }}</div>

            🤖 <strong>Chat Assistant</strong>
            <div v-if="message.animation" class="message-with-animation" v-html="message.answer"></div>
            <div v-else v-html="message.answer"></div>
        </li>
    </ul>
</template>

<script>
export default {
    props: ["messages"],
};
</script>

<style scoped>
.fade-in {
    animation: fadeInAnimation 0.5s ease-in-out;
}

@keyframes fadeInAnimation {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

.message-with-animation {
    /* Additional styling for messages with animation */
}
</style>
