export default {
  // target: 'static',
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'frontend',
    htmlAttrs: {
      lang: 'en',
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' },
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/main.scss',
  ],

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/eslint
    '@nuxtjs/eslint-module',
    '@nuxtjs/fontawesome',
    '@nuxtjs/pwa',
    '@nuxtjs/color-mode',
    '@nuxtjs/svg',
  ],
  // ssr: true,
  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/bootstrap
    'bootstrap-vue/nuxt',
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/toast',
  ],
  colorMode: {
    preference: 'system', // default value of $colorMode.preference
    fallback: 'light', // fallback value if not system preference found
    hid: 'nuxt-color-mode-script',
    globalName: '__NUXT_COLOR_MODE__',
    componentName: 'ColorScheme',
    classPrefix: '',
    classSuffix: '-mode',
    storageKey: 'nuxt-color-mode'
  },
  pwa:{
    meta: {
      start_url: '/',
      scope: '.',
      useWebmanifestExtension: true,
      name: 'NuxtJs FrontEnd Demo',
      description: 'NuxtJs Frontend',
      theme_color: '#4DBA87',
      lang: 'en',
      ogHost: 'http://localhost:3000',
      ogImage: 'http://localhost:3000/icon.png',
    },
    manifest: {
      name: 'NuxtJs FrontEnd Demo',
      short_name: 'NuxtJs FrontEnd Demo',
      description: 'NuxtJs Frontend',
      lang: 'en',
      display: 'standalone',
    },
    icon: {
      fileName: 'icon.png',
      sizes: [512],
    },
    // workbox: {
    //   dev: true // or use a global variable to track the current NODE_ENV, etc to determine dev mode
    // }
  },
  toast: {
    position: 'top-right',
    theme: 'bubble',
    duration: 5000,
    action : {
      text : 'Cancel',
        onClick : (e, toastObject) => {
            toastObject.goAway(0);
        }
    },
    iconPack: 'callback',
    register: [ // Register custom toasts
      {
        name: 'my-error',
        message: 'Oops...Something went wrong',
        options: {
          type: 'error'
        }
      }
    ]
  },
  fontawesome: {
    icons: {
      solid: true,
      brands: true,
    },
  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {},

  axios: {
    baseURL: process.env.API_URL || 'http://localhost:8000',
    headers: {
      Accept: 'application/ld+json',
    },
  },

  publicRuntimeConfig: {
    API_URL: process.env.API_URL || 'http://localhost:8000',
    uploadPath: '/uploads',
  },

  auth: {
    strategies: {
      local: {
        token: {
          property: 'token',
          global: true,
          required: true,
          type: 'Bearer',
        },
        user: {
          property: 'data.username',
          // property: ''
          // autoFetch: true
        },
        endpoints: {
          login: {
            url: '/authentication_token',
            method: 'post',
            propertyName: 'token',
          },
          // logout: { url: '/logout', method: 'post' },
          user: { url: '/api/user', method: 'get', propertyName: 'data' },
          logout: false,
        },
      },
    },
  },
}
