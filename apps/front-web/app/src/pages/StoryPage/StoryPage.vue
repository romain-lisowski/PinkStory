<template>
  <div>
    <StoryHeader :story="story" />

    <div class="relative w-full">
      <StoryHeaderBottom :story="story" />

      <div class="flex flex-col items-center">
        <StoryContent :story="story" />
        <StoryInformations :story="story" />
        <StoryList :search-order="'ORDER_POPULAR'" :search-sort="'DESC'" />
      </div>
    </div>
  </div>
</template>

<script>
import StoryHeader from '@/pages/StoryPage/StoryHeader.vue'
import StoryHeaderBottom from '@/pages/StoryPage/StoryHeaderBottom.vue'
import StoryContent from '@/pages/StoryPage/StoryContent.vue'
import StoryInformations from '@/pages/StoryPage/StoryInformations.vue'
import StoryList from '@/components/story/StoryList.vue'
import useApiStoriesGet from '@/composition/apiStories/useApiStoriesGet'
import { onMounted } from 'vue'

export default {
  components: {
    StoryHeader,
    StoryHeaderBottom,
    StoryContent,
    StoryInformations,
    StoryList,
  },
  props: {
    storyId: {
      type: String,
      required: true,
    },
  },
  setup(props) {
    let story = {}

    const fetchStory = async (storyId) => {
      const { response, error } = await useApiStoriesGet(storyId)

      if (!error.value) {
        story = response.value.story
      }
    }

    onMounted(async () => {
      await fetchStory(props.storyId)
    })

    return { story }
  },
}
</script>
