<template>
  <div class="container-fluid">
    <h1>Update Student {{ this.data.name }}</h1>
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
          <div class="form-group">
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
          <div class="custom-control custom-radio custom-control-inline">
            <input
              id="male"
              v-model="data.gender"
              type="radio"
              name="gender"
              class="custom-control-input"
              value="male"
            />
            <label class="custom-control-label" for="male">Male</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              id="female"
              v-model="data.gender"
              type="radio"
              name="gender"
              class="custom-control-input"
              value="female"
            />
            <label class="custom-control-label" for="female">Female</label>
          </div>
          <div class="custom-control custom-radio custom-control-inline">
            <input
              id="other"
              v-model="data.gender"
              type="radio"
              name="gender"
              class="custom-control-input"
              value="other"
            />
            <label class="custom-control-label" for="other">Other</label>
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
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="this.data.name === '' || this.data.email === '' || this.data.dob === '' || this.data.gender === ''"
              @click.prevent="updateStudent"
            >
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
      },
      errors: '',
    }
  },

  head() {
    return {
      title: 'Update Student',
    }
  },
  computed: {},
  mounted() {
    this.studentId = this.$route.params.id
    if (this.studentId) {
      this.getStudent(this.studentId)
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
          this.data.name = response.data.name
          this.data.email = response.data.email
          this.data.dob = response.data.dob
          this.data.gender = response.data.gender.toLowerCase()
        })
        .catch((error) => {
          console.error(error)
        })
    },
    async updateStudent() {
      await this.$axios
        .put('/api/students/' + this.studentId, this.data)
        .then((response) => {
          if (response.data.email !== '') {
            alert('Student updated successfully')
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
        })
    },
  },
}
</script>
