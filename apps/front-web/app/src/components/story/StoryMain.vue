<template>
  <div
    class="bg-gradient-to-r bg-no-repeat bg-color-white -mt-32 mb-20"
    :style="{
      'background-position-x': '35rem',
      'background-size': '70%',
      'background-image':
        `radial-gradient(
          70% 50% at 70% 40%,
          rgba(255, 255, 255, 0) 25%,
          rgba(255, 255, 255, 1) 100%),
        url(${require('@/assets/images/1.jpg')})`
    }"
  >
    <div class="mx-12 pt-48">
      <h1 class="text-left text-psblack font-extrabold leading-none text-8xl w-3/5 font-psbold">
        {{ story.title }}
      </h1>
      <div class="text-left text-psblack font-extrabold text-3xl mt-2">
        18+
        <span class="font-normal mx-2">|</span>
        {{ story.date }}
        <span class="font-normal mx-2">|</span>
        {{ story.author }}
        <span
          v-if="story.gender === 'female'"
          class="font-medium"
        >&#9792;</span><span
          v-else
          class="font-medium"
        >&#9794;</span>
      </div>
      <div class="text-left text-psred-lighter mt-2 text-4xl tracking-wide">
        {{ story.categories }}
      </div>

      <RatingStars
        :rating="story.rating"
        class="w-1/4 -ml-2 mt-2"
      />

      <div class="text-left mt-8 text-2xl tracking-wide text-psblack w-2/3 leading-10">
        {{ story.abstract }}
      </div>
      <button class="block mt-8 mx-0">
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
