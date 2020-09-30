<template>
  <div>
    <StoryMain />
    <StoryList />
    <StoryList />
    <Category />
  </div>
</template>

<script>

import StoryMain from '@/components/story/StoryMain.vue'
import StoryList from '@/components/story/StoryList.vue'
import Category from '@/components/story/Category.vue'

export default {
  name: 'Home',
  components: {
    StoryMain,
    StoryList,
    Category,
  },
}
</script>
