// resources/assets/js/components/ChatMessages.vue

<template>
    <ul>
        <li
            class="left clearfix"
            v-for="message in messages"
            :key="message.id"
            :class="{ 'fade-in': message.animation }"
        >
            {{ message.user.name }}:
            <p v-if="message.animation" class="message-with-animation">
                {{ message.message }}
            </p>
            <p v-else>{{ message.message }}</p>
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
