<template>
  <div>
    <h3 class="text-gray-700 font-semibold text-2xl mt-2">
      Update email
    </h3>
    <form @submit.prevent="processForm">
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            placeholder="New email"
            type="email"
            name="email"
            v-model="email"
            class="flex-1 border rounded-md border-gray-400 placeholder-gray-600 p-2"
          />
        </label>
      </div>
      <button
        type="submit"
        class="m-5 bg-transparent hover:bg-pink-400 text-gray-700 hover:text-white py-2 px-4 border border-pink-400 hover:border-transparent rounded-md"
      >
        Update
      </button>
    </form>
  </div>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'UserEmail',
  data() {
    return {
      email: this.$store.state.user.email,
    }
  },
  methods: {
    processForm() {
      ApiUsers.updateEmail(this.$store.state.jwt, this.email)
      this.$store.dispatch('logout')
      this.$router.push({ name: 'Auth' })
    },
  },
}
</script>
