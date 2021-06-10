<template v-if="story">
  <div id="main" class="relative mb-12 sm:mb-20">
    <div
      v-if="story.story_image"
      class="absolute inset-0 left-auto w-full md:w-4/5 lg:w-3/4 bg-center bg-cover opacity-50"
      :style="{
        'background-image': `url(${story.story_image.image_url})`,
      }"
    >
      <div class="absolute inset-0 bg-radial-gradient-left"></div>
    </div>

    <div class="relative mx-8 sm:mx-12 pt-32 sm:pt-40 xl:pt-40">
      <h1
        class="text-4xl sm:text-7xl xl:text-8xl xl:w-3/5 font-extrabold text-left leading-tight sm:leading-none"
      >
        {{ story.title }}
      </h1>
      <p
        class="mt-2 text-base sm:text-2xl xl:text-3xl text-left font-extrabold"
      >
        18+
        <span class="mx-2 font-normal">|</span>
        {{ dayJs(story.created_at).format('DD/MM/YYYY HH[h]mm') }}
        <span class="mx-2 font-normal">|</span>
        <span v-if="story.user">{{ story.user.name }}</span>
        <span v-if="story.user && story.user.gender === 'FEMALE'">&#9792;</span
        ><span v-else class="font-medium">&#9794;</span>
      </p>
      <p class="mt-2 text-xl sm:text-3xl xl:text-4xl text-left text-accent">
        {{ story.story_themes.map((theme) => theme.title).join(', ') }}
      </p>

      <UiRatingStars :rating="story.rate" class="mt-2" />

      <p
        class="sm:w-4/5 xl:w-2/3 mt-8 text-base sm:text-xl xl:text-2xl text-justify sm:text-left w-full leading-6 sm:leading-8 xl:leading-10"
      >
        {{ story.extract }}
      </p>

      <router-link
        :to="{ name: 'Story', params: { storyId: story.id } }"
        tag="button"
        class="block m-1 mt-8 mx-0 sm:py-5 py-4 px-8 w-full sm:w-1/2 lg:w-1/4 bg-accent rounded-lg text-lg sm:text-xl font-light"
      >
        {{ t('read-now') }}
      </router-link>
    </div>
  </div>
</template>

<script>
import UiRatingStars from '@/components/ui/UiRatingStars.vue'
import { useI18n } from 'vue-i18n'
import useApiStorySearch from '@/composition/api/story/useApiStorySearch'
import dayJs from 'dayjs'
import { useStore } from 'vuex'

export default {
  components: {
    UiRatingStars,
  },
  async setup() {
    const store = useStore()
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'read-now': 'Lire maintenant',
        },
      },
    })

    let story = {}
    const apiStorySearchData = await useApiStorySearch(store, {
      order: 'ORDER_POPULAR',
      sort: 'ASC',
      limit: 1,
    })

    if (!apiStorySearchData.error.value) {
      // array destructuring
      ;[story] = apiStorySearchData.response.value.stories
    }

    return { story, dayJs, t }
  },
}
</script>
