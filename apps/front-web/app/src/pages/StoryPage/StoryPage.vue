<template>
  <div>
    <StoryHeader v-if="story" :story="story" />

    <div class="relative w-full">
      <StoryHeaderBottom v-if="story" :story="story" />

      <div class="flex flex-col items-center">
        <StoryContent v-if="story" :story="story" />
        <StoryInformations v-if="story" :story="story" />
        <StoryList
          :with-story-list-order="false"
          :search-order="'ORDER_POPULAR'"
          :with-loading-overlay="false"
        />
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
import useApiStoryGet from '@/composition/api/story/useApiStoryGet'

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
  data() {
    return {
      story: null,
    }
  },
  created() {
    this.fetchStory(this.storyId)
  },
  methods: {
    async fetchStory(storyId) {
      const apiStoryGetFetch = await useApiStoryGet(this.$store, storyId)
      if (apiStoryGetFetch.ok.value) {
        this.story = apiStoryGetFetch.response.value.story
      }
    },
  },
}
</script>
