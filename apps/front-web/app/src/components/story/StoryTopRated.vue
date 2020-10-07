<template>
  <div class="mx-12">
    <div class="flex flex-col sm:flex-row justify-between mt-16">
      <h3 class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
        {{ $t('best-stories') }}
      </h3>
      <a
        class="text-accent text-lg sm:text-xl xl:text-2xl pt-2 sm:pt-4 xl:pt-6 cursor-pointer text-left sm:text-right"
      >
        {{ $t('see-best-stories') }} ></a
      >
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
