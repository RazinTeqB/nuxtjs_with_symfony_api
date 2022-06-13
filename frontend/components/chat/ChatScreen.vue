<template>
  <div class="col-md-9 chat-conversation-box custom-border">
    <div v-if="conversation === '' || conversation === null" class="no-chat">
      No Chat Selected
    </div>
    <div v-else class="chat-container">
      <div
        v-for="messageData in messagesData"
        :key="messageData.id"
        :class="$auth.user.id === messageData.by_user ? 'ms-auto' : ''"
        class="card conversation-chat-card custom-border-bottom mt-2"
      >
        <div class="card-body p-2 d-flex flex-column">
          <div class="flex-item ps-3">
            <a href="javascript:void(0);" class="text-decoration-none">{{
              messageData.message
            }}</a>
          </div>
          <div class="flex-item text-end">
            <small>{{ messageData.created }}</small>
          </div>
        </div>
      </div>
      <div class="chat-send-msg-container">
        <input
          v-model="message"
          type="text"
          class="form-control"
          style="width: 90% !important"
          @keyup.enter="sendMessage($event)"
        />
        <button
          class="btn btn-success rounded-circle p-3"
          style="padding-left: 0.8rem !important"
        >
          <font-awesome-icon
            :icon="['fas', 'paper-plane']"
            class="float-end table-sort-icon"
          />
        </button>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'ChatScreen',
  props: {
    conversation: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      messagesData: [],
      message: '',
    }
  },
  watch: {
    conversation(newVal, oldVal) {
      if (newVal !== oldVal) {
        this.getMessages()
        this.message = ''
      }
    },
  },
  mounted() {},

  methods: {
    getMessages() {
      this.$axios
        .get('/api/chat/messages/' + this.conversation)
        .then((response) => {
          this.messagesData = response.data
        })
    },
    sendMessage(event) {
      console.log(event.target.value)
      console.log(this.conversationId)
      // if (this.message !== '') {
      //   this.$axios
      //     .post('/api/chat/messages/' + conversationId, {
      //       message: this.message,
      //     })
      //     .then((response) => {
      //       this.message = ''
      //       this.getMessages()
      //     })
      // }
    },
  },
}
</script>

<style>
.chat-container {
  height: 100%;
  width: 100%;
  overflow-y: auto;
}
.conversation-chat-card {
  width: 40%;
  background-color: inherit;
}
.chat-send-msg-container {
  /* border: 1px solid red; */
  width: 74%;
  height: 50px;
  display: flex;
  justify-content: space-around;
  align-items: center;
  bottom: 5px;
  position: fixed;
}
</style>
