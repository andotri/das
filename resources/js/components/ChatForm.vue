// resources/assets/js/components/ChatForm.vue

<template>
  <div class="input-group">
    <input
      id="btn-input"
      type="text"
      name="question"
      class="form-control input-sm"
      placeholder="Message ChatAssistant…"
      v-model="question"
      @keyup.enter="sendMessage"
    />

    <span class="input-group-btn">
      <button class="btn btn-primary btn-sm" id="btn-chat" @click="sendMessage">
        Send
      </button>
    </span>
  </div>
</template>

<script>
export default {
  props: ["user"],

  data() {
    return {
      question: "",
      room_id: "",
    };
  },

  methods: {
    sendMessage() {
      this.$emit("messagesent", {
        room_id: document.getElementById('room_id').value,
        user: this.user,
        question: this.question,
      });

      this.question = "";
    },
  },
};
</script>
