<template>
  <div
    id="category"
    class="sm:mt-16 pb-4 sm:pb-12 bg-primary-inverse bg-opacity-5"
  >
    <div class="mx-12">
      <slot name="header"></slot>

      <CategoryList
        v-for="(categoryList, index) in data.categoryLists"
        :key="index"
        :category-list="categoryList"
      />
    </div>
  </div>
</template>

<script>
import CategoryList from '@/components/category/CategoryList.vue'
import ApiStoryThemes from '@/api/ApiStoryThemes'
import { useStore } from 'vuex'
import { onMounted, reactive } from 'vue'

export default {
  components: {
    CategoryList,
  },
  setup() {
    const store = useStore()
    const data = reactive({
      categoryLists: [],
    })

    onMounted(async () => {
      const responseSearchStoryThemes = await ApiStoryThemes.search(
        'fr',
        store.state.auth.state.jwt
      )

      if (responseSearchStoryThemes.ok) {
        data.categoryLists = responseSearchStoryThemes.story_themes
      }
    })

    return { data }
  },
}
</script>
