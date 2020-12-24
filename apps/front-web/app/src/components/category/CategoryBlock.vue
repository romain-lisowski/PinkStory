<template>
  <Suspense>
    <template #default>
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

          <slot name="footer"></slot>
        </div>
      </div>
    </template>

    <template #fallback>
      <div class="sm:mt-16 pb-4 sm:pb-12 bg-primary-inverse bg-opacity-5">
        <div class="mx-12">
          {{ t('loading') }}
        </div>
      </div>
    </template>
  </Suspense>
</template>

<script>
import CategoryList from '@/components/category/CategoryList.vue'
import ApiStoryThemes from '@/api/ApiStoryThemes'
import { useStore } from 'vuex'
import { useI18n } from 'vue-i18n'
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
        store.state.jwt
      )

      if (responseSearchStoryThemes.ok) {
        data.categoryLists = responseSearchStoryThemes.story_themes
      }
    })

    const { t } = useI18n({
      locale: 'fr',
      messages: {
        fr: {
          loading: 'Chargement des catégories ...',
        },
      },
    })

    return { data, t }
  },
}
</script>
