<template>
  <div>
    <h3>Email</h3>
    <form @submit.prevent="processForm">
      <div>
        <label>
          Email :
          <input type="email" name="email" v-model="email" />
        </label>
      </div>
      <button type="submit">Update</button>
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
      ApiUsers.updateEmail(this.email)
      this.$store.dispatch('logout')
      this.$router.push({ name: 'Auth' })
    },
  },
}
</script>
