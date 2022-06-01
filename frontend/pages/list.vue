<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 p-3">
          <nuxt-link class="btn btn-sm btn-primary float-right" to="/create">Create</nuxt-link>
      </div>
    </div>
    <div style="max-height: 500px; overflow-y: scroll">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Dob</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="student in this.students['hydra:member']"
            :key="student.id"
          >
            <!-- {{student}} -->
            <td>{{ student.id }}</td>
            <td>{{ student.name }}</td>
            <td>{{ student.email }}</td>
            <td>{{ student.gender }}</td>
            <td>{{ student.dob }}</td>
            <td>
              <nuxt-link :to="'/update/' + student.id">Update</nuxt-link>
              <nuxt-link :to="'/delete/' + student.id">Delete</nuxt-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="container-fluid mt-4">
      <b-pagination
        v-model="currentPage"
        :total-rows="totalPage"
        :per-page="this.perPage"
         first-text="First"
        prev-text="Prev"
        next-text="Next"
        last-text="Last"
        @change="getStudents"
      ></b-pagination>
    </div>
  </div>
</template>

<script>
export default {
  name: 'List',
  middleware: 'auth',
  data() {
    return {
      students: [],
      currentPage: 1,
      perPage: 30,
      totalPage: 1,
      // currentPage: this.students.hasOwnProperty('hydra:totalItems')
      //   ? this.students['hydra:totalItems']
      //   : 0,
    }
  },
    head() {
      return {
        title: 'List All Students',
      }
    },
  watch: {
    students(newData) {
      this.totalPage = newData['hydra:totalItems']
    },
  },

  mounted() {
    this.getStudents()
    // console.log(this.$axios.defaults.baseURL)
  },

  methods: {
    async getStudents(data) {
      let pageNumber = 1
      if (data !== undefined) {
        pageNumber = data
      }
      await this.$axios
        .get('/api/students?page=' + pageNumber, {
          headers: {
            // Accept: 'application/json',
          },
        })
        .then((response) => {
          // this.students = response.data
          this.updateStudents(response.data)
          return response.data
        })
        .catch((error) => {
          console.log(error)
        })
    },
    updateStudents(data) {
      this.students = data
    },
  },
}
</script>
