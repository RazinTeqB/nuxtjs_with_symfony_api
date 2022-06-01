<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-centered">
            Welcome back! Login Into Your Account
          </h3>

          <Notification v-if="error" :message="error.data.message" />

          <form method="post" @submit.prevent="login">
            <div class="field">
              <label class="label">Email</label>
              <div class="control">
                <input
                  v-model="email"
                  type="email"
                  class="form-control"
                  name="email"
                />
              </div>
            </div>
            <div class="field">
              <label class="label">Password</label>
              <div class="control">
                <input
                  v-model="password"
                  type="password"
                  class="form-control"
                  name="password"
                />
              </div>
            </div>
            <div class="control">
              <button type="submit" class="btn btn-primary">Log In</button>
            </div>
          </form>
          <div class="has-text-centered" style="margin-top: 20px">
            <p>
              Don't have an account? Register
              <small>will be available soon</small>
              <!-- <nuxt-link to="/register">Register</nuxt-link> -->
            </p>
          </div>
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

  data() {
    return {
      email: 'test@email.com',
      password: '1234',
      error: null,
    }
  },
  computed: {
    ...mapGetters(['isAuthenticated', 'loggedInUser']),
  },
  methods: {
    async login() {
      try {
        await this.$auth.loginWith('local', {
          data: {
            username: this.email,
            password: this.password,
          },
        })

        this.$router.push('/')
      } catch (e) {
        // this.error = e.response.data.message
        this.error = e.response
      }
    },
  },
}
</script>
