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

export default {
  components: {
    CategoryList,
  },
  data() {
    return {
      categoryLists: [],
    }
  },
  created() {
    this.fetchStoryThemes()
  },
  methods: {
    async fetchStoryThemes() {
      const apiStoryThemeSearchData = await useApiStoryThemeSearch(this.$store)

      if (!apiStoryThemeSearchData.error.value) {
        this.categoryLists = apiStoryThemeSearchData.response.value.story_themes
      }
    },
  },
}
</script>
