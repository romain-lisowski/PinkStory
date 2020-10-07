<template>
  <div>
    <h3 class="font-semibold text-2xl mt-2">
      {{ $t('Informations') }}
    </h3>
    <form @submit.prevent="processForm">
      <div class="m-5 flex justify-end">
        <label class="flex flex-1">
          <input
            v-model="name"
            placeholder="Pseudo"
            type="text"
            name="name"
            :autocomplete="'nickname'"
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
    "informations": "Informations",
    "pseudo": "Pseudo",
    "update": "Modifier"
  }
}
</i18n>
