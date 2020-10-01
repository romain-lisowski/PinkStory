<template>
  <div class="flex flex-wrap justify-items-start gap-4 mb-8">
    <div
      v-for="(category, index) in categories"
      :key="category"
      class="py-3"
    >
      <span
        v-if="index == 0"
        class="rounded-l-full rounded-r-full py-3 px-6 border bg-white border-gray-400 text-xl text-gray-500"
      >
        {{ category }}
      </span>
      <span
        v-else-if="index == 1"
        class="rounded-l-full rounded-r-full py-3 px-6 border bg-psred text-xl text-white"
      >
        {{ category }}
      </span>
      <span
        v-else
        class="rounded-l-full rounded-r-full py-3 px-6 border border-gray-400 text-xl text-psblack"
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
