<template>
  <ul class="flex flex-wrap -mx-6 mt-4 sm:mt-6 xl:mt-8 pt-2 pl-2 text-left">
    <StoryListItem
      v-for="(story, index) in data.stories"
      :key="index"
      :story="story"
    />
  </ul>
</template>

<script>
import StoryListItem from '@/components/story/StoryListItem.vue'
import ApiStories from '@/api/ApiStories'
import { onMounted, reactive, watch } from 'vue'
import { useStore } from 'vuex'

export default {
  components: {
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
      default: null,
    },
  },
  setup(props) {
    const store = useStore()
    const data = reactive({
      stories: [],
    })

    const searchStories = async () => {
      const queryParams = {
        order: props.searchOrder,
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
      }
    }

    watch(props.searchCategoryIds, async () => {
      await searchStories()
    })

    watch(props.searchOrder, async () => {
      await searchStories()
    })

    onMounted(async () => {
      await searchStories()
    })

    return { data }
  },
}
</script>
