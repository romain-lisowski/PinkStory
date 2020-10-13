<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ $t('update-email') }}
    </p>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        v-model="email"
        :placeholder="$t('new-email')"
        type="email"
        name="email"
        class="my-5 p-3 text-primary-inverse bg-primary-inverse rounded-md"
      />
      <button
        class="mt-3 py-4 text-lg font-light tracking-wide text-primary bg-accent rounded-lg"
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
    "update-email": "Email",
    "new-email": "Nouvel email",
    "update": "Modifier"
  }
}
</i18n>
