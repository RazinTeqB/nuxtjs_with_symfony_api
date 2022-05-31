<template>
  <div>
    {{ this.students }}
  </div>
</template>

<script>
// import axios from 'axios'
export default {
  name: 'List',
  data() {
    return {
      students: [],
    }
  },
  head() {
    return {
      title: 'List All Students',
    }
  },

  mounted() {
    this.getStudents()
    // console.log(this.$axios.defaults.baseURL)
  },

  methods: {
    async getStudents() {
      await this.$axios
        .get('/api/students')
        .then((response) => {
          this.students = response.data
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
}
</script>
