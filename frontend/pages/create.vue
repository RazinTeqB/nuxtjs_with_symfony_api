<template>
  <div class="container-fluid">
    <h1>Create New Student</h1>
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
            <label for="image">Image Upload</label>
            <input
              id="image"
              type="file"
              class="form-control"
              @change="handleFileUpload($event)"
            />
            <div
              v-if="errors.image !== undefined && errors.image != ''"
              class="invalid-feedback d-block"
            >
              {{ errors.image }}
            </div>
          </div>
          <div class="form-group mt-3">
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="isLoading"
              @click.prevent="create"
            >
              <font-awesome-icon
                v-if="isLoading == true ? true : false"
                :icon="['fas', 'spinner']"
                class="fa-spin"
              />
              Submit
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
  name: 'Create',
  middleware: 'auth',
  data() {
    return {
      today: new Date().toISOString().split('T')[0],
      data: {
        name: '',
        email: '',
        gender: '',
        dob: '',
        image: '',
      },
      errors: '',
      isLoading: false,
    }
  },
  head() {
    return {
      title: 'Create new student',
    }
  },
  computed: {},
  methods: {
    handleFileUpload(event) {
      this.data.file = event.target.files[0]
    },
    async create() {
      this.isLoading = true
      const formData = new FormData()
      formData.append('name', this.data.name)
      formData.append('email', this.data.email)
      formData.append('gender', this.data.gender)
      formData.append('dob', this.data.dob)
      formData.append('image', this.data.file)

      await this.$axios
        .post('/api/students', formData, {
          headers: {
            "accept": 'application/json',
            'Content-Type': 'multipart/form-data',
            // 'Content-Type': 'multipart/form-data',
          },
        })
        .then((response) => {
          if (response.data.email !== '') {
            alert('Student created successfully')
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
