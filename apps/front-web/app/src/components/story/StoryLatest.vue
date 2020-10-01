<template>
  <div class="mx-12">
    <div class="flex flex-row justify-between mt-12">
      <h3 class="text-5xl text-psblack font-psbold text-left ">
        &#8677; {{ $t('latest-stories') }}
      </h3>
      <a class="text-psred text-2xl pt-4 cursor-pointer">
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
