<template>
  <div class="container-fluid p-0 full-width">
    <div class="row">
      <div class="col-md-12 custom-border p-2 ps-3">
        <h3>Real-Time Chat System</h3>
      </div>
    </div>
    <div class="row" style="height: 91.3%">
      <Conversations />
      <ChatScreen :conversation="conversation_id" />
    </div>
  </div>
</template>

<script>
import Conversations from '../../components/chat/Conversations.vue'
import ChatScreen from '../../components/chat/ChatScreen.vue'
export default {
  name: 'Chat',
  components: {
    Conversations,
    ChatScreen,
  },
  layout: 'empty',
  middleware: 'auth',
  data() {
    return {
      conversation_id: null,
    }
  },
  mounted() {
    console.log('Chat mounted')
  },
  methods: {
    selectConversation(conversationId) {
      this.conversation_id = parseInt(conversationId)
    },
  },
}
</script>

<style lang="scss">
#app,
.full-width {
  height: 100vh;
}
.custom-border {
  border: 2px solid hsl(154deg 53% 20%);
}
.custom-border-bottom {
  border-bottom: 2px solid hsl(154deg 53% 20%);
}
.conversation-card {
  width: 100%;
  background-color: inherit;
  .profile-box {
    height: 100%;
    width: fit-content;
  }
  .profile-box img {
    height: auto;
    width: 45px;
  }
}

.no-chat {
  height: 100%;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.5em;
}
</style>
