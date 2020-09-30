<template>
  <div class="mx-12 ">
    <div class="flex flex-row justify-between gap-4 mt-12">
      <h3 class="text-5xl text-psblack font-psbold text-left ">
        Vous pourriez aussi aimer
      </h3>
      <a class="text-psred text-2xl pt-4">Découvrir la suite</a>
    </div>
    <div class="grid gap-8 grid-flow-col-dense text-left mt-8">
      <StoryListItem />
      <StoryListItem />
      <StoryListItem />
      <StoryListItem />
    </div>
  </div>
</template>

<script>
import StoryListItem from '@/components/story/StoryListItem.vue'

export default {
  name: 'StoryList',
  components: {
    StoryListItem,
  },
}
</script>
