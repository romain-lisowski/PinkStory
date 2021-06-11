<template v-if="story">
  <li
    class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-6 pb-10 sm:pb-8 xl:pb-4 p-4 rounded-2xl cursor-pointer"
  >
    <a>
      <img
        v-if="story.story_image"
        class="w-full h-48 sm:h-64 object-cover object-top rounded-2xl opacity-90"
        :src="story.story_image.image_url"
      />

      <span class="block font-semibold text-xl sm:text-2xl leading-tight mt-4">
        {{ story.title }}
      </span>

      <span class="block mt-1 text-sm sm:text-base">
        {{ t('write-by') }} {{ story.user.name
        }}<span v-if="story.user && story.user.gender === 'FEMALE'"
          >&#9792;</span
        ><span v-else>&#9794;</span>
        <span class="mx-2 font-normal">|</span>
        <span>{{ dayJs(story.created_at).format('DD/MM/YYYY HH[h]mm') }}</span>
      </span>
      <span class="block mt-1 text-lg sm:text-xl text-accent">
        {{ story.story_themes.map((theme) => theme.title).join(', ') }}
      </span>
      <span class="flex justify-start mt-1">
        <UiRatingStars :rating="story.rate" />
      </span>
    </a>
  </li>
</template>

<script>
import UiRatingStars from '@/components/ui/UiRatingStars.vue'
import { useI18n } from 'vue-i18n'
import dayJs from 'dayjs'

export default {
  components: {
    UiRatingStars,
  },
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'write-by': 'Par',
          'read-more': 'Lire la suite',
        },
      },
    })
    return { dayJs, t }
  },
}
</script>
