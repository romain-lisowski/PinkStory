<template>
  <div class="mx-12">
    <div class="flex flex-col sm:flex-row justify-between mt-6 sm:mt-12">
      <h3 class="text-3xl sm:text-5xl text-psblack dark:text-pswhite font-psbold text-left ">
        {{ $t('latest-stories') }}
      </h3>
      <a class="text-psred text-lg sm:text-2xl pt-2 sm:pt-4 cursor-pointer text-left sm:text-right">
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
