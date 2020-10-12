<template>
  <div
    id="category"
    class="sm:mt-16 pb-4 sm:pb-12 bg-primary-inverse bg-opacity-5"
  >
    <div class="mx-12">
      <div class="flex flex-col sm:flex-row justify-between mb-8 pt-8 sm:pt-12">
        <h3 class="text-3xl sm:text-4xl xl:text-5xl font-semibold text-left">
          {{ $t('categories') }}
        </h3>
        <a
          class="pt-2 sm:pt-4 xl:pt-6 text-lg sm:text-xl xl:text-2xl text-left sm:text-right text-accent cursor-pointer"
        >
          {{ $t('search-story') }} >
        </a>
      </div>
      <CategoryList :categories="category1" />
      <CategoryList :categories="category2" />
      <CategoryList :categories="category3" />
    </div>
  </div>
</template>

<script>
import CategoryList from '@/components/category/CategoryList.vue'

export default {
  name: 'Category',
  components: {
    CategoryList,
  },
  data() {
    return {
      category1: [
        'Orientation',
        'Hétéro',
        'Gay',
        'Lesbien',
        'Bisexuel',
        'Transexuel',
      ],
      category2: [
        'Type',
        'Soft',
        'Hard',
        'Réel',
        'Fictif',
        'Fantasme',
        'Romantique',
      ],
      category3: [
        'Pratique',
        'Sextoy',
        'Jeu érotique',
        'Fellation',
        'Cunnilingus',
        'Anulingus',
        'Sodomie',
        'Uro',
        'Fist',
        'Double pénétration',
        'Squirt',
        'Prostate',
      ],
    }
  },
}
</script>

<i18n>
{
  "fr": {
    "categories": "Catégories",
    "search-story": "Rechercher des histoires"
  }
}
</i18n>
