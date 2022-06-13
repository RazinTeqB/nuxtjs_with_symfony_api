<template>
  <div class="container-fluid">
    <h3 class="my-3 my-md-1">{{ data.name }}</h3>
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
            disabled
            readonly
          />
        </div>
        <div class="form-group mt-3">
          <label for="email">Email</label>
          <input
            id="email"
            v-model="data.email"
            type="email"
            class="form-control"
            disabled
            readonly
          />
        </div>
        <label for="" class="form-label mt-3"> Gender: {{ getGender }} </label>
        <div class="form-group mt-3">
          <label for="dob">DOB</label>
          <input
            id="dob"
            v-model="data.dob"
            type="date"
            class="form-control"
            disabled
            readonly
          />
        </div>
        <div class="form-group mt-3">
          <label for="user">User</label>
          <input
            id="dob"
            v-model="data.user.username"
            type="text"
            class="form-control"
            disabled
            readonly
          />
        </div>
        <div class="form-group mt-3">
          <a
            type="button"
            class="btn btn-primary btn-lg"
            @click="$router.push('/students/' + studentId + '/update')"
          >
            Edit
          </a>
          <button
            type="button"
            class="btn btn-secondary btn-lg"
            @click="$router.push('/students/')"
          >
            Go Back
          </button>
        </div>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Update',
  middleware: 'auth',
  data() {
    return {
      studentId: '',
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
  computed: {
    getGender() {
      return (
        this.data.gender.charAt(0).toUpperCase() + this.data.gender.slice(1)
      )
    },
  },
  mounted() {
    if (
      this.$route.params.id === '' ||
      this.$route.params.id === undefined ||
      Number.isNaN(parseInt(this.$route.params.id))
    ) {
      // alert('Student not found')
      this.$toast.error('Student not found')
      this.$router.push('/students/')
      return false
    }
    this.studentId = this.$route.params.id
    if (this.studentId) {
      this.isLoading = true
      this.getStudent(this.studentId)
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
            this.$router.push('/students/')
          }
          this.data.name = response.data.name
          this.data.email = response.data.email
          this.data.dob = response.data.dob
          this.data.gender = response.data.gender.toLowerCase()
          this.data.user = response.data.user
        })
        .catch((error) => {
          console.error(error)
        })
    },
  },
}
</script>
