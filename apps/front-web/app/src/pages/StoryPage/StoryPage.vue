<template>
  <div>
    <StoryHeader :story="data.story" />

    <div class="relative w-full">
      <StoryHeaderBottom :story="data.story" />

      <div class="flex flex-col items-center">
        <StoryContent :story="data.story" />
        <StoryInformations :story="data.story" />
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
import ApiStories from '@/api/ApiStories'
import { onMounted, reactive, watch } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'

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
    const route = useRoute()
    const store = useStore()
    const data = reactive({
      story: {},
    })

    const fetchStory = async (storyId) => {
      const responseSearchStories = await ApiStories.get(
        store.state.jwt,
        storyId
      )

      if (responseSearchStories.ok) {
        data.story = responseSearchStories.story
      }
    }

    onMounted(() => {
      fetchStory(props.storyId)
    })

    watch(
      () => route.params,
      async (newParams) => {
        await fetchStory(newParams.storyId)
      }
    )

    return { data }
  },
}
</script>
