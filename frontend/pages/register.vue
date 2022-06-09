<template>
  <section class="section">
    <div class="container-fluid">
      <div class="columns">
        <div class="w-md-50 m-auto">
          <h3 class="title has-text-centered">Register New Account</h3>

          <!-- <Notification v-if="errors" :message="errors" /> -->

          <form method="post" @submit.prevent="register">
            <div class="form-group">
              <label class="form-label">Email</label>
              <input
                v-model="email"
                type="email"
                class="form-control"
                name="email"
              />
              <div
                v-if="errors.email !== undefined && errors.email != ''"
                class="invalid-feedback d-block"
              >
                {{ errors.email }}
              </div>
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
              <label class="form-label">Confirm Password</label>
              <input
                v-model="confirmPassword"
                type="password"
                class="form-control"
                name="confirmPassword"
              />
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
            <div class="form-group mt-3">
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
              // alert('User Register successfully')
              this.$toast.success('User Register successfully')
              this.$auth
                .loginWith('local', {
                  data: {
                    username: this.email,
                    password: this.password,
                  },
                })
                .then(() => {
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
                  });
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
