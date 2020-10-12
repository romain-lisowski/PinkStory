<template>
  <div id="main" class="relative mb-12 sm:mb-20">
    <div
      class="absolute inset-0 left-auto w-full md:w-4/5 lg:w-3/4 bg-center bg-cover opacity-50"
      :style="{
        'background-image': `url(${require('@/assets/images/1.jpg')})`,
      }"
    >
      <div class="absolute inset-0 bg-radial-gradient-left"></div>
    </div>

    <div class="relative mx-12 pt-32 sm:pt-40 xl:pt-40">
      <h1
        class="text-4xl sm:text-7xl xl:text-8xl xl:w-3/5 font-extrabold text-left leading-tight sm:leading-none"
      >
        {{ story.title }}
      </h1>
      <p
        class="mt-2 text-base sm:text-2xl xl:text-3xl text-left font-extrabold"
      >
        18+
        <span class="mx-2 font-normal">|</span>
        {{ story.date }}
        <span class="mx-2 font-normal">|</span>
        {{ story.author }}
        <span v-if="story.gender === 'female'">&#9792;</span
        ><span v-else class="font-medium">&#9794;</span>
      </p>
      <p
        class="mt-2 text-xl sm:text-3xl xl:text-4xl text-left text-accent tracking-wide"
      >
        {{ story.categories }}
      </p>

      <RatingStars :rating="story.rating" class="mt-2" />

      <p
        class="sm:w-4/5 xl:w-2/3 mt-8 text-base sm:text-xl xl:text-2xl text-justify sm:text-left tracking-wide w-full leading-6 sm:leading-8 xl:leading-10"
      >
        {{ story.abstract }}
      </p>

      <button
        class="block m-1 mt-8 mx-0 sm:py-5 py-4 px-8 bg-accent rounded-lg text-lg sm:text-xl font-light tracking-wide"
      >
        {{ $t('read-now') }}
      </button>
    </div>
  </div>
</template>

<script>
import RatingStars from '@/components/story/RatingStars.vue'

export default {
  name: 'StoryMain',
  components: {
    RatingStars,
  },
  data() {
    return {
      story: {
        author: 'Estelle48',
        gender: 'female',
        date: '26/09/2020',
        title: 'Chantage et soumission',
        categories: 'Hétéro, Triolisme, Hard et 4 autres',
        rating: 4.5,
        abstract: `Cette histoire s'est passée l'an dernier et dure depuis.
        Je me présente Estelle, 48 ans, mariée avec deux grandes filles de 26 et 23 ans.
        Mon mari Marc et moi vivons dans un petit lotissement en région parisienne.
        Ma grande fille Cathy est marié depuis deux ans avec Laurent,
        bel homme qu'elle a rencontré sur les bancs de la fac.`,
      },
    }
  },
}
</script>

<i18n>
{
  "fr": {
    "read-now": "Lire maintenant"
  }
}
</i18n>
