<template>
  <div>
    <h1>Students</h1>
    <ul>
      <li class="d-flex align-item-center flex-row">
        <nuxt-link class="header-links" to="/list"
          >List<span>
            <small class="d-flex align-items-center"
              >List all students</small
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
}
</script>

<style scoped>
ul span {
  font-style: inherit;
  font-family: inherit;
  margin-left: 10px;
}
</style>
