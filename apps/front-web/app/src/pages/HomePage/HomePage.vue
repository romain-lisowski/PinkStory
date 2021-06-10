<template>
  <div>
    <Suspense>
      <HomeStoryMain />
    </Suspense>

    <StoryList
      :with-story-list-order="false"
      :search-order="'ORDER_POPULAR'"
      :search-sort="'DESC'"
      :title="t('top-rated-title')"
      :link="t('top-rated-link')"
    />
    <StoryList
      :with-story-list-order="false"
      :search-order="'ORDER_CREATED_AT'"
      :search-sort="'DESC'"
      :title="t('latest-stories-title')"
      :link="t('latest-stories-link')"
    />

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
import StoryList from '@/components/story/StoryList.vue'
import CategoryBlock from '@/components/category/CategoryBlock.vue'
import { useI18n } from 'vue-i18n'

export default {
  components: {
    HomeStoryMain,
    StoryList,
    CategoryBlock,
  },
  setup() {
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'top-rated-title': 'Vous pourriez aussi aimer',
          'top-rated-link': 'Voir les mieux notées',
          'latest-stories-title': 'Les dernières histoires',
          'latest-stories-link': 'Voir les nouvelles histoires',
          categories: 'Catégories',
          'search-story': 'Rechercher des histoires',
        },
      },
    })

    return { t }
  },
}
</script>
