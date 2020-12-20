<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ t('update-profile-picture') }}
    </p>
    <div class="flex justify-center my-4">
      <span v-if="userProfilePicture" class="relative">
        <img class="h-40 w-40 rounded-full" :src="userProfilePicture" />
        <button
          class="mt-2 text-accent underline"
          @click="deleteProfilePicture"
        >
          {{ t('delete-profile-picture') }}
        </button>
      </span>

      <span
        v-else
        class="h-40 w-40 flex items-center justify-center text-4xl font-bold bg-accent bg-opacity-100 rounded-full"
        >{{ userName[0].toUpperCase() }}</span
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
        {{ t('update') }}
      </button>
    </form>
  </div>
</template>

<script>
import { useStore } from 'vuex'
import ApiUsers from '@/api/ApiUsers'
import { useI18n } from 'vue-i18n'
import { computed, reactive } from 'vue'

export default {
  setup() {
    const store = useStore()

    const data = reactive({
      uploadProfilePicture: null,
    })

    const userName = computed(() => {
      return store.getters.userName
    })

    const userProfilePicture = computed(() => {
      return store.getters.userProfilePicture
    })

    const uploadProfilePictureChange = (event) => {
      if (event.target.files) {
        const uploadedFile = event.target.files[0]
        data.uploadProfilePicture = uploadedFile
      }
    }
    const deleteProfilePicture = async () => {
      await ApiUsers.deleteProfilePicture(store.state.jwt)
      store.dispatch('fetchCurrentUser')
    }
    const processForm = async () => {
      await ApiUsers.updateProfilePicture(
        store.state.jwt,
        data.uploadProfilePicture
      )
      store.dispatch('fetchCurrentUser')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-profile-picture': 'Image',
          'new-email': 'Nouvel email',
          update: 'Modifier',
          'delete-profile-picture': 'Supprimer',
        },
      },
    })

    return {
      ...data,
      userName,
      userProfilePicture,
      uploadProfilePictureChange,
      deleteProfilePicture,
      processForm,
      t,
    }
  },
}
</script>
