<template>
  <div class="mx-12">
    <div class="flex flex-col sm:flex-row justify-between mt-16">
      <h3 class="text-3xl sm:text-5xl text-psblack dark:text-pswhite font-psbold text-left ">
        {{ $t('best-stories') }}
      </h3>
      <a class="text-psred text-lg sm:text-2xl pt-2 sm:pt-4 cursor-pointer text-left sm:text-right">
        {{ $t('see-best-stories') }} ></a>
    </div>
    <StoryList />
  </div>
</template>

<script>
import StoryList from '@/components/story/StoryList.vue'

export default {
  name: 'StoryTopRated',
  components: {
    StoryList,
  },
}
</script>

<i18n>
{
  "fr": {
    "best-stories": "Vous pourriez aussi aimer",
    "see-best-stories": "Voir les mieux notées"
  }
}
</i18n>
