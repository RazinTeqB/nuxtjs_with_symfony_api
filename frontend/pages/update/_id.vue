<template>
  <div class="container-fluid">
    <h3>Update Student {{ data.name }}</h3>
    <form>
      <div class="row pt-3">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="fullName">Full Name</label>
            <input
              id="fullName"
              v-model="data.name"
              type="text"
              class="form-control"
              aria-describedby="emailHelp"
            />
            <div
              v-if="errors.name !== undefined && errors.name != ''"
              class="invalid-feedback d-block"
            >
              {{ errors.name }}
            </div>
          </div>
          <div class="form-group mt-3">
            <label for="email">Email</label>
            <input
              id="email"
              v-model="data.email"
              type="email"
              class="form-control"
            />
            <div
              v-if="errors.email !== undefined && errors.email != ''"
              class="invalid-feedback d-block"
            >
              {{ errors.email }}
            </div>
          </div>
          <label for="" class="form-label mt-3">Gender</label>
          <div class="form-group d-flex flex-row justify-content-around w-50">
            <div class="form-check">
              <input
                id="male"
                v-model="data.gender"
                type="radio"
                name="gender"
                class="form-check-input"
                value="male"
              />
              <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check">
              <input
                id="female"
                v-model="data.gender"
                type="radio"
                name="gender"
                class="form-check-input"
                value="female"
              />
              <label class="form-check-label" for="female">Female</label>
            </div>
            <div class="form-check">
              <input
                id="other"
                v-model="data.gender"
                type="radio"
                name="gender"
                class="form-check-input"
                value="other"
              />
              <label class="form-check-label" for="other">Other</label>
            </div>
          </div>
          <div class="form-group">
            <div
              v-if="errors.gender !== undefined && errors.gender != ''"
              class="invalid-feedback d-block"
            >
              {{ errors.gender }}
            </div>
          </div>
          <div class="form-group mt-3">
            <label for="dob">DOB</label>
            <input
              id="dob"
              v-model="data.dob"
              type="date"
              class="form-control"
              :max="today"
            />
            <div
              v-if="errors.dob !== undefined && errors.dob != ''"
              class="invalid-feedback d-block"
            >
              {{ errors.dob }}
            </div>
          </div>
          <div class="form-group mt-3">
            <label for="user">User</label>
            <select id="user" v-model="data.user" class="form-control">
              <option value="">Select User</option>
              <option
                v-for="user in users['hydra:member']"
                :key="user.id"
                :value="user['@id']"
              >
                {{ user.username }}
              </option>
            </select>
            <div
              v-if="errors.dob !== undefined && errors.dob != ''"
              class="invalid-feedback d-block"
            >
              {{ errors.dob }}
            </div>
          </div>
          <div class="form-group mt-3">
            <button
              type="submit"
              class="btn btn-primary btn-lg"
              :disabled="
                data.name === '' ||
                data.email === '' ||
                data.dob === '' ||
                data.gender === '' ||
                isLoading
              "
              @click.prevent="updateStudent"
            >
              <font-awesome-icon
                v-if="isLoading == true ? true : false"
                :icon="['fas', 'spinner']"
                class="fa-spin"
              />
              Update
            </button>
          </div>
        </div>
        <div class="col-md-3"></div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  name: 'Update',
  middleware: 'auth',
  data() {
    return {
      studentId: '',
      today: new Date().toISOString().split('T')[0],
      data: {
        name: '',
        email: '',
        gender: '',
        dob: '',
        user: '',
      },
      errors: '',
      users: [],
      isLoading: false,
    }
  },

  head() {
    return {
      title: 'Update Student',
    }
  },
  computed: {},
  mounted() {
    if (this.$route.params.id === '' || this.$route.params.id === undefined) {
      // alert('Student not found')
      this.$toast.error('Student not found')
      this.$router.push('/list')
    }
    this.studentId = this.$route.params.id
    if (this.studentId) {
      this.isLoading = true
      this.getStudent(this.studentId)
      this.getUsers()
      this.isLoading = false
    }
  },
  methods: {
    async getStudent(data) {
      await this.$axios
        .get('/api/students/' + data, {
          headers: {
            // Accept: 'application/json',
          },
        })
        .then((response) => {
          if (response.data === null || response.data.name === undefined) {
            this.$toast.error('Student not found')
            this.$router.push('/list')
          }
          this.data.name = response.data.name
          this.data.email = response.data.email
          this.data.dob = response.data.dob
          this.data.gender = response.data.gender.toLowerCase()
          this.data.user = response.data['@id']
        })
        .catch((error) => {
          console.error(error)
        })
    },
    async getUsers() {
      await this.$axios
        .get('/api/users', {
          headers: {
            // Accept: 'application/json',
          },
        })
        .then((response) => {
          this.users = response.data
        })
        .catch((error) => {
          console.error(error)
        })
    },
    async updateStudent() {
      this.isLoading = true
      this.data.user = this.data.user === '' ? null : this.data.user

      await this.$axios
        .put('/api/students/' + this.studentId, this.data)
        .then((response) => {
          if (response.data.email !== '') {
            // alert('Student updated successfully')
            this.$toast.success('Student updated successfully')
            this.$router.push('/list')
          }
        })
        .catch((rspError) => {
          this.errors = []
          if (rspError.response.data.violations === undefined) {
            console.error(rspError.response.data['hydra:description'])
          } else {
            for (let i = 0; i < rspError.response.data.violations.length; i++) {
              this.errors[rspError.response.data.violations[i].propertyPath] =
                rspError.response.data.violations[i].message
            }
          }
          this.isLoading = false
        })
      this.isLoading = false
    },
  },
}
</script>
