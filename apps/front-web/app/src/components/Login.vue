<template>
  <form @submit.prevent="processForm">
    <div>
      <h3 class="text-gray-700 font-semibold text-2xl mt-2">
        Login
      </h3>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            placeholder="Email"
            type="email"
            name="email"
            v-model="email"
            class="flex-1 border rounded-md border-gray-400 placeholder-gray-600 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            placeholder="Password"
            type="password"
            name="password"
            v-model="password"
            :autocomplete="'off'"
            class="flex-1 border rounded-md border-gray-400 placeholder-gray-600 p-2"
          />
        </label>
      </div>
    </div>
    <button
      type="submit"
      class="m-5 bg-transparent hover:bg-pink-400 text-gray-700 hover:text-white py-2 px-4 border border-pink-400 hover:border-transparent rounded-md"
    >
      Connexion
    </button>
  </form>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    processForm() {
      this.$store.dispatch('login', { email: this.email, password: this.password })
    },
  },
}
</script>
