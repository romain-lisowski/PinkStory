<template>
  <div>
    <HomeStoryMain />
    <StoryTopRated />
    <StoryLatest />
    <CategoryBlock>
      <template #header>
        <div
          class="flex flex-col sm:flex-row justify-between mb-8 pt-8 sm:pt-12"
        >
          <p class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
            {{ t('categories') }}
          </p>
          <router-link
            :to="{ name: 'Search' }"
            class="pt-2 sm:pt-4 xl:pt-6 text-lg sm:text-xl xl:text-2xl text-left sm:text-right text-accent cursor-pointer"
          >
            {{ t('search-story') }} >
          </router-link>
        </div>
      </template>
    </CategoryBlock>
  </div>
</template>

<script>
import HomeStoryMain from '@/pages/HomePage/HomeStoryMain.vue'
import StoryTopRated from '@/components/story/StoryTopRated.vue'
import StoryLatest from '@/components/story/StoryLatest.vue'
import CategoryBlock from '@/components/category/CategoryBlock.vue'
import { useI18n } from 'vue-i18n'

export default {
  components: {
    HomeStoryMain,
    StoryTopRated,
    StoryLatest,
    CategoryBlock,
  },
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          categories: 'Catégories',
          'search-story': 'Rechercher des histoires',
        },
      },
    })

    return { t }
  },
}
</script>
