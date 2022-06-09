<template>
  <section class="section">
    <div class="container-fluid">
      <div class="w-md-50 m-auto">
        <h3 class="title has-text-centered">
          Welcome back!
          <span class="d-block d-md-inline-block">Login Into Your Account</span>
        </h3>

        <Notification v-if="error" :message="error.data.message" />

        <form method="post" @submit.prevent="login">
          <div class="form-group">
            <label class="form-label">Email</label>
            <input
              v-model="email"
              type="email"
              class="form-control"
              name="email"
            />
          </div>
          <div class="form-group mt-3">
            <label class="form-label">Password</label>
            <input
              v-model="password"
              type="password"
              class="form-control"
              name="password"
            />
          </div>
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary" :disabled="isLoading">
              <font-awesome-icon
                v-if="isLoading == true ? true : false"
                :icon="['fas', 'spinner']"
                class="fa-spin"
              />
              Log In
            </button>
          </div>
        </form>
        <div class="has-text-centered" style="margin-top: 20px">
          <p>
            Don't have an account?
            <nuxt-link to="/register">Register</nuxt-link>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
import Notification from '~/components/Notification'
export default {
  components: {
    Notification,
  },
  middleware: 'guest',
  data() {
    return {
      email: 'test@email.com',
      password: '1234',
      error: null,
      isLoading: false,
    }
  },
  computed: {
    ...mapGetters(['isAuthenticated', 'loggedInUser']),
  },
  methods: {
    async login() {
      this.isLoading = true
      try {
        await this.$auth.loginWith('local', {
          data: {
            username: this.email,
            password: this.password,
          },
        })
        this.$toast.clear(' ')
        this.$toast.success(' ', {
          theme: 'toasted-primary',
          position: 'top-center',
          icon: (el) => {
            el.innerHTML =
              '<div class="d-flex flex-row justify-content-center align-items-center"><img src="pwa.png" /><span class="pwa-text">Ready</span></div>'
            el.classList.add('pwa-icon')
            return el
          },
          action: {
            text: '',
          },
        })
        this.$router.push('/')
      } catch (e) {
        // this.error = e.response.data.message
        this.error = e.response
        this.isLoading = false
      }
      this.isLoading = false
    },
  },
}
</script>
