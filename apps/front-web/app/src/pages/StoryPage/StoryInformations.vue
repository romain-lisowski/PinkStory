<template>
  <div class="flex flex-col items-center">
    <p class="text-3xl md:text-5xl font-bold">{{ t('informations') }}</p>
    <div class="flex flex-wrap justify-evenly gap-8 mt-6">
      <div
        class="py-8 px-6 md:p-8 lg:p-12 bg-primary-inverse bg-opacity-5 rounded-xl"
      >
        <img class="mx-auto w-32 md:w-48 object-cover rounded-2xl" />
        <p class="py-4 text-xl md:text-2xl font-bold">story.parentTitle</p>
        <div class="flex">
          <div class="flex flex-col pr-6 border-r-2">
            <span class="text-base md:text-lg">{{ t('reader-reviews') }}</span>
            <span class="text-xl md:text-2xl font-bold"
              >{{ story.rate }} / 5</span
            >
          </div>
          <div class="flex flex-col pl-6">
            <span class="text-base md:text-lg">{{ t('chapters') }}</span>
            <span class="text-xl md:text-2xl font-bold">99</span>
          </div>
        </div>
      </div>

      <div
        v-if="story.user"
        class="py-6 px-8 md:p-8 lg:p-12 bg-primary-inverse bg-opacity-5 rounded-xl"
      >
        <img
          class="mx-auto p-1/2 md:p-1 w-24 md:w-32 border-2 border-accent rounded-full"
          :src="require('@/assets/images/profil.jpg')"
        />
        <p class="py-4 text-xl md:text-2xl font-bold">
          {{ story.user.name }}
        </p>
        <div class="flex">
          <div class="flex flex-col pr-6 border-r-2">
            <span class="text-base md:text-lg">{{ t('registration') }}</span>
            <span class="text-xl md:text-2xl font-bold">{{
              createdAtFormatted
            }}</span>
          </div>
          <div class="flex flex-col pl-6">
            <span class="text-base md:text-lg">{{ t('stories') }}</span>
            <span class="text-xl md:text-2xl font-bold">99</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import dayJs from 'dayjs'

export default {
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const createdAtFormatted = computed(() => {
      return dayJs(props.story.created_at).format('DD/MM/YYYY HH:mm')
    })

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          informations: 'Informations',
          'reader-reviews': 'Avis des lecteurs',
          chapters: 'Chapitres',
          stories: 'Histoires',
          registration: 'Inscription',
        },
      },
    })
    return { createdAtFormatted, t }
  },
}
</script>
