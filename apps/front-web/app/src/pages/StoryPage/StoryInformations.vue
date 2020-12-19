<template>
  <div class="flex flex-col items-center">
    <p class="text-3xl md:text-5xl font-bold">{{ t('informations') }}</p>
    <div class="flex flex-wrap justify-evenly gap-8 mt-6">
      <div
        class="py-8 px-6 md:p-8 lg:p-12 bg-primary-inverse bg-opacity-5 rounded-xl"
      >
        <img
          class="mx-auto w-32 md:w-48 object-cover rounded-2xl"
          :src="require(`@/assets/images/${story.imagePath}`)"
        />
        <p class="py-4 text-xl md:text-2xl font-bold">
          {{ story.parentTitle }}
        </p>
        <div class="flex">
          <div class="flex flex-col pr-6 border-r-2">
            <span class="text-base md:text-lg">{{ t('reader-reviews') }}</span>
            <span class="text-xl md:text-2xl font-bold"
              >{{ story.rating }} / 5</span
            >
          </div>
          <div class="flex flex-col pl-6">
            <span class="text-base md:text-lg">{{ t('chapters') }}</span>
            <span class="text-xl md:text-2xl font-bold">{{
              story.nbChapters
            }}</span>
          </div>
        </div>
      </div>

      <div
        class="py-6 px-8 md:p-8 lg:p-12 bg-primary-inverse bg-opacity-5 rounded-xl"
      >
        <img
          class="mx-auto p-1/2 md:p-1 w-24 md:w-32 border-2 border-accent rounded-full"
          :src="require('@/assets/images/profil.jpg')"
        />
        <p class="py-4 text-xl md:text-2xl font-bold">{{ story.author }}</p>
        <div class="flex">
          <div class="flex flex-col pr-6 border-r-2">
            <span class="text-base md:text-lg">{{ t('registration') }}</span>
            <span class="text-xl md:text-2xl font-bold">{{
              story.registration
            }}</span>
          </div>
          <div class="flex flex-col pl-6">
            <span class="text-base md:text-lg">{{ t('stories') }}</span>
            <span class="text-xl md:text-2xl font-bold">{{
              story.nbStories
            }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StoryInformations',
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
    "informations": "Informations",
    "reader-reviews": "Avis des lecteurs",
    "chapters": "Chapitres",
    "stories": "Histoires",
    "registration": "Inscription"
  }
}
</i18n> -->
