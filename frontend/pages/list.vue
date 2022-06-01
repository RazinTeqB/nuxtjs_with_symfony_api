<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 p-3">
        <nuxt-link class="btn btn-sm btn-primary float-right" to="/create"
          >Create</nuxt-link
        >
      </div>
    </div>
    <div style="max-height: 500px; overflow-y: scroll">
      <div class="row my-3">
        <div class="col-md-3">
          <input
            type="text"
            class="form-control"
            name="search[name]"
            placeholder="Search by name"
            v-model="search.name"
            @input="searchFilter"
          />
        </div>
        <div class="col-md-3">
          <input
            type="text"
            class="form-control"
            name="search[email]"
            placeholder="Search by email"
            v-model="search.email"
            @input="searchFilter"
          />
        </div>
        <div class="col-md-3">
          <input
            type="text"
            class="form-control"
            name="search[gender]"
            placeholder="Search by gender"
            v-model="search.gender"
            @input="searchFilter"
          />
        </div>
        <div class="col-md-3"></div>
      </div>
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
          <tr v-for="student in students['hydra:member']" :key="student.id">
            <!-- {{student}} -->
            <td>{{ student.id }}</td>
            <td>{{ student.name }}</td>
            <td>{{ student.email }}</td>
            <td>{{ student.gender }}</td>
            <td>{{ student.dob }}</td>
            <td>
              <nuxt-link :to="'/update/' + student.id">Update</nuxt-link>
              <a class="" href="#" @click.prevent="deleteStudent(student.id)"
                >Delete</a
              >
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div
      class="container-fluid mt-4 d-flex flex-row justify-content-between align-items-center"
    >
      <div class="flex-item">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalPage"
          :per-page="perPage"
          first-text="First"
          prev-text="Prev"
          next-text="Next"
          last-text="Last"
          @change="getStudents"
        ></b-pagination>
      </div>
      <div class="flex-item">
        Showing <a href="javascript:void()" class="">{{ currentPageItemsTotal}}</a> Total
        <a href="javascript:void()" class="">{{ totalPage }}</a> page(s)
      </div>
    </div>
  </div>
</template>

<script>
import debounce from 'debounce'
export default {
  name: 'List',
  middleware: 'auth',
  data() {
    return {
      students: [],
      currentPage: 1,
      perPage: 10,
      totalPage: 1,
      currentPageItemsTotal: 0,
      search: {
        name: '',
        email: '',
        gender: '',
      },
      searchQueryString: '',
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
      this.currentPageItemsTotal = newData['hydra:member'].length
    },
  },

  mounted() {
    this.getStudents()
    this.searchFilter = debounce(this.searchFilter, 500)
    // console.log(this.$axios.defaults.baseURL)
  },

  methods: {
    searchFilter() {
      this.searchQueryString = ''
      for (const key in this.search) {
        if (this.search[key] !== '') {
          this.searchQueryString += [key] + '=' + this.search[key] + '&'
        }
      }
      this.getStudents(this.currentPage, this.searchQueryString)
    },
    async getStudents(currentPage, searchString = '') {
      let pageNumber = 1
      if (currentPage !== undefined) {
        pageNumber = currentPage
      }
      await this.$axios
        .get(
          '/api/students?itemsPerPage=' +
            this.perPage +
            '&page=' +
            pageNumber +
            '&' +
            searchString,
          {
            headers: {
              // Accept: 'application/json',
            },
          }
        )
        .then((response) => {
          // this.students = response.data
          this.updateStudents(response.data)
          return response.data
        })
        .catch((error) => {
          console.error(error)
        })
    },
    updateStudents(data) {
      this.students = data
    },
    async deleteStudent(studentId) {
      if (confirm('Are you sure you want to delete this student?')) {
        await this.$axios
          .delete('/api/students/' + studentId)
          .then((response) => {
            this.getStudents(this.currentPage)
            return response.data
          })
          .catch((error) => {
            console.error(error)
          })
      }
    },
  },
}
</script>

<style scoped>
ul.pagination {
  margin-bottom: auto;
  margin-top: auto;
}
</style>
