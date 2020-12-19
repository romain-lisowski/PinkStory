<template>
  <li
    class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 px-6 pb-10 sm:pb-8 xl:pb-4 p-4 rounded-2xl cursor-pointer"
  >
    <a>
      <img
        class="w-full h-48 sm:h-64 object-cover object-top rounded-2xl opacity-90"
        :src="require(`@/assets/images/${story.imagePath}`)"
      />

      <span class="block font-semibold text-xl sm:text-2xl leading-tight mt-4">
        {{ story.title }}
      </span>

      <span class="block mt-1 text-sm sm:text-base">
        {{ t('write-by') }} {{ story.author
        }}<span v-if="story.gender === 'female'">&#9792;</span
        ><span v-else>&#9794;</span>
      </span>
      <span class="block mt-1 text-lg sm:text-xl text-accent">
        {{ story.categories }}
      </span>
      <span class="flex justify-between mt-1">
        <UiRatingStars :rating="story.rating" class="flex-1" />
        <span class="text-base sm:text-lg mr-2 sm:mt-2">
          {{ story.nbComments }} {{ t('comments') }}
        </span>
      </span>
    </a>
  </li>
</template>

<script>
import UiRatingStars from '@/components/ui/UiRatingStars.vue'

export default {
  name: 'StoryListItem',
  components: {
    UiRatingStars,
  },
  props: {
    story: {
      type: Object,
      required: true,
    },
  },
}
</script>

<!-- <i18n>
{
  "fr": {
    "write-by": "Par",
    "comments": "avis",
    "read-more" : "Lire la suite"
  }
}
</i18n> -->
