<template>
  <div class="mx-12">
    <div class="mt-0 sm:mt-12 flex flex-col sm:flex-row justify-between">
      <h3 class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
        {{ $t('latest-stories') }}
      </h3>
      <a
        class="pt-2 sm:pt-4 xl:pt-6 text-lg sm:text-xl xl:text-2xl text-left sm:text-right text-accent cursor-pointer"
      >
        {{ $t('see-latest-stories') }} >
      </a>
    </div>
    <StoryList />
  </div>
</template>

<script>
import StoryList from '@/components/story/StoryList.vue'

export default {
  name: 'StoryLatest',
  components: {
    StoryList,
  },
}
</script>

<i18n>
{
  "fr": {
    "latest-stories": "Les dernières histoires",
    "see-latest-stories" : "Voir les nouvelles histoires"
  }
}
</i18n>
