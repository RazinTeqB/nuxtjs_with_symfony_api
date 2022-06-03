<template>
  <section class="section">
    <div class="container">
      <div class="columns">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-centered">Register New Account</h3>

          <!-- <Notification v-if="errors" :message="errors" /> -->

          <form method="post" @submit.prevent="register">
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
              <div
                v-if="errors.email !== undefined && errors.email != ''"
                class="invalid-feedback d-block"
              >
                {{ errors.email }}
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
            <div class="field">
              <label class="label">Confirm Password</label>
              <div class="control">
                <input
                  v-model="confirmPassword"
                  type="password"
                  class="form-control"
                  name="confirmPassword"
                />
              </div>
              <div
                v-if="confirmPassword != '' && password !== confirmPassword"
                class="invalid-feedback d-block"
              >
                Password does not match
              </div>
              <div
                v-if="errors.password !== undefined && errors.password != ''"
                class="invalid-feedback d-block"
              >
                {{ errors.password }}
              </div>
            </div>
            <div class="control">
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="
                  email === '' ||
                  password === '' ||
                  confirmPassword === '' ||
                  password !== confirmPassword ||
                  isLoading
                "
              >
                <font-awesome-icon
                  v-if="isLoading == true ? true : false"
                  :icon="['fas', 'spinner']"
                  class="fa-spin"
                />
                Register
              </button>
            </div>
          </form>
          <div class="has-text-centered" style="margin-top: 20px">
            <p>
              Already have an account? <nuxt-link to="/login">Login</nuxt-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { mapGetters } from 'vuex'
// import Notification from '~/components/Notification'
export default {
  components: {
    // Notification,
  },
  middleware: 'guest',
  data() {
    return {
      email: '',
      password: '',
      confirmPassword: '',
      errors: '',
      isLoading: false,
    }
  },
  computed: {
    ...mapGetters(['isAuthenticated', 'loggedInUser']),
  },
  methods: {
    async register() {
      try {
        this.isLoading = true
        await this.$axios
          .post('/api/register', {
            username: this.email,
            password: this.password,
          })
          .then((response) => {
            if (response.data.email !== '') {
              alert('User Register successfully')
              this.$auth
                .loginWith('local', {
                  data: {
                    username: this.email,
                    password: this.password,
                  },
                })
                .then(() => {
                  this.$router.push('/')
                })
            }
          })
          .catch((rspError) => {
            this.errors = []
            if (rspError.response.data.violations === undefined) {
              console.error(rspError.response.data['hydra:description'])
            } else {
              for (
                let i = 0;
                i < rspError.response.data.violations.length;
                i++
              ) {
                this.errors[rspError.response.data.violations[i].propertyPath] =
                  rspError.response.data.violations[i].message
              }
            }
            this.isLoading = false
          })
      } catch (e) {
        // this.error = e.response.data.message
        this.errors = e.response
        this.isLoading = false
      }
      this.isLoading = false
    },
  },
}
</script>
