<template>
  <div :class="title ? 'mx-8 sm:mx-12 mb-0 sm:mb-12' : ''">
    <div v-if="title" class="flex flex-col sm:flex-row justify-between mt-16">
      <p class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
        {{ title }}
      </p>
      <a
        class="pt-2 sm:pt-4 xl:pt-6 text-lg sm:text-xl xl:text-2xl text-left sm:text-right text-accent cursor-pointer"
      >
        {{ link }} ></a
      >
    </div>
    <div v-if="withStoryListOrder">
      <StoryListOrder :nb-results="nbResults" />
    </div>
    <ul class="flex flex-wrap -mx-6 mt-4 sm:mt-6 xl:mt-8 pt-2 pl-2 text-left">
      <StoryListItem
        v-for="(story, index) in stories"
        :key="index"
        :story="story"
      />
    </ul>
  </div>
</template>

<script>
import StoryListOrder from '@/components/story/StoryListOrder.vue'
import StoryListItem from '@/components/story/StoryListItem.vue'
import useApiStorySearch from '@/composition/api/story/useApiStorySearch'
import { useStore } from 'vuex'
import { computed, ref, watch } from 'vue'

export default {
  components: {
    StoryListOrder,
    StoryListItem,
  },
  props: {
    searchOrder: {
      type: String,
      default: 'ORDER_POPULAR',
    },
    searchLimit: {
      type: Number,
      default: 6,
    },
    withStoryListOrder: {
      type: Boolean,
      default: true,
    },
    withLoadingOverlay: {
      type: Boolean,
      default: true,
    },
    title: {
      type: String,
      default: null,
    },
    link: {
      type: String,
      default: null,
    },
  },
  async setup(props) {
    const store = useStore()
    const stories = ref(null)
    const nbResults = ref(0)

    const storeSearchOrder = computed(() => {
      return store.getters['site/getSearchOrder']
    })
    const storeSearchCategoryIds = computed(() => {
      return store.getters['site/getSearchCategoryIds']
    })
    const refreshingSearchCategory = computed(() => {
      return store.getters['site/refreshingSearchCategory']
    })

    const searchStories = async () => {
      const apiStorySearchFetch = await useApiStorySearch(
        store,
        {
          order: storeSearchOrder.value,
          sort: 'DESC',
          limit: props.searchLimit,
          story_theme_ids: storeSearchCategoryIds.value,
        },
        props.withLoadingOverlay
      )

      if (apiStorySearchFetch.ok.value) {
        stories.value = apiStorySearchFetch.response.value.stories
        nbResults.value = apiStorySearchFetch.response.value.stories_total
      }
    }

    watch(storeSearchOrder, () => {
      searchStories()
    })

    watch(refreshingSearchCategory, () => {
      searchStories()
    })

    searchStories()

    return {
      stories,
      nbResults,
    }
  },
}
</script>
