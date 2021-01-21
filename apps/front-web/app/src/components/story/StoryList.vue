<template>
  <div :class="data.hasTitle ? 'mx-8 sm:mx-12 mb-0 sm:mb-12' : ''">
    <div
      v-if="data.hasTitle"
      class="flex flex-col sm:flex-row justify-between mt-16"
    >
      <p class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
        {{ title }}
      </p>
      <a
        class="pt-2 sm:pt-4 xl:pt-6 text-lg sm:text-xl xl:text-2xl text-left sm:text-right text-accent cursor-pointer"
      >
        {{ link }} ></a
      >
    </div>
    <slot name="StoryListOrder">
      <StoryListOrder :nb-results="data.nbResults" />
    </slot>
    <ul class="flex flex-wrap -mx-6 mt-4 sm:mt-6 xl:mt-8 pt-2 pl-2 text-left">
      <StoryListItem
        v-for="(story, index) in data.stories"
        :key="index"
        :story="story"
      />
    </ul>
  </div>
</template>

<script>
import StoryListOrder from '@/components/story/StoryListOrder.vue'
import StoryListItem from '@/components/story/StoryListItem.vue'
import ApiStories from '@/api/ApiStories'
import { computed, onMounted, reactive, watch } from 'vue'
import { useStore } from 'vuex'

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
    title: {
      type: String,
      default: null,
    },
    link: {
      type: String,
      default: null,
    },
  },
  setup(props) {
    const store = useStore()
    const data = reactive({
      stories: [],
      nbResults: 0,
      searchOrder: props.searchOrder,
    })

    const searchStories = async () => {
      const queryParams = {
        order: data.searchOrder,
        sort: props.searchSort,
        limit: props.searchLimit,
        categoryIds: props.searchCategoryIds,
      }

      const responseSearchStories = await ApiStories.search(
        store.state.jwt,
        queryParams
      )

      if (responseSearchStories.ok) {
        data.stories = responseSearchStories.stories
        data.nbResults = responseSearchStories.stories_total
      }
    }

    data.hasTitle = computed(() => {
      return props.title !== null
    })

    const storeSearchOrder = computed(() => {
      return store.state.searchOrder
    })

    watch(storeSearchOrder, async (value) => {
      if (value) {
        data.searchOrder = value
        await searchStories()
      }
    })

    watch(props.searchCategoryIds, async () => {
      await searchStories()
    })

    onMounted(async () => {
      await searchStories()
    })

    return { data, searchStories }
  },
}
</script>
