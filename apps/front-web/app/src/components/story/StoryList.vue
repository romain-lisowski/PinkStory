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
import { onMounted, reactive } from 'vue'

export default {
  components: {
    StoryListItem,
  },
  props: {
    order: {
      type: String,
      default: 'ORDER_POPULAR',
    },
    sort: {
      type: String,
      default: 'DESC',
    },
  },
  setup(props) {
    const data = reactive({
      stories: [],
    })

    onMounted(async () => {
      const responseSearchStories = await ApiStories.search(
        props.order,
        props.sort
      )

      if (responseSearchStories.ok) {
        data.stories = responseSearchStories.stories
      }
    })

    return { data }
  },
}
</script>
