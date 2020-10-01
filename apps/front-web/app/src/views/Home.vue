<template>
  <div>
    <StoryMain />
    <StoryTopRated />
    <StoryLatest />
    <Category />
  </div>
</template>

<script>

import StoryMain from '@/components/story/StoryMain.vue'
import StoryTopRated from '@/components/story/StoryTopRated.vue'
import StoryLatest from '@/components/story/StoryLatest.vue'
import Category from '@/components/category/Category.vue'

export default {
  name: 'Home',
  components: {
    StoryMain,
    StoryTopRated,
    StoryLatest,
    Category,
  },
}
</script>
