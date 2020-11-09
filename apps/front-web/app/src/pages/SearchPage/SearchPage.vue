<template>
  <div class="mb-10">
    <CategoryBlock />
    <div class="mx-8">
      <StoryList />
    </div>
  </div>
</template>

<script>
import CategoryBlock from '@/components/category/CategoryBlock.vue'
import StoryList from '@/components/story/StoryList.vue'

export default {
  name: 'SearchPage',
  components: {
    CategoryBlock,
    StoryList,
  },
  data() {},
}
</script>

<i18n>
{
  "fr": {
    "search": "Rechercher"
  }
}
</i18n>
