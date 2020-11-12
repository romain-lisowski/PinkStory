<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ $t('update-picture') }}
    </p>
    <div class="flex justify-center my-4">
      <img
        v-if="userPicture"
        class="rounded-full"
        :src="require('@/assets/images/profil.jpg')"
      />
      <span
        v-else
        class="px-12 py-8 text-4xl font-bold bg-accent bg-opacity-100 rounded-full"
        >{{ userName[0] }}</span
      >
    </div>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        type="file"
        name="picture"
        class="my-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
        @change="uploadPictureChange"
      />
      <button
        class="mt-3 py-4 text-lg font-light tracking-wide text-primary bg-accent bg-opacity-100 rounded-lg"
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
  name: 'UserUpdatePicture',
  data() {
    return {
      userName: this.$store.state.user.name,
      userPicture: this.$store.state.user.picture,
      uploadPicture: null,
    }
  },
  methods: {
    uploadPictureChange(event) {
      this.uploadPicture = event.target.files
      console.log(event.target.files)
    },
    processForm() {
      ApiUsers.updatePicture(this.$store.state.jwt, this.picture)
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "update-picture": "Image",
    "new-email": "Nouvel email",
    "update": "Modifier"
  }
}
</i18n>
