<template>
  <div class="bg-psgray mt-16 pb-4">
    <div class="mx-12">
      <div class="flex justify-between mb-8 pt-8">
        <h3 class="text-5xl text-psblack font-psbold text-left ">
          Catégories
        </h3>
        <a class="text-psred text-2xl pt-8 cursor-pointer">Trouver une histoire</a>
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
      category1: ['Orientation', 'Hétéro', 'Gay', 'Lesbien', 'Bisexuel', 'Transexuel'],
      category2: ['Type', 'Soft', 'Hard', 'Réel', 'Fictif', 'Fantasme', 'Romantique'],
      category3: ['Pratique', 'Sextoy', 'Jeu érotique', 'Fellation', 'Cunnilingus', 'Anulingus',
        'Sodomie', 'Uro', 'Fist', 'Double pénétration', 'Squirt', 'Prostate',
      ],
    }
  },
}
</script>
