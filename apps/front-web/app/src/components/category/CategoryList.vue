<template>
  <div class="flex flex-wrap justify-items-start gap-4 mb-8">
    <div
      v-for="(category, index) in categories"
      :key="category"
      class="py-3"
    >
      <span
        v-if="index == 0"
        class="py-3 px-6 text-2xl text-psred font-pssemibold"
      >
        {{ category }} :
      </span>
      <span
        v-else-if="index == 1"
        class="rounded-lg py-3 px-6 border bg-psred text-xl text-white"
      >
        {{ category }}
      </span>
      <span
        v-else
        class="rounded-lg py-3 px-6 border bg-white text-xl text-gray-600"
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
