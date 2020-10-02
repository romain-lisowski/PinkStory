<template>
  <div class="flex flex-wrap justify-items-start gap-2 sm:gap-4 mb-8">
    <div
      v-for="(category, index) in categories"
      :key="category"
      class="py-3"
    >
      <span
        v-if="index == 0"
        class="py-3 px-6 text-base sm:text-2xl text-psred font-pssemibold"
      >
        {{ category }} :
      </span>
      <span
        v-else-if="index == 1"
        class="rounded-xl py-2 px-5 sm:py-3 sm:px-6 bg-psred text-base sm:text-xl text-white"
      >
        {{ category }}
      </span>
      <span
        v-else
        class="rounded-xl
        py-2
        px-5
        sm:py-3
        sm:px-6
        border
        bg-white
        dark:bg-pswhite
        dark:text-psblack-lighter
        text-base
        sm:text-xl
        text-gray-600"
      >
        {{ category }}
      </span>
    </div>
  </div>
</template>

<script>

export default {
  name: 'CategoryList',
  props: {
    categories: {
      type: Array,
      required: true,
    },
  },
}
</script>
