<template>
  <div>
    <h3 class="text-gray-700 font-semibold text-2xl mt-2">
      Update informations
    </h3>
    <form @submit.prevent="processForm">
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700">
          <input
            placeholder="Pseudo"
            type="text"
            name="name"
            v-model="name"
            :autocomplete="'nickname'"
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
  name: 'UserInformation',
  data() {
    return {
      name: this.$store.state.user.name,
    }
  },
  methods: {
    processForm() {
      ApiUsers.updateInformation(this.$store.state.jwt, this.name)
    },
  },
}
</script>
