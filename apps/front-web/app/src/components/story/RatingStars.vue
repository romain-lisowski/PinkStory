<template>
  <div class="text-left sm:text-2xl">
    <span
      v-for="index in Math.floor(rating)"
      :key="`empty-${index}`"
      class="text-accent"
      >&#x2605;</span
    >
    <span
      v-if="rating % 1 > 0"
      class="text-opacity-0 bg-stars-gradient-to-r bg-clip-text"
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
  name: 'RatingStars',
  props: {
    rating: {
      type: Number,
      default: 0,
    },
  },
}
</script>
