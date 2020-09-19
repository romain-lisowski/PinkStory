<template>
  <div>
    <h3>Informations</h3>
    <form @submit.prevent="processForm">
      <div>
        <label>
          Pseudo :
          <input type="text" name="name" v-model="name" :autocomplete="'nickname'" />
        </label>
      </div>
      <button type="submit">Update</button>
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
