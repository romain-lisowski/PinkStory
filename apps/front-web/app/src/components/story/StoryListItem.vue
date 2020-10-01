<template>
  <div class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-6 pb-4">
    <img
      class="rounded-lg opacity-90 object-cover object-top h-64 w-full"
      :src="require(`@/assets/images/${story.imagePath}`)"
    >
    <div class="text-psblack font-psbold text-2xl leading-tight mt-4">
      {{ story.title }}
    </div>
    <div class="text-psblack mt-1">
      {{ $t('write-by') }} {{ story.author }}<span v-if="story.gender === 'female'">&#9792;</span><span v-else>&#9794;</span>
    </div>
    <div class="text-psred text-xl mt-1">
      {{ story.categories }}
    </div>
    <div class="flex justify-between mt-1 text-psblack">
      <div class="flex-1">
        <RatingStars :rating="story.rating" />
      </div>
      <div class="text-lg mr-2">
        {{ story.nbComments }} {{ $t('comments') }}
      </div>
    </div>

    <div class="text-justify mt-4 ">
      {{ story.abstract }}
    </div>
    <div class="text-psred text-xl mt-4 pr-2 text-right">
      {{ $t('read-more') }} >
    </div>
  </div>
</template>

<script>
import RatingStars from '@/components/story/RatingStars.vue'

export default {
  name: 'StoryListItem',
  components: {
    RatingStars,
  },
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
}
</script>

<i18n>
{
  "fr": {
    "write-by": "Par",
    "comments": "avis",
    "read-more" : "Lire la suite"
  }
}
</i18n>
