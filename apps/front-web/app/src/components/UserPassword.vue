<template>
  <div>
    <h3>Password</h3>
    <form @submit.prevent="processForm">
      <div>
        <label>
          Current password :
          <input type="password" name="password-old" v-model="passwordOld" />
        </label>
      </div>
      <div>
        <label>
          New password :
          <input type="password" name="password-new" v-model="passwordNew" />
        </label>
      </div>
      <div>
        <label>
          Confirm new password :
          <input type="password" name="password-new-confirm" v-model="passwordNewConfirm" />
        </label>
      </div>
      <button type="submit">Update</button>
    </form>
  </div>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'UserPassword',
  data() {
    return {
      passwordOld: '',
      passwordNew: '',
      passwordNewConfirm: '',
    }
  },
  methods: {
    processForm() {
      ApiUsers.updatePassword(
        this.passwordOld,
        this.passwordNew,
        this.passwordNewConfirm,
      )
    },
  },
}
</script>
