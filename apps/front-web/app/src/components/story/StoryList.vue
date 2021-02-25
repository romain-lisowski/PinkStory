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
import { mapGetters } from 'vuex'

export default {
  components: {
    StoryListOrder,
    StoryListItem,
  },
  props: {
    withStoryListOrder: {
      type: Boolean,
      default: true,
    },
    searchOrder: {
      type: String,
      default: 'ORDER_POPULAR',
    },
    searchSort: {
      type: String,
      default: 'DESC',
    },
    searchLimit: {
      type: Number,
      default: 6,
    },
    searchCategoryIds: {
      type: Array,
      default: () => [],
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
  data() {
    return {
      stories: [],
      nbResults: 0,
      localSearchOrder: this.searchOrder,
      localSearchCategoryIds: this.searchCategoryIds,
    }
  },
  computed: {
    ...mapGetters({
      storeSearchOrder: 'site/getSearchOrder',
      storeSearchCategoryIds: 'site/getSearchCategoryIds',
      refreshingSearchCategory: 'site/refreshingSearchCategory',
    }),
  },
  watch: {
    storeSearchOrder(value) {
      if (value) {
        this.localSearchOrder = value
        this.searchStories()
      }
    },
    refreshingSearchCategory() {
      this.localSearchCategoryIds = this.storeSearchCategoryIds
      this.searchStories()
    },
  },
  created() {
    this.searchStories()
  },
  methods: {
    async searchStories() {
      const { response, error } = await useApiStorySearch(
        this.$store,
        {
          order: this.localSearchOrder,
          sort: this.searchSort,
          limit: this.searchLimit,
          categoryIds: this.localSearchCategoryIds,
        },
        this.withLoadingOverlay
      )

      if (!error.value) {
        this.stories = response.value.stories
        this.nbResults = response.value.stories_total
      }
    },
  },
}
</script>
