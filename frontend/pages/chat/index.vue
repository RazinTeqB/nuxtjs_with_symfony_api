<template>
  <div class="container-fluid p-0 full-width">
    <div :class="isLoading ? '' : 'stopLoading'" class="loader-container">
      <div class="loader" :style="isLoading ? '' : 'display:none;'"></div>
    </div>
    <div class="row">
      <div class="col-md-12 custom-border p-2 ps-3">
        <div class="d-flex flex-row align-items-center">
          <button
            class="navbar-toggler align-self-end mb-1"
            type="button"
            @click="toggleSidebar = !toggleSidebar"
          >
            <font-awesome-icon :icon="['fas', 'bars']" class="float-start" />
          </button>
          <span class="fs-3 ms-3 m-0">Real-Time Chat System</span>
        </div>
      </div>
    </div>
    <div class="row" style="height: 91.3%">
      <!-- <Conversations /> -->
      <div
        :class="toggleSidebar ? 'collapse' : ''"
        class="col-md-3 chat-conversation-list-box custom-border border-top-0"
      >
        <div class="col-md-12 mt-2">
          <v-select
            label="name"
            :filterable="false"
            :options="searchUser.options"
            :clearable="false"
            :clear-search-on-select="true"
            @search="onSearchUsers"
            @input="startNewConv($event)"
            placeholder="Start new conversation"
          >
            <template slot="no-options"> Type to search.. </template>
            <template slot="option" slot-scope="option">
              <div class="d-center">
                <span>{{ option.name }}</span>
                <br />
                <small>{{ option.username }}</small>
              </div>
            </template>
            <template slot="selected-option" slot-scope="option">
              <div class="selected d-center">
                <span>{{ option.name }}</span>
                <br />
                <small>{{ option.username }}</small>
              </div>
            </template>
          </v-select>
        </div>
        <div
          v-for="conversation in conversations"
          :key="conversation.id"
          class="card conversation-card custom-border-bottom mt-2"
          :style="
            conversation_id === conversation.id
              ? 'box-shadow: hsl(154deg 53% 60%) 2px 2px 6px 1px;'
              : ''
          "
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
                @click.prevent="selectConversation(conversation.id)"
                >{{ conversation.conv_with_user }}</a
              >
            </div>
          </div>
        </div>
      </div>
      <div
        v-if="conversation_id === '' || conversation_id === null"
        class="col-md-9 chat-conversation-box custom-border border"
      >
        <div class="no-chat">No Chat Selected</div>
      </div>
      <ChatScreen
        v-for="conversation in conversations"
        v-show="conversation_id === conversation.id"
        :key="conversation.id"
        :conversation="conversation"
        :selected_conversation="conversation_id"
      />
    </div>
  </div>
</template>

<script>
// import Conversations from '../../components/chat/Conversations.vue'
import debounce from 'debounce'
import ChatScreen from '../../components/chat/ChatScreen.vue'
export default {
  name: 'Chat',
  components: {
    // Conversations,
    ChatScreen,
  },
  layout: 'empty',
  middleware: 'auth',
  data() {
    return {
      conversation_id: null,
      toggleSidebar: false,
      conversations: [],
      isLoading: true,
      searchUser: {
        value: '',
        options: [],
      },
    }
  },
  mounted() {
    if (window.innerWidth < 770) {
      this.toggleSidebar = true
    }
    this.getConversations()
    this.$echo
      .channel('user-' + this.$auth.user.id)
      .on('conversationsUpdate', (e) => {
        if (e.data !== null || e.data !== undefined) {
          const valObj = this.conversations.find(function (elem, index) {
            return elem.id === e.data.id
          })
          if (valObj === undefined) {
            this.conversations.push(e.data)
          }
          if (e.openChat === true || e.openChat === 'true') {
            this.selectConversation(e.data.id)
          }
        }
      })
    this.isLoading = false
    this.searchUsers = debounce(this.searchUsers, 300)
    this.onSearchUsers = debounce(this.onSearchUsers, 300)
  },
  methods: {
    selectConversation(conversationId) {
      this.conversation_id = parseInt(conversationId)
    },
    getConversations() {
      this.$axios.get('/api/chat/conversations').then((response) => {
        this.conversations = response.data
        // console.log(this.conversations)
      })
    },
    onSearchUsers(search, loading) {
      this.searchUsers(search, this)
      loading(false)
    },
    searchUsers(search) {
      const loggInUser = this.$auth.user
      this.$axios
        .get('/api/users?page=1&itemsPerPage=2&username=' + search, {
          headers: {
            Accept: 'application/json',
          },
        })
        .then((response) => {
          if (response.data !== undefined) {
            const valObj = response.data.find(function (elem, index) {
              return elem.id === loggInUser.id
            })
            if (valObj !== undefined) {
              const indexOfId = response.data.indexOf(valObj)
              response.data.splice(indexOfId, 1)
            }
            this.searchUser.options = response.data
          }
        })
    },
    startNewConv(event) {
      const valObj = this.conversations.find(function (elem, index) {
        return elem.id === event.id
      })
      if (valObj === undefined) {
        this.createConversation(event)
      }
    },
    createConversation(data) {
      this.$axios.post('/api/chat/conversations', data).then((response) => {
        console.log(response.data)
      })
    },
  },
}
</script>

<style lang="scss">
.loader-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(9, 26, 40, 1);
  z-index: 9999;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
}
.loader {
  border: 9px solid #f3f3f3; /* Light grey */
  border-top: 9px solid var(--color-secondary); /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 1s linear infinite;
}
.stopLoading {
  animation: stopLoading 1s ease forwards !important;
}
@keyframes stopLoading {
  0% {
    opacity: 1;
  }
  90% {
    opacity: 0;
  }
  100% {
    opacity: 0;
    z-index: -1;
  }
}
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
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
.conversation-card:hover {
  box-shadow: 1px 1px 7px 1px hsl(154deg 53% 80%);
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
