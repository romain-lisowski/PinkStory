<template>
  <form @submit.prevent="processForm">
    <div>
      <h2>Sign up :</h2>
      <div>
        <label>
          Pseudo :
          <input type="text" name="name" v-model="name" />
        </label>
      </div>
      <div>
        <label>
          Email :
          <input type="email" name="email" v-model="email" />
        </label>
      </div>
      <div>
        <label>
          Password :
          <input type="password" name="password" v-model="password" :autocomplete="'autocomplete'"/>
        </label>
      </div>
      <div>
        <label>
          Confirm password :
          <input type="password" name="passwordConfirm" v-model="passwordConfirm" :autocomplete="'autocomplete'"/>
        </label>
      </div>
    </div>
    <button type="submit">Submit</button>
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
