export default {
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
    css: [],

    // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
    plugins: [],

    // Auto import components: https://go.nuxtjs.dev/config-components
    components: true,

    // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
    buildModules: [
        // https://go.nuxtjs.dev/eslint
        '@nuxtjs/eslint-module',
        '@nuxtjs/fontawesome',
    ],

    // Modules: https://go.nuxtjs.dev/config-modules
    modules: [
        // https://go.nuxtjs.dev/bootstrap
        'bootstrap-vue/nuxt',
        '@nuxtjs/axios',
        '@nuxtjs/auth-next'
    ],

    fontawesome: {
        icons: {
            solid: true,
            brands: true
        }
    },
    // Build Configuration: https://go.nuxtjs.dev/config-build
    build: {},

    axios: {
        baseURL: process.env.API_URL || "http://localhost:8000",
        headers: {
            Accept: 'application/ld+json'
        }
    },

    publicRuntimeConfig: {
        API_URL: process.env.API_URL || "http://localhost:8000",
        uploadPath: '/uploads'
    },

    auth: {
        strategies: {
            local: {
                token: {
                    property: 'token',
                    global: true,
                    required: true,
                    type: 'Bearer'
                },
                user: {
                    property: 'data.username',
                    // property: ''
                    // autoFetch: true
                },
                endpoints: {
                    login: { url: '/authentication_token', method: 'post', propertyName: 'token' },
                    // logout: { url: '/logout', method: 'post' },
                    user: { url: '/api/user', method: 'get', propertyName: 'data' },
                    logout: false,
                }
            }
        }
    }
}