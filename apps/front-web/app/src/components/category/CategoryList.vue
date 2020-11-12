<template>
  <div class="flex flex-wrap justify-items-start gap-2 sm:gap-4 mb-4 sm:mb-8">
    <span class="py-2 pr-2 text-base sm:text-xl xl:text-2xl text-accent">
      {{ categoryList.type }} :
    </span>
    <div
      v-for="(category, index) in categoryList.categories"
      :key="index"
      class="py-3"
    >
      <CategoryListItem :category="category" />
    </div>
  </div>
</template>

<script>
import CategoryListItem from '@/components/category/CategoryListItem.vue'

export default {
  name: 'CategoryList',
  components: {
    CategoryListItem,
  },
  props: {
    categoryList: {
      type: Object,
      required: true,
    },
  },
}
</script>
