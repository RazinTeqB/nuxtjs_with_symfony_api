<template>
  <div class="nav">
    <nuxt-link to="/" class="brand">Nuxt Front-End Demo</nuxt-link>
    <nav v-if="isAuthenticated">
      <!-- <nuxt-link class="header-links" to="/students">List</nuxt-link> -->
      <!-- <nuxt-link class="header-links" to="/students/create">Create</nuxt-link> -->
      <b-nav-item-dropdown right class="header-links" ref="navdropdown">
        <!-- Using 'button-content' slot -->
        <template #button-content>
          <em>User</em>
        </template>
        <a
          class="dropdown-item"
          @click.prevent="onClickNavItem('/students/create')"
        >
          Create
        </a>
        <a class="dropdown-item" @click.prevent="onClickNavItem('/students/')">
          List
        </a>
      </b-nav-item-dropdown>
      <a
        class="header-links"
        href="/logout"
        title="Logout"
        @click.prevent="logout"
      >
        {{ loggedInUser }}
      </a>
      <ColorModePicker />
    </nav>
    <nav v-else>
      <nuxt-link class="header-links" to="/login">Login</nuxt-link>
      <nuxt-link class="header-links" to="/register">Register</nuxt-link>
      <ColorModePicker />
    </nav>
  </div>
</template>
<script>
import { mapGetters } from 'vuex'
import { BNavItemDropdown } from 'bootstrap-vue'
// import 'bootstrap/dist/js/bootstrap.js'
import ColorModePicker from '@/components/ColorModePicker'

export default {
  name: 'NavBar',
  // layout: '../layout/default',
  components: {
    ColorModePicker,
    BNavItemDropdown,
  },
  head() {
    return {
      script: [{ src: '/bootstrap/bootstrap.min.js', type: 'text/javascript' }],
    }
  },
  computed: {
    ...mapGetters(['isAuthenticated', 'loggedInUser']),
  },

  methods: {
    async logout() {
      if (!confirm('Are you sure you want to logout?')) return
      await this.$auth.logout('local')

      this.$router.push('/login')
    },
    onClickNavItem(redirectRoute) {
      // Close the menu and (by passing true) return focus to the toggle button
      this.$refs.navdropdown.hide(true)
      this.$router.push(redirectRoute)
    },
  },
}
</script>

<style scoped>
a.header-links::after {
  content: '';
  border-right: 1px solid;
  margin-left: 5px;
}
li.header-links,
li.header-links a.nav-link,
li.header-links * {
  border: none;
  color: #39b982 !important;
  text-decoration: none;
}
li.header-links:active,
li.header-links:focus,
li.header-links:focus-visible
 {
  border: none;
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
.nav a,
.nav li.header-links {
  display: inline-block;
}

@media (max-width: 425px) {
  .brand {
    font-size: 1em;
  }
  .header-links {
    font-size: 0.8em;
  }
  .header-links::after {
    margin-left: 8px;
  }
}
</style>
