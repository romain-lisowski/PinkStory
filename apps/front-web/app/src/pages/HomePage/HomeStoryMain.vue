<template>
  <div id="main" class="relative mb-12 sm:mb-20">
    <div
      class="absolute inset-0 left-auto w-full md:w-4/5 lg:w-3/4 bg-center bg-cover opacity-50"
      :style="{
        'background-image': `url(${data.imageUrl})`,
      }"
    >
      <div class="absolute inset-0 bg-radial-gradient-left"></div>
    </div>

    <div class="relative mx-8 sm:mx-12 pt-32 sm:pt-40 xl:pt-40">
      <h1
        class="text-4xl sm:text-7xl xl:text-8xl xl:w-3/5 font-extrabold text-left leading-tight sm:leading-none"
      >
        {{ data.story.title }}
      </h1>
      <p
        class="mt-2 text-base sm:text-2xl xl:text-3xl text-left font-extrabold"
      >
        18+
        <span class="mx-2 font-normal">|</span>
        {{ data.createdAtFormatted }}
        <span class="mx-2 font-normal">|</span>
        <span>{{ data.userName }}</span>
        <span v-if="'TODO: gender' === 'female'">&#9792;</span
        ><span v-else class="font-medium">&#9794;</span>
      </p>
      <p class="mt-2 text-xl sm:text-3xl xl:text-4xl text-left text-accent">
        {{ data.storyCategories }}
      </p>

      <UiRatingStars :rating="data.story.rate" class="mt-2" />

      <p
        class="sm:w-4/5 xl:w-2/3 mt-8 text-base sm:text-xl xl:text-2xl text-justify sm:text-left w-full leading-6 sm:leading-8 xl:leading-10"
      >
        {{ data.extract }}
      </p>

      <router-link
        :to="{ name: 'Story', params: { storyId: data.story.id } }"
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
import ApiStories from '@/api/ApiStories'
import { computed, onMounted, reactive } from 'vue'
import { useStore } from 'vuex'
import dayJs from 'dayjs'

export default {
  components: {
    UiRatingStars,
  },
  setup() {
    const data = reactive({
      story: [],
    })

    onMounted(async () => {
      const store = useStore()
      const responseSearchStories = await ApiStories.search(store.state.jwt, {
        order: 'ORDER_POPULAR',
        sort: 'DESC',
        limit: 1,
      })

      if (responseSearchStories.ok) {
        ;[data.story] = responseSearchStories.stories
      }

      data.storyCategories = computed(() => {
        const themes = data.story.story_themes.map((theme) => theme.title)
        return themes.join(', ')
      })

      data.userName = computed(() => {
        return data.story.user.name
      })

      data.imageUrl = computed(() => {
        return data.story.story_image.image_url
      })

      data.createdAtFormatted = computed(() => {
        return dayJs(data.story.created_at).format('DD/MM/YYYY HH:mm')
      })
    })

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'read-now': 'Lire maintenant',
        },
      },
    })

    return { data, t }
  },
}
</script>
