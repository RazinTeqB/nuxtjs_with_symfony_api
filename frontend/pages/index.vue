<template>
  <div>
    <h1>Students</h1>
    <ul>
      <li class="d-flex align-item-center flex-row position-relative">
        <nuxt-link class="header-links stretched-link text-decoration-none" to="/list"
          >List<span>
            <small class="d-flex align-items-center"
              >List all students</small
            ></span
          ></nuxt-link
        >
      </li>
      <li class="d-flex align-item-center flex-row position-relative">
        <nuxt-link class="header-links stretched-link text-decoration-none" to="/create"
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
}
</script>

<style scoped>
ul li {
  margin-bottom: 10px;
  height: 80px;
  width: 150px;
  padding: 10px;
  transition: 0.3s ease;
  border-radius: 6px;
}
ul li:hover {
  border: 1px solid #39b982;
  box-shadow: 1px 1px 10px 0px #39b982b3;
}
ul span {
  font-style: inherit;
  font-family: inherit;
  margin-left: 10px;
}
</style>
