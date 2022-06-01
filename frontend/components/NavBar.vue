<template>
  <div class="nav">
    <nuxt-link to="/" class="brand">Nuxt Front-End Demo</nuxt-link>
    <nav v-if="isAuthenticated">
      <nuxt-link class="header-links" to="/list">List</nuxt-link>
      <nuxt-link class="header-links" to="/find">Find</nuxt-link>
      <nuxt-link class="header-links" to="/create">Create</nuxt-link>
      <a
        class="header-links"
        href="/logout"
        title="Logout"
        @click.prevent="logout"
      >
        {{ loggedInUser }}
      </a>
    </nav>
    <nav v-else>
      <nuxt-link class="header-links" to="/login">Login</nuxt-link>
    </nav>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
export default {
  name: 'NavBar',
  // layout: '../layout/default',
  computed: {
    ...mapGetters(['isAuthenticated', 'loggedInUser']),
  },

  methods: {
    async logout() {
      if (!confirm('Are you sure you want to logout?')) return
      await this.$auth.logout('local')

      this.$router.push('/login')
    },
  },
}
</script>

<style scoped>
.header-links::after {
  content: '';
  border-right: 1px solid;
  margin-left: 5px;
}
.brand {
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  font-size: 1.5em;
  color: #39b982;
  text-decoration: none;
}
.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;
}
.nav .nav-item {
  box-sizing: border-box;
  margin: 0 5px;
  color: rgba(0, 0, 0, 0.5);
  text-decoration: none;
}
.nav .nav-item.router-link-exact-active {
  color: #39b982;
  border-bottom: solid 2px #39b982;
}
.nav a {
  display: inline-block;
}
</style>
