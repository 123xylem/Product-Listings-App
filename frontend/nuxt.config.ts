export default defineNuxtConfig({
  modules: ["@nuxtjs/tailwindcss"],

  app: {
    head: {
      title: "Listings App",
      meta: [
        { charset: "utf-8" },
        { name: "viewport", content: "width=device-width, initial-scale=1" },
        { name: "description", content: "A modern listings application" },
      ],
    },
  },

  typescript: {
    strict: true,
  },

  runtimeConfig: {
    public: {
      apiBaseUrl: process.env.NUXT_PUBLIC_API_BASE_URL,
    },
  },

  compatibilityDate: "2025-04-01",
});
