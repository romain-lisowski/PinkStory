<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ t('update-profile-picture') }}
    </p>
    <div class="flex justify-center my-4">
      <span v-if="userLoggedIn.image" class="relative">
        <img class="h-40 w-40 rounded-full" :src="userLoggedIn.image" />
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
        >{{ userLoggedIn.name[0].toUpperCase() }}</span
      >
    </div>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        type="file"
        name="picture"
        class="my-5 p-3 rounded-md bg-primary bg-opacity-100 opacity-100"
        @change="uploadUserImageChanged"
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
import { computed, ref } from 'vue'

export default {
  setup() {
    const store = useStore()
    const uploadProfilePicture = ref(null)

    const userLoggedIn = computed(() => {
      return store.state.auth.state.userLoggedIn
    })

    const uploadUserImageChanged = (event) => {
      if (event.target.files) {
        const uploadedFile = event.target.files[0]
        uploadProfilePicture.value = uploadedFile
      }
    }
    const deleteProfilePicture = async () => {
      await ApiUsers.deleteimage(store.state.auth.state.jwt)
      store.dispatch('auth/fetchCurrentUser')
    }
    const processForm = async () => {
      await ApiUsers.updateImage(
        store.state.auth.state.jwt,
        uploadProfilePicture.value
      )
      store.dispatch('auth/fetchCurrentUser')
    }

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-profile-picture': 'Image',
          'new-email': 'Nouvel email',
          'delete-profile-picture': 'Supprimer',
          update: 'Modifier',
        },
      },
    })

    return {
      userLoggedIn,
      uploadProfilePicture,
      uploadUserImageChanged,
      deleteProfilePicture,
      processForm,
      t,
    }
  },
}
</script>
