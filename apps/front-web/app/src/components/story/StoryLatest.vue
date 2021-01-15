<template>
  <div class="mx-8 sm:mx-12">
    <div class="flex flex-col sm:flex-row justify-between">
      <p class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
        {{ t('latest-stories') }}
      </p>
      <a
        class="pt-2 sm:pt-4 xl:pt-6 text-lg sm:text-xl xl:text-2xl text-left sm:text-right text-accent cursor-pointer"
      >
        {{ t('see-latest-stories') }} >
      </a>
    </div>
    <StoryList :search-order="searchOrder" :search-sort="searchSort">
      <template #StoryListOrder>&nbsp;</template>
    </StoryList>
  </div>
</template>

<script>
import StoryList from '@/components/story/StoryList.vue'
import { useI18n } from 'vue-i18n'

export default {
  components: {
    StoryList,
  },
  setup() {
    const searchOrder = 'ORDER_CREATED_AT'
    const searchSort = 'ASC'
    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          'latest-stories': 'Les dernières histoires',
          'see-latest-stories': 'Voir les nouvelles histoires',
        },
      },
    })

    return { searchOrder, searchSort, t }
  },
}
</script>
