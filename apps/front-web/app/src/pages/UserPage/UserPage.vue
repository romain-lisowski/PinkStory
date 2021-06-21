<template>
  <div class="flex flex-col justify-center items-center">
    <p class="mt-32 font-bold text-4xl sm:text-5xl lg:text-5xl">
      {{ t('update-profile') }}
    </p>
    <Suspense>
      <div class="w-3/4 sm:w-2/3 md:w-1/2 xl:w-1/3 my-10">
        <div class="flex flex-col gap-8 items-center justify-center">
          <UserUpdatePicture
            class="mb-4 px-10 pt-6 pb-10 bg-primary-inverse bg-opacity-5 rounded-xl w-full"
          />
          <UserUpdateEmail
            class="mb-4 px-10 pt-6 pb-10 bg-primary-inverse bg-opacity-5 rounded-xl w-full"
          />
          <UserUpdatePassword
            class="mb-4 px-10 pt-6 pb-10 bg-primary-inverse bg-opacity-5 rounded-xl w-full"
          />
          <UserUpdateInformation
            class="mb-4 px-10 pt-6 pb-10 bg-primary-inverse bg-opacity-5 rounded-xl w-full"
          />
        </div>
      </div>
    </Suspense>
  </div>
</template>

<script>
import UserUpdatePicture from '@/pages/UserPage/UserUpdatePicture.vue'
import UserUpdateEmail from '@/pages/UserPage/UserUpdateEmail.vue'
import UserUpdatePassword from '@/pages/UserPage/UserUpdatePassword.vue'
import UserUpdateInformation from '@/pages/UserPage/UserUpdateInformation.vue'
import { useI18n } from 'vue-i18n'

export default {
  components: {
    UserUpdatePicture,
    UserUpdateEmail,
    UserUpdatePassword,
    UserUpdateInformation,
  },
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'update-profile': 'Modification du profil',
        },
      },
    })

    return { t }
  },
}
</script>
