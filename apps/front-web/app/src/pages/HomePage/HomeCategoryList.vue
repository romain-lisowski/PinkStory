<template>
  <div class="flex flex-wrap justify-items-start gap-2 sm:gap-4 mb-4 sm:mb-8">
    <div v-for="(category, index) in categories" :key="category" class="py-3">
      <span
        v-if="index == 0"
        class="py-2 pr-2 text-base sm:text-xl xl:text-2xl text-accent"
      >
        {{ category }} :
      </span>
      <span
        v-else-if="index == 1"
        class="rounded-xl py-2 px-4 sm:py-3 sm:px-6 bg-accent text-sm sm:text-lg xl:text-xl text-primary bg-opacity-100"
      >
        {{ category }}
      </span>
      <span
        v-else
        class="rounded-xl py-2 px-4 sm:py-3 sm:px-6 border bg-white text-sm sm:text-lg xl:text-xl text-gray-600"
      >
        {{ category }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HomeCategoryList',
  props: {
    categories: {
      type: Array,
      required: true,
    },
  },
}
</script>
