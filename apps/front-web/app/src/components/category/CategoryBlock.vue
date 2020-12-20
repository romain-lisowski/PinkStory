<template>
  <div
    id="category"
    class="sm:mt-16 pb-4 sm:pb-12 bg-primary-inverse bg-opacity-5"
  >
    <div class="mx-12">
      <slot name="header"></slot>
      <CategoryList
        v-for="(categoryList, index) in categoryLists"
        :key="index"
        :category-list="categoryList"
      />
      <slot name="footer"></slot>
    </div>
  </div>
</template>

<script>
import CategoryList from '@/components/category/CategoryList.vue'
import { reactive } from 'vue'

export default {
  components: {
    CategoryList,
  },
  setup() {
    const data = reactive({
      categoryLists: [
        {
          type: 'Orientation',
          categories: ['Hétéro', 'Gay', 'Lesbien', 'Bisexuel', 'LGBT+'],
        },
        {
          type: 'Nombre',
          categories: ['Solitaire', 'Couple', 'Triolisme', 'Groupe'],
        },
        {
          type: 'Pratique',
          categories: [
            'Masturbation',
            'Sex oral',
            'Sodomie',
            'Jeu érotique',
            'Domination',
            'Soumission',
            'Autres BDSM',
            'Jeux de rôles',
          ],
        },
        {
          type: 'Moeurs',
          categories: ['Fétichisme', 'Echangisme', 'Libertinage'],
        },
        {
          type: 'Âge',
          categories: ['Jeune', 'Milf/Dilf', 'Mature'],
        },
        {
          type: 'Lieu',
          categories: [
            'A la maison',
            'Au bureau',
            'Dans un lieu public',
            'En pleine nature',
          ],
        },
      ],
    })

    return { ...data }
  },
}
</script>
