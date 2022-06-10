<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12 p-3">
        <h3 style="display: inline-block" class="">Students list</h3>
        <nuxt-link
          class="btn btn-sm btn-primary float-end position-relative translate-middle top-50"
          to="/students/create"
          >Create</nuxt-link
        >
      </div>
      <div class="col-md-12 border-bottom rounded">
        <div
          class="container-fluid my-4 d-flex flex-md-row flex-column justify-content-between align-items-center"
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
              class="customPaginationClass"
              @change="getStudents"
            ></b-pagination>
          </div>

          <div class="flex-item my-3 my-md-0">
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
              <label for="perPageCount" class="form-label">Per Page</label>
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
              class="form-control mt-2 mt-md-0"
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
              class="form-control mt-2 mt-md-0"
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
              class="form-control mt-2 mt-md-0"
              name="search[gender]"
              placeholder="Search by gender"
              autocomplete="off"
              @input="searchFilter()"
            />
          </div>
          <div class="col-md-3 mt-2 mt-md-0 text-center text-md-end">
            <button class="btn btn-warning" @click="resetAll()">
              Reset All
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- <div style="max-height: 500px; overflow-y: scroll"> -->
    <div class="row">
      <div class="col-md-12" style="overflow-x: scroll">
        <table class="table">
          <thead>
            <tr>
              <th class="sortable" @click="sortOrder('id')">
                #
                <font-awesome-icon
                  v-if="order.id === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.id === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.id === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th class="sortable" @click="sortOrder('name')">
                Name
                <font-awesome-icon
                  v-if="order.name === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.name === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.name === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th class="sortable" @click="sortOrder('email')">
                Email
                <font-awesome-icon
                  v-if="order.email === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.email === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.email === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th class="sortable" @click="sortOrder('gender')">
                Gender
                <font-awesome-icon
                  v-if="order.gender === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.gender === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.gender === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th class="sortable" @click="sortOrder('dob')">
                Dob
                <font-awesome-icon
                  v-if="order.dob === ''"
                  :icon="['fas', 'sort']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.dob === 'ASC'"
                  :icon="['fas', 'sort-up']"
                  class="float-end table-sort-icon"
                />
                <font-awesome-icon
                  v-else-if="order.dob === 'DESC'"
                  :icon="['fas', 'sort-down']"
                  class="float-end table-sort-icon"
                />
              </th>
              <th>Image</th>
              <th>Linked User</th>
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
                <nuxt-link
                  v-if="student.user !== undefined"
                  :to="'/users/' + student.user.id"
                  >{{ student.user.username }}</nuxt-link
                >
              </td>
              <td>
                <div class="d-flex flex-row justify-content-around">
                  <nuxt-link :to="'/students/' + student.id">
                    <font-awesome-icon
                      :icon="['fas', 'eye']"
                      title="Show Student"
                      class="fs-4 flex-item me-3"
                    />
                  </nuxt-link>
                  <nuxt-link :to="'/students/' + student.id + '/update'">
                    <font-awesome-icon
                      :icon="['fas', 'pencil']"
                      title="Update Student"
                      class="fs-4 flex-item me-3"
                    />
                  </nuxt-link>
                  <a
                    class=""
                    href="#"
                    title="Delete Student"
                    @click.prevent="deleteStudent(student.id)"
                  >
                    <font-awesome-icon
                      :icon="['fas', 'trash']"
                      class="fs-4 flex-item"
                    />
                  </a>
                </div>
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
import { BPagination } from 'bootstrap-vue'

export default {
  name: 'List',
  components: {
    BPagination,
  },
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
        // alert('Total ' + this.totalPageFun + ' page(s) available')
        this.$toast.info('Total ' + this.totalPageFun + ' page(s) available')
        this.gotoPage = oldVal
      }
    },
  },

  mounted() {
    this.uploadPath = this.$config.API_URL + this.$config.uploadPath + '/'
    this.getStudents()
    this.searchFilter = debounce(this.searchFilter, 300)
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
            this.$toast.success('Student deleted successfully')
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
// custom style applied to the bootstrap-vue pagination component (because
component not yet available in bootstrap v5)
<style>
.customPaginationClass button.page-link {
  padding-top: 9px !important;
  padding-bottom: 9px !important;
}
</style>
