<template>
  <div>
    <h3 class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ $t('update-informations') }}
    </h3>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        v-model="name"
        placeholder="Pseudo"
        type="text"
        name="name"
        :autocomplete="'nickname'"
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
  name: 'UserUpdateInformation',
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

<i18n>
{
  "fr": {
    "update-informations": "Informations",
    "pseudo": "Pseudo",
    "update": "Modifier"
  }
}
</i18n>
