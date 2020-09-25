<template>
  <form @submit.prevent="processForm">
    <div>
      <h3 class="text-gray-700 font-semibold text-2xl mt-2">
        Sign up
      </h3>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            placeholder="Pseudo"
            type="text"
            name="name"
            v-model="name"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            placeholder="Email"
            type="email"
            name="email"
            v-model="email"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            placeholder="Password"
            type="password"
            name="password"
            v-model="password" autocomplete="'autocomplete'"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
      <div class="m-5 flex justify-end">
        <label class="flex flex-1 text-gray-700 font-normal">
          <input
            placeholder="Confirm password"
            type="password"
            name="passwordConfirm"
            v-model="passwordConfirm" autocomplete="'autocomplete'"
            class="flex-1 border rounded-md placeholder-gray-600 border-gray-400 p-2"
          />
        </label>
      </div>
    </div>
    <button
     type="submit"
     class="mt-2 mb-4 bg-transparent text-gray-700 hover:text-white py-2 px-4 border border-pink-400 hover:border-transparent hover:bg-pink-400 rounded-md"
    >
      Submit</button>
  </form>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'SignUp',
  data() {
    return {
      name: '',
      email: '',
      password: '',
      passwordConfirm: '',
    }
  },
  methods: {
    processForm() {
      ApiUsers.signUp(this.name, this.email, this.password, this.passwordConfirm)
    },
  },
}
</script>
