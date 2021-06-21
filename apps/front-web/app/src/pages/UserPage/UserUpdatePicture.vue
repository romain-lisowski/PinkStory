<template>
  <div>
    <p class="font-bold text-2xl sm:text-3xl lg:text-4xl text-accent">
      {{ t('update-profile-picture') }}
    </p>
    <div class="flex justify-center my-4">
      <span v-if="currentUser.image" class="relative">
        <img class="h-40 w-40 rounded-full" :src="currentUser.image" />
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
        >{{ currentUser.name[0].toUpperCase() }}</span
      >
    </div>
    <form class="flex flex-col" @submit.prevent="processForm">
      <input
        type="file"
        name="picture"
        class="mt-5 p-4 rounded-md bg-primary bg-opacity-100 opacity-100"
        @change="uploadUserImageChanged"
      />
      <button
        class="mt-8 py-4 text-lg font-light tracking-wide text-primary bg-accent bg-opacity-100 rounded-lg"
        type="submit"
      >
        {{ t('update') }}
      </button>
    </form>
  </div>
</template>

<script>
import useApiUserUpdateImage from '@/composition/api/user/useApiUserUpdateImage'
import useApiUserDeleteImage from '@/composition/api/user/useApiUserDeleteImage'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
import { computed, ref } from 'vue'

export default {
  setup() {
    const store = useStore()
    const uploadProfilePicture = ref(null)

    const currentUser = computed(() => {
      return store.state.auth.state.currentUser
    })

    const uploadUserImageChanged = (event) => {
      if (event.target.files) {
        const uploadedFile = event.target.files[0]
        uploadProfilePicture.value = uploadedFile
      }
    }
    const deleteProfilePicture = async () => {
      const jwt = store.getters['auth/getJwt']
      await useApiUserDeleteImage(store, { jwt })
      store.dispatch('auth/signIn', jwt)
    }
    const processForm = async () => {
      const jwt = store.getters['auth/getJwt']
      await useApiUserUpdateImage(store, {
        jwt,
        image: uploadProfilePicture.value,
      })
      store.dispatch('auth/signIn', jwt)
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
      currentUser,
      uploadProfilePicture,
      uploadUserImageChanged,
      deleteProfilePicture,
      processForm,
      t,
    }
  },
}
</script>
