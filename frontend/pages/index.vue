<template>
  <div>
    <h3 class="my-3 my-md-1">Students</h3>
    <ul>
      <li class="d-flex align-item-center flex-row position-relative">
        <nuxt-link
          class="header-links stretched-link text-decoration-none"
          to="/students"
          >List<span>
            <small class="d-flex align-items-center"
              >List all students</small
            ></span
          ></nuxt-link
        >
      </li>
      <li class="d-flex align-item-center flex-row position-relative ms-4">
        <nuxt-link
          class="header-links stretched-link text-decoration-none"
          to="/students/create"
          >Create<span>
            <small class="d-flex align-items-center"
              >Create New Student</small
            ></span
          ></nuxt-link
        >
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Index',
  layout: '~/layout/default',
  middleware: 'auth',
  mounted() {
    console.log('Index mounted.')
    // this.$echo.channel('channel1').on('new-greeting', (e) => {
    //   console.log(e.message);
    // })
  },
}
</script>

<style scoped>
ul li {
  margin-bottom: 10px;
  height: 80px;
  width: 150px;
  padding: 10px;
  transition: 0.3s ease;
  float: left;
  border-bottom: 1px solid #39b982;
  border-radius: 0px;
}

ul li:hover {
  border-radius: 6px;
  border: 1px solid #39b982;
  box-shadow: 1px 1px 10px 0px #39b982b3;
}
ul span {
  font-style: inherit;
  font-family: inherit;
  margin-left: 10px;
}

@media (max-width: 425px) {
  ul li {
    border-radius: 6px;
    border: 1px solid #39b982;
    box-shadow: 1px 1px 10px 0px #39b982b3;
  }
}
</style>
