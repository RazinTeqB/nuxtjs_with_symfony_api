<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 p-3">
        <h3 style="display: inline-block" class="">Students list</h3>
        <nuxt-link
          class="btn btn-sm btn-primary float-end position-relative translate-middle top-50"
          to="/create"
          >Create</nuxt-link
        >
      </div>
      <div class="col-md-12 border-bottom rounded">
        <div
          class="container-fluid my-4 d-flex flex-row justify-content-between align-items-center"
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
              class="customPaginationClass"
            ></b-pagination>
          </div>

          <div class="flex-item">
            <div
              class="form-group d-flex flex-row justify-content-between align-items-center"
            >
              <label for="goto" class="text-nowrap me-2"> Go to </label>
              <input
                id="goto"
                v-model="gotoPage"
                type="number"
                class="form-control"
                placeholder="page number"
                :max="totalPageFun"
                @blur="searchFilter"
              />
            </div>
          </div>

          <div class="flex-item">
            <div
              class="d-flex flex-row justify-content-around align-items-center"
            >
              <div class="flex-item">
                <div class="form-group form-inline my-auto">
                  <div
                    class="d-flex flex-row justify-content-between align-items-center"
                  >
                    <label for="perPageCount" class="form-label"
                      >Per Page</label
                    >
                    <select
                      id="perPageCount"
                      v-model="perPage"
                      name="perPage"
                      class="form-control me-4 ms-2 h-75"
                      style="width: 70px"
                      @change="searchFilter"
                    >
                      <option value="10">10</option>
                      <option value="20">20</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                      <!-- <option :value="perPage * totalPage">All</option> -->
                    </select>
                  </div>
                </div>
              </div>
              <div class="flex-item">
                Showing
                <a href="javascript:void()" class="">{{
                  currentPageItemsTotal
                }}</a>
                of Total
                <a href="javascript:void()" class="">{{ totalPage }}</a> page(s)
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row my-3">
          <div class="col-md-3">
            <input
              v-model="search.name"
              type="text"
              class="form-control"
              name="search[name]"
              placeholder="Search by name"
              autocomplete="off"
              @input="searchFilter()"
            />
          </div>
          <div class="col-md-3">
            <input
              v-model="search.email"
              type="text"
              class="form-control"
              name="search[email]"
              placeholder="Search by email"
              autocomplete="off"
              @input="searchFilter()"
            />
          </div>
          <div class="col-md-3">
            <input
              v-model="search.gender"
              type="text"
              class="form-control"
              name="search[gender]"
              placeholder="Search by gender"
              autocomplete="off"
              @input="searchFilter()"
            />
          </div>
          <div class="col-md-3">
            <button class="btn btn-warning float-end" @click="resetAll()">
              Reset All
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- <div style="max-height: 500px; overflow-y: scroll"> -->
    <div class="row">
      <div class="col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th @click="sortOrder('id')" class="sortable">
                #
                <font-awesome-icon
                  v-if="this.order.id === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.id === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.id === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th @click="sortOrder('name')" class="sortable">
                Name
                <font-awesome-icon
                  v-if="this.order.name === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.name === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.name === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th @click="sortOrder('email')" class="sortable">
                Email
                <font-awesome-icon
                  v-if="this.order.email === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.email === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.email === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th @click="sortOrder('gender')" class="sortable">
                Gender
                <font-awesome-icon
                  v-if="this.order.gender === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.gender === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.gender === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th @click="sortOrder('dob')" class="sortable">
                Dob
                <font-awesome-icon
                  v-if="this.order.dob === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.dob === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="this.order.dob === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th>Image</th>
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
                <!-- {{ student.image !== '' ? uploadPath + student.image : '' }} -->
                <img
                  v-if="student.image !== '' && student.image !== undefined"
                  :src="uploadPath + student.image"
                  alt="Image Not Found"
                  class="uploadImg"
                />
              </td>
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
      totalPage: 1,
      currentPageItemsTotal: 0,
      search: {
        name: '',
        email: '',
        gender: '',
      },
      perPage: 10,
      order: {
        id: '',
        name: '',
        email: '',
        gender: '',
        dob: '',
      },
      searchQueryString: '',
      uploadPath: '',
      gotoPage: '',
    }
  },
  head() {
    return {
      title: 'List All Students',
    }
  },
  computed: {
    totalPageFun() {
      return Math.ceil(this.students['hydra:totalItems'] / this.perPage)
    },
  },
  watch: {
    students(newData) {
      this.totalPage = newData['hydra:totalItems']
      this.currentPageItemsTotal = newData['hydra:member'].length
    },
    perPage(newVal, oldVal) {
      this.gotoPage = ''
    },
    gotoPage(newVal, oldVal) {
      if (newVal > this.totalPageFun) {
        alert('Total ' + this.totalPageFun + ' page(s) available')
        this.gotoPage = oldVal
      }
    },
  },

  mounted() {
    this.uploadPath = this.$config.API_URL + this.$config.uploadPath + '/'
    this.getStudents()
    this.searchFilter = debounce(this.searchFilter, 300)
    // console.log(this.$axios.defaults.baseURL)
  },

  methods: {
    resetAll() {
      this.gotoPage = ''
      this.perPage = 10
      this.search.name = ''
      this.search.email = ''
      this.search.gender = ''
      this.currentPage = 1
      this.order.id = ''
      this.order.name = ''
      this.order.email = ''
      this.order.gender = ''
      this.order.dob = ''
      this.searchFilter()
    },
    sortOrder(field) {
      if (this.order[field] === '') {
        this.order[field] = 'DESC'
      } else if (this.order[field] === 'ASC') {
        this.order[field] = 'DESC'
      } else if (this.order[field] === 'DESC') {
        this.order[field] = 'ASC'
      }
      this.searchFilter()
    },
    searchFilter() {
      if (this.gotoPage !== '' && this.gotoPage !== this.currentPage) {
        this.currentPage = this.gotoPage
      }
      this.searchQueryString = ''
      for (const key in this.search) {
        if (this.search[key] !== '') {
          this.searchQueryString += [key] + '=' + this.search[key] + '&'
        }
      }
      for (const orderKey in this.order) {
        if (this.order[orderKey] !== '') {
          this.searchQueryString +=
            'order[' + [orderKey] + ']' + '=' + this.order[orderKey] + '&'
        }
      }
      this.getStudents(this.currentPage)
    },
    async getStudents(currentPage) {
      let pageNumber = 1
      const searchString = this.searchQueryString
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
          if (
            response.data['hydra:member'].length === undefined ||
            response.data['hydra:member'].length === 0
          ) {
            if (pageNumber > 1) {
              this.currentPage = pageNumber - 1
              this.getStudents(this.currentPage)
            } else {
              this.updateStudents(response.data)
            }
          } else {
            this.updateStudents(response.data)
          }
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
.uploadImg {
  height: 50px;
  width: 50px;
  transition: 0.3s ease;
}
.uploadImg:hover {
  transform: scale(1.5);
}
</style>
// custom style applied to the bootstrap-vue pagination component (because component not yet available in bootstrap v5)
<style>
.customPaginationClass button.page-link {
  padding-top: 9px !important;
  padding-bottom: 9px !important;
}
</style>
