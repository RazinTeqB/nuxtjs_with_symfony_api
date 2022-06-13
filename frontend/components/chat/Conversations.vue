<template>
  <div class="col-md-3 chat-conversation-list-box custom-border">
    <div
      v-for="conversation in conversations"
      :key="conversation.id"
      class="card conversation-card custom-border-bottom mt-2"
    >
      <div class="card-body p-2 d-flex flex-row">
        <div class="profile-box flex-item">
          <img
            src="http://localhost:8000/uploads/trademark-62a1e0b17a92d.png"
            alt="Image Not Found"
            class="uploadImg"
          />
        </div>
        <div class="flex-item ps-3">
          <a
            :href="'/chat/' + conversation.id"
            class="stretched-link text-decoration-none"
            @click.prevent="$parent.selectConversation(conversation.id)"
            >{{ conversation.conv_with_user }}</a
          >
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Conversations',
  data() {
    return {
      conversations: [],
    }
  },
  mounted() {
    // console.log('Chat mounted')
    this.getConversations()
  },
  methods: {
    getConversations() {
      this.$axios.get('/api/chat/conversations').then((response) => {
        this.conversations = response.data
      })
    },
  },
}
</script>
