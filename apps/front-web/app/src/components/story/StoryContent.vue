<template>
  <div class="w-5/6 lg:w-3/4 pt-12">
    <p
      class="text-justify font-thin text-base sm:text-lg lg:text-xl tracking-wide leading-relaxed"
      v-html="$sanitize(story.content)"
    ></p>

    <span class="text-base sm:text-lg lg:text-xl font-bold"
      >{{ $t('categories') }} :
      <span
        class="text-base sm:text-lg lg:text-xl font-normal text-accent-highlight"
        >{{ story.categories }}</span
      >
    </span>

    <div class="flex justify-between w-full my-12">
      <div class="flex items-center">
        <font-awesome-icon icon="chevron-left" class="text-4xl mr-4" />
        <span class="flex flex-col">
          <span class="text-base sm:text-lg lg:text-xl text-left"
            >{{ $t('chapter') }} 11</span
          >
          <span class="font-bold">{{ story.previousChapter }}</span>
        </span>
      </div>
      <div class="flex items-center">
        <span class="flex flex-col">
          <span class="text-base sm:text-lg lg:text-xl text-right"
            >{{ $t('chapter') }} 13</span
          >
          <span class="font-bold">{{ story.nextChapter }}</span>
        </span>
        <font-awesome-icon icon="chevron-right" class="text-4xl ml-4" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StoryContent',
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
    "categories": "Catégories",
    "chapter": "Chapitre"
  }
}
</i18n>
