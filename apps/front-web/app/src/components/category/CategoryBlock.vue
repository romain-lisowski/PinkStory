<template>
  <div
    id="category"
    class="sm:mt-16 pb-4 sm:pb-12 bg-primary-inverse bg-opacity-5"
  >
    <div class="mx-12">
      <slot name="header"></slot>

      <CategoryList
        v-for="(categoryList, index) in categoryLists"
        :key="index"
        :category-list="categoryList"
      />
    </div>
  </div>
</template>

<script>
import CategoryList from '@/components/category/CategoryList.vue'
import useApiStoryThemeSearch from '@/composition/api/storyTheme/useApiStoryThemeSearch'
import { useStore } from 'vuex'

export default {
  components: {
    CategoryList,
  },
  async setup() {
    const store = useStore()
    let categoryLists = null

    const apiStoryThemeSearchFetch = await useApiStoryThemeSearch(store)
    if (apiStoryThemeSearchFetch.ok.value) {
      categoryLists = apiStoryThemeSearchFetch.response.value.story_themes
    }

    return { categoryLists }
  },
}
</script>
