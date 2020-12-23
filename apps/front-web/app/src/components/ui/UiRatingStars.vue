<template>
  <div class="sm:text-2xl text-left">
    <span
      v-for="index in Math.floor(rating)"
      :key="`empty-${index}`"
      class="text-accent"
      >&#x2605;</span
    >
    <span
      v-show="rating % 1 > 0"
      class="bg-stars-gradient-to-r bg-clip-text text-opacity-0"
      style="-webkit-text-fill-color: transparent"
      >&#x2605;</span
    >
    <span
      v-for="index in 5 - Math.round(rating)"
      :key="`full-${index}`"
      class="text-primary"
      >&#x2605;</span
    >
  </div>
</template>

<script>
export default {
  props: {
    rating: {
      type: Number,
      default: 0,
    },
  },
}
</script>
