<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ $t('update-profile-picture') }}
    </p>
    <div class="flex justify-center my-4">
      <span v-if="getUserProfilePicture" class="relative">
        <img class="rounded-full h-48 w-48" :src="getUserProfilePicture" />
        <button
          class="mt-2 text-accent underline"
          @click="deleteProfilePicture"
        >
          {{ $t('delete-profile-picture') }}
        </button>
      </span>

      <span
        v-else
        class="px-12 py-8 text-4xl font-bold bg-accent bg-opacity-100 rounded-full"
        >{{ getUserName[0].toUpperCase() }}</span
      >
    </div>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        type="file"
        name="picture"
        class="my-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
        @change="uploadProfilePictureChange"
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
import { mapGetters } from 'vuex'
import ApiUsers from '@/api/ApiUsers'

export default {
  name: 'UserUpdateProfilePicture',
  data() {
    return {
      uploadProfilePicture: null,
    }
  },
  computed: {
    ...mapGetters(['getUserName', 'getUserProfilePicture']),
  },
  methods: {
    uploadProfilePictureChange(event) {
      if (event.target.files) {
        const uploadedFile = event.target.files[0]
        this.uploadProfilePicture = uploadedFile
      }
    },
    async deleteProfilePicture() {
      await ApiUsers.deleteProfilePicture(this.$store.state.jwt)
      this.$store.dispatch('fetchCurrentUser')
    },
    async processForm() {
      await ApiUsers.updateProfilePicture(
        this.$store.state.jwt,
        this.uploadProfilePicture
      )
      this.$store.dispatch('fetchCurrentUser')
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "update-profile-picture": "Image",
    "new-email": "Nouvel email",
    "update": "Modifier",
    "delete-profile-picture": "Supprimer"
  }
}
</i18n>
