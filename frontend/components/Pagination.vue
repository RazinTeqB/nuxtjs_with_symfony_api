<template>
  <nav id="pagination">
    <ul v-if="this.totalPageCount" class="page-numbers">
      <li
        v-for="num in this.pageNumbers"
        :key="num"
        :style="{ width: 100 / pageNumberCount + '%' }"
      >
        <!-- <span v-if="isPageNumber(num)"> -->
          <nuxt-link
            v-if="num != $route.query.page && num != currentPage"
            :to="{ path: '/', query: { page: num } }"
            >{{ num }}</nuxt-link
          >
          <span v-else>{{ num }}</span>
        <!-- </span> -->
      </li>
    </ul>
    <ul v-if="this.totalPageCount != 1" class="page-guides">
      <li>
        <nuxt-link
          v-if="$route.query.page != 1 && $route.query.page"
          :to="{ path: '/', query: { page: 1 } }"
          >First</nuxt-link
        >
        <span v-else> First </span>
      </li>
      <li>
        <nuxt-link
          v-if="this.prevpage != null"
          :to="{ path: '/', query: { page: this.prevpage } }"
          >&laquo; Next</nuxt-link
        >
        <span v-else> & laquo; Previous </span>
      </li>
      <li>
        <nuxt-link
          v-if="
            this.nextpage != null &&
            $route.query.page != this.totalPageCount
          "
          :to="{ path: '/', query: { page: this.nextpage } }"
          >Next &raquo;</nuxt-link
        >
        <span v-else> Next & raquo; </span>
      </li>
      <li>
        <nuxt-link
          v-if="$route.query.page != this.totalPageCount"
          :to="{ path: '/', query: { page: this.totalPageCount } }"
          >Last</nuxt-link
        >
        <span v-else>Last</span>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'Pagination',
  data() {
    return {
      prevpage: null,
      nextpage: null,
      currentPage: null,
      pageNumbers: [],
      pageNumberCount: 0,
    }
  },
  props: {
    totalPageCount: {
      type: Number,
      default: 0,
    },
  },
  computed: {

  },
  mounted() {
    this.setPageNumbers()
  },

  methods: {
     isPageNumber(num) {
      return num != null
    },
    setPages(currentPage, totalPageCount) {
      this.prevpage = currentPage > 1 ? currentPage - 1 : null
      if (!totalPageCount) {
        this.nextpage = this.$route.query.page
          ? parseInt(this.$route.query.page) + 1
          : 2
      } else {
        this.nextpage =
          currentPage < totalPageCount ? parseInt(currentPage) + 1 : null
      }

      for (let i = 0; i < 7; i++) {
        const _p = parseInt(currentPage) - 4 + i
        if (_p > 0 && _p <= totalPageCount) {
          this.pageNumbers.push(_p)
          this.pageNumberCount++
        } else this.pageNumbers.push(null)
      }
    },
    setPageNumbers() {
      const _currentPage = this.$route.query.page ? this.$route.query.page : 1
      this.currentPage = _currentPage
      this.setPages(_currentPage, this.$store.state.totalPageCount)
    },
  },
}
</script>
