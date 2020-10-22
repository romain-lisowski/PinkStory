<template>
  <div>
    <HomeMainStory />
    <StoryTopRated />
    <StoryLatest />
    <HomeCategory />
  </div>
</template>

<script>
import HomeMainStory from '@/pages/HomePage/HomeMainStory.vue'
import StoryTopRated from '@/components/story/StoryTopRated.vue'
import StoryLatest from '@/components/story/StoryLatest.vue'
import HomeCategory from '@/pages/HomePage/HomeCategory.vue'

export default {
  name: 'HomePage',
  components: {
    HomeMainStory,
    StoryTopRated,
    StoryLatest,
    HomeCategory,
  },
}
</script>
