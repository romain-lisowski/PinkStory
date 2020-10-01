<template>
  <div class="mx-12">
    <div class="flex flex-row justify-between mt-12">
      <h3 class="text-5xl text-psblack font-psbold text-left ">
        &#x2764; {{ $t('best-stories') }}
      </h3>
      <a class="text-psred text-2xl pt-4 cursor-pointer">
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
