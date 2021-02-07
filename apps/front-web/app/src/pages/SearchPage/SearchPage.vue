<template>
  <div class="mb-10">
    <CategoryBlock>
      <template #header>
        <div
          class="flex flex-col sm:flex-row justify-between mb-8 pt-8 sm:pt-12"
        >
          <p
            class="block w-full mt-12 sm:mt-0 text-5xl md:text-5xl xl:text-6xl font-semibold text-center"
          >
            {{ t('search') }}
          </p>
        </div>
      </template>
    </CategoryBlock>
    <div class="mx-8">
      <StoryList
        :search-order="searchOrder"
        :search-category-ids="searchCategoryIds"
      />
    </div>
  </div>
</template>

<script>
import CategoryBlock from '@/components/category/CategoryBlock.vue'
import StoryList from '@/components/story/StoryList.vue'
import { useI18n } from 'vue-i18n'
import { ref } from 'vue'
import { useStore } from 'vuex'

export default {
  name: 'SearchPage',
  components: {
    CategoryBlock,
    StoryList,
  },

  setup() {
    const store = useStore()
    const searchCategoryIds = ref(store.state.site.state.searchCategoryIds)
    const searchOrder = ref(store.state.site.state.searchOrder)

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          search: 'Rechercher par catégorie',
        },
      },
    })

    return { searchCategoryIds, searchOrder, t }
  },
}
</script>
