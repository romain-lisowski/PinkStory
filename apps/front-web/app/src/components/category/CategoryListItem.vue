<template>
  <span
    :class="active ? activeCategoryClasses : ''"
    class="rounded-xl py-2 px-4 sm:py-3 sm:px-6 border bg-white text-sm sm:text-md xl:text-xl text-gray-600 bg-opacity-100 cursor-pointer transition-all duration-300 ease-in"
    @click="toggleActive"
  >
    {{ category }}
  </span>
</template>

<script>
export default {
  name: 'CategoryListItem',
  props: {
    category: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      active: this.$store.state.categoryFilters.includes(this.category),
      activeCategoryClasses: ['bg-accent', 'text-primary-inverse'],
    }
  },
  methods: {
    toggleActive() {
      this.active = !this.active
      this.$store.dispatch('toggleFilter', { category: this.category })
    },
  },
}
</script>
