<template>
  <div>
    <h3 class="font-semibold text-2xl mt-2">
      {{ $t('Email') }}
    </h3>
    <form @submit.prevent="processForm">
      <div class="m-5 flex justify-end">
        <label class="flex flex-1">
          <input
            v-model="email"
            :placeholder="$t('new-email')"
            type="email"
            name="email"
            class="flex-1 border rounded-md border-gray-400 p-2"
          />
        </label>
      </div>
      <button
        class="bg-accent text-primary rounded-lg m-1 py-4 px-8 text-lg font-light tracking-wide"
        type="submit"
      >
        {{ $t('update') }}
      </button>
    </form>
  </div>
</template>

<script>
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'UserUpdateEmail',
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

<i18n>
{
  "fr": {
    "email": "Email",
    "new-email": "Nouvel email",
    "update": "Modifier"
  }
}
</i18n>
