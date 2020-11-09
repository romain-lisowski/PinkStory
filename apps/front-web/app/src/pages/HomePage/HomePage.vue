<template>
  <div>
    <HomeMainStory />
    <StoryTopRated />
    <StoryLatest />
    <CategoryBlock :display-search-link="true" />
  </div>
</template>

<script>
import HomeMainStory from '@/pages/HomePage/HomeMainStory.vue'
import StoryTopRated from '@/components/story/StoryTopRated.vue'
import StoryLatest from '@/components/story/StoryLatest.vue'
import CategoryBlock from '@/components/category/CategoryBlock.vue'

export default {
  name: 'HomePage',
  components: {
    HomeMainStory,
    StoryTopRated,
    StoryLatest,
    CategoryBlock,
  },
}
</script>
