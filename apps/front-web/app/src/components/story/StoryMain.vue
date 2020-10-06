<template>
  <div id="main" class="mb-12 sm:mb-20 w-full relative">
    <div
      class="absolute top-0 right-0 bottom-0 w-2/3 lg:w-3/4 bg-center bg-cover opacity-75"
      :style="{
        'background-image': `url(${require('@/assets/images/1.jpg')})`,
      }"
    />
    <div class="absolute top-0 right-0 bottom-0 left-0 ps-gradient" />
    <div class="mx-12 pt-12 sm:pt-16 xl:pt-20 relative">
      <h1
        class="text-left text-default font-extrabold leading-tight sm:leading-none text-5xl sm:text-7xl xl:text-8xl xl:w-3/5 font-psbold"
      >
        {{ story.title }}
      </h1>
      <div
        class="text-left text-default font-extrabold text-xl sm:text-2xl xl:text-3xl mt-2"
      >
        18+
        <span class="font-normal mx-2">|</span>
        {{ story.date }}
        <span class="font-normal mx-2">|</span>
        {{ story.author }}
        <span v-if="story.gender === 'female'" class="font-medium">&#9792;</span
        ><span v-else class="font-medium">&#9794;</span>
      </div>
      <div
        class="text-left text-psred-lighter mt-2 text-2xl sm:text-3xl xl:text-4xl tracking-wide"
      >
        {{ story.categories }}
      </div>
      <RatingStars :rating="story.rating" class="mt-2" />

      <div
        class="text-justify sm:text-left mt-8 text-lg sm:text-xl xl:text-2xl tracking-wide text-default w-full sm:w-4/5 xl:w-2/3 leading-6 sm:leading-8 xl:leading-10"
      >
        {{ story.abstract }}
      </div>
      <button class="block mt-8 mx-0 sm:py-5 sm:text-xl">
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

<style>
.ps-gradient {
  background-image: radial-gradient(
    ellipse farthest-side at 100% 20%,
    rgba(var(--color-bg-primary), 0) 0,
    rgba(var(--color-bg-primary), 0.4) 50%,
    rgba(var(--color-bg-primary), 1) 75%,
    rgba(var(--color-bg-primary), 1) 100%
  );
}
</style>

<i18n>
{
  "fr": {
    "read-now": "Lire maintenant"
  }
}
</i18n>
